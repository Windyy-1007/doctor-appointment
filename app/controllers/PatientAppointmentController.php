<?php

require_once __DIR__ . '/../models/AppointmentModel.php';
require_once __DIR__ . '/../models/DoctorModel.php';
require_once __DIR__ . '/../models/OfficeModel.php';

class PatientAppointmentController extends Controller
{
    private const WORK_DAY_START = 9;
    private const WORK_DAY_END = 17;
    private const SLOT_DURATION_MINUTES = 30;
    private const DAYS_VISIBLE = 14;

    private AppointmentModel $appointmentModel;
    private DoctorModel $doctorModel;
    private OfficeModel $officeModel;

    public function __construct()
    {
        Auth::requireRole(['patient']);
        $this->appointmentModel = new AppointmentModel();
        $this->doctorModel = new DoctorModel();
        $this->officeModel = new OfficeModel();
    }

    public function index(): void
    {
        $patientId = (int) ($_SESSION['user_id'] ?? 0);
        $appointments = $this->appointmentModel->getAppointmentsForPatient($patientId);
        $flash = $_SESSION['patient_appointments_flash'] ?? null;
        unset($_SESSION['patient_appointments_flash']);

        $this->render('patient/appointments/index', [
            'appointments' => $appointments,
            'flash' => $flash,
        ]);
    }

    public function cancel($id): void
    {
        $patientId = (int) ($_SESSION['user_id'] ?? 0);
        $appointment = $this->appointmentModel->findForPatient((int) $id, $patientId);
        if ($appointment === null) {
            $this->redirect('/patient/appointments');
            return;
        }

        $appointmentDatetime = new DateTime($appointment['appointment_datetime']);
        $now = new DateTime('now');

        if ($appointmentDatetime < $now || $appointment['status'] === 'cancelled') {
            $_SESSION['patient_appointments_flash'] = 'Cannot cancel this appointment.';
            $this->redirect('/patient/appointments');
            return;
        }

        $this->appointmentModel->updateStatusForPatient($appointment['id'], $patientId, 'cancelled');
        $_SESSION['patient_appointments_flash'] = 'Appointment cancelled.';
        $this->redirect('/patient/appointments');
    }

    public function showRescheduleForm($id): void
    {
        $patientId = (int) ($_SESSION['user_id'] ?? 0);
        $appointment = $this->appointmentModel->findForPatient((int) $id, $patientId);
        if ($appointment === null || $appointment['status'] === 'cancelled') {
            $this->redirect('/patient/appointments');
            return;
        }

        $doctor = $this->doctorModel->findById((int) $appointment['doctor_id']);
        $office = $this->officeModel->findById((int) $appointment['office_id']);
        if ($doctor === null || $office === null) {
            $this->redirect('/patient/appointments');
            return;
        }
        $window = $this->getBookingWindow();
        $days = $this->buildSlotMatrix($doctor['id'], (int) $office['id'], $window['start'], $window['end']);

        $this->render('patient/appointments/reschedule', [
            'appointment' => $appointment,
            'doctor' => $doctor,
            'office' => $office,
            'days' => $days,
            'error' => null,
            'selectedSlot' => null,
        ]);
    }

    public function reschedule($id): void
    {
        $patientId = (int) ($_SESSION['user_id'] ?? 0);
        $appointment = $this->appointmentModel->findForPatient((int) $id, $patientId);
        if ($appointment === null || $appointment['status'] === 'cancelled') {
            $this->redirect('/patient/appointments');
            return;
        }

        $slotDatetime = trim($_POST['slot_datetime'] ?? '');
        $doctorId = (int) $appointment['doctor_id'];
        $officeId = (int) $appointment['office_id'];
        $window = $this->getBookingWindow();
        $validation = $this->validateSlot($slotDatetime, $doctorId, $officeId, $window['start'], $window['end'], $appointment['appointment_datetime']);

        if (!$validation['valid']) {
            $doctor = $this->doctorModel->findById($doctorId);
            $office = $this->officeModel->findById($officeId);
            if ($doctor === null || $office === null) {
                $this->redirect('/patient/appointments');
                return;
            }
            $days = $this->buildSlotMatrix($doctorId, $officeId, $window['start'], $window['end']);

            $this->render('patient/appointments/reschedule', [
                'appointment' => $appointment,
                'doctor' => $doctor,
                'office' => $office,
                'days' => $days,
                'error' => $validation['message'],
                'selectedSlot' => $slotDatetime,
            ]);
            return;
        }

        $this->appointmentModel->updateDatetimeForPatient($appointment['id'], $patientId, $slotDatetime);
        $_SESSION['patient_appointments_flash'] = 'Appointment rescheduled to ' . $validation['slot']->format('F j, Y \a\t H:i');
        $this->redirect('/patient/appointments');
    }

