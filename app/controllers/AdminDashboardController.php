<?php

require_once __DIR__ . '/../models/AppointmentModel.php';
require_once __DIR__ . '/../models/OfficeModel.php';
require_once __DIR__ . '/../models/UserModel.php';

class AdminDashboardController extends Controller
{
    private AppointmentModel $appointmentModel;
    private OfficeModel $officeModel;
    private UserModel $userModel;

    public function __construct()
    {
        Auth::requireRole(['admin']);
        $this->appointmentModel = new AppointmentModel();
        $this->officeModel = new OfficeModel();
        $this->userModel = new UserModel();
    }

    public function index(): void
    {
        $stats = $this->appointmentModel->getAppointmentsCountPerWeek(4);
        $totals = [
            'appointments' => $this->appointmentModel->countAll(),
            'offices' => $this->officeModel->countAll(),
            'patients' => $this->userModel->countByRole('patient'),
        ];

        $this->render('admin/dashboard/index', [
            'stats' => $stats,
            'totals' => $totals,
        ]);
    }
}
