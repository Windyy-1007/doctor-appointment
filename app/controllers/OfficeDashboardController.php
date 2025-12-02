<?php

require_once __DIR__ . '/../models/OfficeModel.php';
require_once __DIR__ . '/../models/DoctorModel.php';
require_once __DIR__ . '/../models/AppointmentModel.php';

class OfficeDashboardController extends Controller
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
        $office = $this->getOffice();
        if ($office === null) {
            $this->redirect('/');
            return;
        }

        $appointments = $this->appointmentModel->getAppointmentsForOffice((int) $office['id']);
        $doctorCount = count($this->doctorModel->getByOfficeId((int) $office['id']));

        $upcoming = array_filter($appointments, function (array $appointment): bool {
            $slot = DateTime::createFromFormat('Y-m-d H:i:s', $appointment['appointment_datetime']);
            return $slot !== false && $slot >= new DateTime('now');
        });

        $stats = [
            'total' => count($appointments),
            'confirmed' => count(array_filter($appointments, fn ($appointment) => $appointment['status'] === 'confirmed')), 
            'pending' => count(array_filter($appointments, fn ($appointment) => $appointment['status'] === 'pending')),
        ];

        $this->render('office/dashboard/index', [
            'office' => $office,
            'doctorCount' => $doctorCount,
            'stats' => $stats,
            'upcomingAppointments' => array_slice($upcoming, 0, 5),
        ]);
    }

    private function getOffice(): ?array
    {
        $userId = $_SESSION['user_id'] ?? null;
        if ($userId === null) {
            return null;
        }

        return $this->officeModel->getByUserId((int) $userId);
    }
}