    private function getBookingWindow(): array
    {
        $start = new DateTime('today');
        $start->setTime(0, 0, 0);
        $end = (clone $start)->modify('+' . (self::DAYS_VISIBLE - 1) . ' days');
        $end->setTime(self::WORK_DAY_END, 0, 0);

        return ['start' => $start, 'end' => $end];
    }

    private function buildSlotMatrix(int $doctorId, int $officeId, DateTime $windowStart, DateTime $windowEnd): array
    {
        $days = [];
        $slotsPerDay = (int) (((self::WORK_DAY_END - self::WORK_DAY_START) * 60) / self::SLOT_DURATION_MINUTES);
        $bookedSlots = array_flip($this->appointmentModel->getBookedSlotsForDoctorInRange(
            $doctorId,
            $officeId,
            $windowStart->format('Y-m-d H:i:s'),
            $windowEnd->format('Y-m-d H:i:s')
        ));

        for ($day = 0; $day < self::DAYS_VISIBLE; $day++) {
            $currentDay = (clone $windowStart)->modify("+{$day} days");
            $currentDay->setTime(self::WORK_DAY_START, 0, 0);
            $dateKey = $currentDay->format('Y-m-d');
            $label = $currentDay->format('l, F j');
            $slots = [];
            $slotTime = clone $currentDay;

            for ($slot = 0; $slot < $slotsPerDay; $slot++) {
                $slotKey = $slotTime->format('Y-m-d H:i:s');
                $slots[] = [
                    'time' => $slotTime->format('H:i'),
                    'datetime' => $slotKey,
                    'is_booked' => isset($bookedSlots[$slotKey]),
                ];
                $slotTime->modify('+' . self::SLOT_DURATION_MINUTES . ' minutes');
            }

            $days[] = [
                'date' => $dateKey,
                'label' => $label,
                'slots' => $slots,
            ];
        }

        return $days;
    }

    private function validateSlot(string $slotDatetime, int $doctorId, int $officeId, DateTime $windowStart, DateTime $windowEnd, ?string $ignoreDatetime = null): array
    {
        if ($slotDatetime === '') {
            return ['valid' => false, 'message' => 'Please select a new slot.', 'slot' => null];
        }

        $slot = DateTime::createFromFormat('Y-m-d H:i:s', $slotDatetime);
        if ($slot === false) {
            return ['valid' => false, 'message' => 'Invalid time slot selected.', 'slot' => null];
        }

        if ($slot < $windowStart || $slot > $windowEnd) {
            return ['valid' => false, 'message' => 'Selected time is outside the rescheduling window.', 'slot' => $slot];
        }

        $minutesFromStart = ((int) $slot->format('H') * 60 + (int) $slot->format('i')) - (self::WORK_DAY_START * 60);
        $daySpanMinutes = (self::WORK_DAY_END - self::WORK_DAY_START) * 60;

        if ($minutesFromStart < 0 || $minutesFromStart >= $daySpanMinutes || $minutesFromStart % self::SLOT_DURATION_MINUTES !== 0) {
            return ['valid' => false, 'message' => 'Please choose a slot that falls within working hours.', 'slot' => $slot];
        }

        if ($slot < new DateTime('now')) {
            return ['valid' => false, 'message' => 'Cannot select a slot in the past.', 'slot' => $slot];
        }

        $bookedSlots = $this->appointmentModel->getBookedSlotsForDoctorInRange(
            $doctorId,
            $officeId,
            $windowStart->format('Y-m-d H:i:s'),
            $windowEnd->format('Y-m-d H:i:s')
        );

        if (in_array($slotDatetime, $bookedSlots, true) && $slotDatetime !== $ignoreDatetime) {
            return ['valid' => false, 'message' => 'This slot is already taken.', 'slot' => $slot];
        }

        return ['valid' => true, 'message' => '', 'slot' => $slot];
    }
}
