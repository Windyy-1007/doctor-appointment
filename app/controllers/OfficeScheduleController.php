<?php

require_once __DIR__ . '/../models/OfficeModel.php';
require_once __DIR__ . '/../models/DoctorModel.php';
require_once __DIR__ . '/../models/AppointmentModel.php';
require_once __DIR__ . '/../models/OfficeSlotModel.php';

class OfficeScheduleController extends Controller
{
    private OfficeModel $officeModel;
    private DoctorModel $doctorModel;
    private AppointmentModel $appointmentModel;
    private OfficeSlotModel $slotModel;

    public function __construct()
    {
        Auth::requireRole(['office']);
        $this->officeModel = new OfficeModel();
        $this->doctorModel = new DoctorModel();
        $this->appointmentModel = new AppointmentModel();
        $this->slotModel = new OfficeSlotModel();
    }

    public function index(): void
    {
        $officeId = $this->getOfficeId();
        if ($officeId === null) {
            $this->redirect('/');
            return;
        }

        $doctors = $this->doctorModel->getByOfficeId($officeId);
        $dayCount = 14;
        $slotsPerDay = 16; // 8 hours * 2 slots per hour (09:00-17:00)

        $windowStart = new DateTime('today');
        $windowEnd = (clone $windowStart)->modify('+' . $dayCount . ' days')->setTime(23, 59, 59);

        $appointments = $this->appointmentModel->getAppointmentsForOfficeInRange(
            $officeId,
            $windowStart->format('Y-m-d H:i:s'),
            $windowEnd->format('Y-m-d H:i:s')
        );

        $bookedSlots = [];
        foreach ($appointments as $appointment) {
            $key = $appointment['doctor_id'] . '_' . $appointment['appointment_datetime'];
            $bookedSlots[$key] = $appointment;
        }

        $slotEntries = $this->slotModel->getSlotsForOfficeInRange(
            $officeId,
            $windowStart->format('Y-m-d H:i:s'),
            $windowEnd->format('Y-m-d H:i:s')
        );
        $slotMap = [];
        foreach ($slotEntries as $entry) {
            $slotMap[$entry['doctor_id']][$entry['slot_datetime']] = $entry;
        }

        $flash = $_SESSION['office_schedule_flash'] ?? null;
        unset($_SESSION['office_schedule_flash']);

        $days = [];
        for ($day = 0; $day < $dayCount; $day++) {
            $currentDay = (clone $windowStart)->modify("+{$day} days");
            $currentDay->setTime(9, 0, 0);
            $dateKey = $currentDay->format('Y-m-d');
            $label = $currentDay->format('l, F j');

            foreach ($doctors as $doctor) {
                if (!isset($days[$dateKey])) {
                    $days[$dateKey] = [
                        'label' => $label,
                        'doctors' => [],
                    ];
                }
                $slots = [];
                $slotKeyed = [];
                $slotTime = clone $currentDay;

                for ($slot = 0; $slot < $slotsPerDay; $slot++) {
                    $slotDatetime = $slotTime->format('Y-m-d H:i:s');
                    $slotKey = $doctor['id'] . '_' . $slotDatetime;
                    $status = 'available';
                    $appointment = $bookedSlots[$slotKey] ?? null;
                    $override = $slotMap[$doctor['id']][$slotDatetime] ?? null;

                    if ($appointment !== null) {
                        $status = 'booked';
                    } elseif ($override !== null && $override['status'] === 'blocked') {
                        $status = 'blocked';
                    }

                    $slots[] = [
                        'doctor_id' => $doctor['id'],
                        'doctor_name' => $doctor['name'],
                        'time' => $slotTime->format('H:i'),
                        'datetime' => $slotDatetime,
                        'status' => $status,
                        'appointment' => $appointment,
                        'slot_entry' => $override,
                    ];
                    $slotKeyed[$slotDatetime] = true;
                    $slotTime->modify('+30 minutes');
                }

                foreach ($slotMap[$doctor['id']] ?? [] as $customDatetime => $entry) {
                    if (isset($slotKeyed[$customDatetime]) || strpos($customDatetime, $dateKey) !== 0) {
                        continue;
                    }

                    $slots[] = [
                        'doctor_id' => $doctor['id'],
                        'doctor_name' => $doctor['name'],
                        'time' => (new DateTime($customDatetime))->format('H:i'),
                        'datetime' => $customDatetime,
                        'status' => $entry['status'],
                        'appointment' => $bookedSlots[$doctor['id'] . '_' . $customDatetime] ?? null,
                        'slot_entry' => $entry,
                    ];
                }

                $days[$dateKey]['label'] = $label;
                $days[$dateKey]['doctors'][] = [
                    'id' => $doctor['id'],
                    'name' => $doctor['name'],
                    'slots' => $slots,
                ];
            }
        }

        $this->render('office/schedule/index', [
            'days' => array_values($days),
            'doctors' => $doctors,
            'flash' => $flash,
        ]);
    }

