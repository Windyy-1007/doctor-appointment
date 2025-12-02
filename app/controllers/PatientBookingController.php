<?php

require_once __DIR__ . '/../models/AppointmentModel.php';
require_once __DIR__ . '/../models/DoctorModel.php';
require_once __DIR__ . '/../models/OfficeModel.php';
require_once __DIR__ . '/../models/UserModel.php';

use PHPMailer\PHPMailer\PHPMailer;

class PatientBookingController extends Controller
{
    private const WORK_DAY_START = 9;
    private const WORK_DAY_END = 17;
    private const SLOT_DURATION_MINUTES = 30;
    private const DAYS_VISIBLE = 14;

    private AppointmentModel $appointmentModel;
    private DoctorModel $doctorModel;
    private OfficeModel $officeModel;
    private UserModel $userModel;

    public function __construct()
    {
        Auth::requireRole(['patient']);
        $this->appointmentModel = new AppointmentModel();
        $this->doctorModel = new DoctorModel();
        $this->officeModel = new OfficeModel();
        $this->userModel = new UserModel();
    }

    public function calendar(): void
    {
        $doctorId = (int) ($_GET['doctor_id'] ?? 0);
        $officeId = (int) ($_GET['office_id'] ?? 0);

        $resources = $this->loadDoctorAndOffice($doctorId, $officeId);
        if ($resources === null) {
            $this->redirect('/patient/specialties');
            return;
        }

        $window = $this->getBookingWindow();
        $days = $this->buildSlotMatrix($doctorId, $officeId, $window['start'], $window['end']);

        $this->render('patient/booking/calendar', [
            'doctor' => $resources['doctor'],
            'office' => $resources['office'],
            'days' => $days,
            'error' => null,
            'selectedSlot' => null,
        ]);
    }

    public function confirm(): void
    {
        $doctorId = (int) ($_POST['doctor_id'] ?? 0);
        $officeId = (int) ($_POST['office_id'] ?? 0);
        $slotDatetime = trim($_POST['slot_datetime'] ?? '');

        $resources = $this->loadDoctorAndOffice($doctorId, $officeId);
        if ($resources === null) {
            $this->redirect('/patient/specialties');
            return;
        }

        $window = $this->getBookingWindow();
        $validation = $this->validateSlot($slotDatetime, $doctorId, $officeId, $window['start'], $window['end']);

        if (!$validation['valid']) {
            $days = $this->buildSlotMatrix($doctorId, $window['start'], $window['end']);
            $this->render('patient/booking/calendar', [
                'doctor' => $resources['doctor'],
                'office' => $resources['office'],
                'days' => $days,
                'error' => $validation['message'],
                'selectedSlot' => $slotDatetime,
            ]);
            return;
        }

        $patientId = (int) ($_SESSION['user_id'] ?? 0);
        $patient = $this->userModel->findById($patientId);
        $appointmentId = $this->appointmentModel->createAppointment($patientId, $doctorId, $officeId, $slotDatetime);

        $this->sendConfirmationEmail($patient, $resources['doctor'], $resources['office'], $slotDatetime);

        $_SESSION['patient_appointments_flash'] = 'Appointment confirmed for ' . $validation['slot']->format('F j, Y \a\t H:i');
        $this->redirect('/patient/appointments');
    }

    private function loadDoctorAndOffice(int $doctorId, int $officeId): ?array
    {
        if ($doctorId <= 0 || $officeId <= 0) {
            return null;
        }

        $doctor = $this->doctorModel->findById($doctorId);
        if ($doctor === null || (int) $doctor['office_id'] !== $officeId) {
            return null;
        }

        $office = $this->officeModel->findById($officeId);
        if ($office === null || $office['status'] !== 'approved') {
            return null;
        }

        return [
            'doctor' => $doctor,
            'office' => $office,
        ];
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

    private function validateSlot(string $slotDatetime, int $doctorId, int $officeId, DateTime $windowStart, DateTime $windowEnd): array
    {
        if ($slotDatetime === '') {
            return ['valid' => false, 'message' => 'Please select a time slot.', 'slot' => null];
        }

        $slot = DateTime::createFromFormat('Y-m-d H:i:s', $slotDatetime);
        if ($slot === false) {
            return ['valid' => false, 'message' => 'Invalid time slot selected.', 'slot' => null];
        }

        if ($slot < $windowStart || $slot > $windowEnd) {
            return ['valid' => false, 'message' => 'Selected time is outside the booking window.', 'slot' => $slot];
        }

        $minutesFromStart = ((int) $slot->format('H') * 60 + (int) $slot->format('i')) - (self::WORK_DAY_START * 60);
        $daySpanMinutes = (self::WORK_DAY_END - self::WORK_DAY_START) * 60;

        if ($minutesFromStart < 0 || $minutesFromStart >= $daySpanMinutes || $minutesFromStart % self::SLOT_DURATION_MINUTES !== 0) {
            return ['valid' => false, 'message' => 'Please choose a slot that falls within working hours.', 'slot' => $slot];
        }

        if ($slot < new DateTime('now')) {
            return ['valid' => false, 'message' => 'Cannot book a slot in the past.', 'slot' => $slot];
        }

        $bookedSlots = $this->appointmentModel->getBookedSlotsForDoctorInRange(
            $doctorId,
            $officeId,
            $windowStart->format('Y-m-d H:i:s'),
            $windowEnd->format('Y-m-d H:i:s')
        );

        if (in_array($slotDatetime, $bookedSlots, true)) {
            return ['valid' => false, 'message' => 'This slot is already booked.', 'slot' => $slot];
        }

        return ['valid' => true, 'message' => '', 'slot' => $slot];
    }

    private function sendConfirmationEmail(?array $patient, array $doctor, array $office, string $slotDatetime): void
    {
        if ($patient === null || empty($patient['email'])) {
            return;
        }

        $autoloadPath = __DIR__ . '/../../vendor/autoload.php';
        if (file_exists($autoloadPath)) {
            require_once $autoloadPath;
        }

        if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
            return; // PHPMailer is not available yet; install via Composer (phpmailer/phpmailer)
        }

        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com'; // TODO: replace with your SMTP host
            $mail->SMTPAuth = true;
            $mail->Username = 'smtp-username'; // TODO: SMTP username
            $mail->Password = 'smtp-password'; // TODO: SMTP password
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
            $mail->setFrom('no-reply@example.com', 'Doctor Appointment');
            $mail->addAddress($patient['email'], $patient['name']);
            $mail->isHTML(true);
            $mail->Subject = 'Appointment Confirmation';
            $mail->Body = sprintf(
                '<p>Hi %s,</p><p>Your appointment with Dr. %s at %s has been confirmed for %s.</p><p>Location: %s</p><p>Thank you for booking with us!</p>',
                htmlspecialchars($patient['name']),
                htmlspecialchars($doctor['name']),
                htmlspecialchars($office['office_name']),
                htmlspecialchars((new DateTime($slotDatetime))->format('F j, Y \a\t H:i')),
                htmlspecialchars($office['address'])
            );
            $mail->AltBody = "Appointment confirmed with Dr. {$doctor['name']} at {$office['office_name']} on " . (new DateTime($slotDatetime))->format('F j, Y \a\t H:i');

            $mail->send();
        } catch (Exception $e) {
            error_log('Patient booking email failed: ' . $e->getMessage());
        }
    }
}
