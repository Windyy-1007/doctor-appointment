<?php

require_once __DIR__ . '/../models/OfficeModel.php';
require_once __DIR__ . '/../models/DoctorModel.php';
require_once __DIR__ . '/../models/AppointmentModel.php';

class OfficeScheduleController extends Controller
{
    private OfficeModel $officeModel;
    private DoctorModel $doctorModel;
    private AppointmentModel $appointmentModel;

    public function __construct()
    {
        Auth::requireRole(['office']);
        $this->officeModel = new OfficeModel();
        $this->doctorModel = new DoctorModel();
        $this->appointmentModel = new AppointmentModel();
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
            $bookedSlots[$key] = true;
        }

        $days = [];
        for ($day = 0; $day < $dayCount; $day++) {
            $currentDay = (clone $windowStart)->modify("+$day day");
            $currentDay->setTime(9, 0, 0);
            $dateKey = $currentDay->format('Y-m-d');
            $days[$dateKey] = [
                'label' => $currentDay->format('l, F j'),
                'doctors' => [],
            ];

            foreach ($doctors as $doctor) {
                $slots = [];
                $slotTime = clone $currentDay;
                for ($slot = 0; $slot < $slotsPerDay; $slot++) {
                    $slotKey = $doctor['id'] . '_' . $slotTime->format('Y-m-d H:i:s');
                    $slots[] = [
                        'time' => $slotTime->format('H:i'),
                        'datetime' => $slotTime->format('Y-m-d H:i:s'),
                        'status' => isset($bookedSlots[$slotKey]) ? 'booked' : 'available',
                    ];
                    $slotTime->modify('+30 minutes');
                }

                $days[$dateKey]['doctors'][] = [
                    'id' => $doctor['id'],
                    'name' => $doctor['name'],
                    'slots' => $slots,
                ];
            }
        }

        $this->render('office/schedule/index', [
            'days' => $days,
        ]);
    }

    // Placeholder for future blocking support
    public function blockSlot(): void
    {
        // To implement: toggle availability by storing blocked slots in a separate table
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