    public function addSlot(): void
    {
        $officeId = $this->getOfficeId();
        if ($officeId === null) {
            $this->redirect('/');
            return;
        }

        $doctorId = (int) ($_POST['doctor_id'] ?? 0);
        $slotDatetime = trim($_POST['slot_datetime'] ?? '');
        $note = trim($_POST['note'] ?? '');

        $parsed = DateTime::createFromFormat('Y-m-d\TH:i', $slotDatetime);
        if ($doctorId <= 0 || $slotDatetime === '' || $parsed === false) {
            $_SESSION['office_schedule_flash'] = 'Please select a doctor and valid time to add a slot.';
            $this->redirect('/office/schedule');
            return;
        }

        $normalized = $parsed->format('Y-m-d H:i:s');
        $created = $this->slotModel->upsertSlot($officeId, $doctorId, $normalized, 'available', $note ?: null);

        $_SESSION['office_schedule_flash'] = $created ? 'Working slot added.' : 'Unable to add slot.';
        $this->redirect('/office/schedule');
    }

    public function blockSlot(): void
    {
        $this->ensureOfficeContext(fn(int $officeId) => $this->handleBlockSlot($officeId));
    }

    public function unblockSlot(): void
    {
        $this->ensureOfficeContext(fn(int $officeId) => $this->handleUnblockSlot($officeId));
    }

    public function cancelAppointment(): void
    {
        $this->ensureOfficeContext(fn(int $officeId) => $this->handleCancelAppointment($officeId));
    }

    public function markAttendance(): void
    {
        $this->ensureOfficeContext(fn(int $officeId) => $this->handleMarkAttendance($officeId));
    }

    private function handleBlockSlot(int $officeId): void
    {
        $doctorId = (int) ($_POST['doctor_id'] ?? 0);
        $slotDatetime = trim($_POST['slot_datetime'] ?? '');
        $reason = trim($_POST['reason'] ?? 'Blocked by office');

        if ($doctorId <= 0 || $slotDatetime === '' || DateTime::createFromFormat('Y-m-d H:i:s', $slotDatetime) === false) {
            $_SESSION['office_schedule_flash'] = 'Invalid slot selected for blocking.';
            $this->redirect('/office/schedule');
            return;
        }

        $blocked = $this->slotModel->upsertSlot($officeId, $doctorId, $slotDatetime, 'blocked', $reason ?: null);
        $_SESSION['office_schedule_flash'] = $blocked ? 'Slot blocked.' : 'Unable to block that slot.';
        $this->redirect('/office/schedule');
    }

    private function handleUnblockSlot(int $officeId): void
    {
        $slotId = (int) ($_POST['slot_id'] ?? 0);
        if ($slotId <= 0) {
            $_SESSION['office_schedule_flash'] = 'Slot not found.';
            $this->redirect('/office/schedule');
            return;
        }

        $deleted = $this->slotModel->deleteSlot($officeId, $slotId);
        $_SESSION['office_schedule_flash'] = $deleted ? 'Slot unblocked.' : 'Unable to unblock that slot.';
        $this->redirect('/office/schedule');
    }

    private function handleCancelAppointment(int $officeId): void
    {
        $appointmentId = (int) ($_POST['appointment_id'] ?? 0);
        if ($appointmentId <= 0) {
            $_SESSION['office_schedule_flash'] = 'Appointment not specified.';
            $this->redirect('/office/schedule');
            return;
        }

        $cancelled = $this->appointmentModel->updateStatus($appointmentId, $officeId, 'cancelled');
        $_SESSION['office_schedule_flash'] = $cancelled ? 'Appointment cancelled.' : 'Unable to cancel appointment.';
        $this->redirect('/office/schedule');
    }

    private function handleMarkAttendance(int $officeId): void
    {
        $appointmentId = (int) ($_POST['appointment_id'] ?? 0);
        if ($appointmentId <= 0) {
            $_SESSION['office_schedule_flash'] = 'Appointment not specified.';
            $this->redirect('/office/schedule');
            return;
        }

        $marked = $this->appointmentModel->updateStatus($appointmentId, $officeId, 'attended');
        $_SESSION['office_schedule_flash'] = $marked ? 'Attendance recorded.' : 'Unable to update attendance.';
        $this->redirect('/office/schedule');
    }

    private function ensureOfficeContext(callable $handler): void
    {
        $officeId = $this->getOfficeId();
        if ($officeId === null) {
            $this->redirect('/');
            return;
        }

        $handler($officeId);
    }

    private function getOfficeId(): ?int
    {
        $userId = $_SESSION['user_id'] ?? null;
        if ($userId === null) {
            return null;
        }

        $office = $this->officeModel->getByUserId((int) $userId);
        return $office['id'] ?? null;
    }
}
