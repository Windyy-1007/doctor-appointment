<?php

require_once __DIR__ . '/../models/ReportModel.php';
require_once __DIR__ . '/../models/StaffActionModel.php';
require_once __DIR__ . '/../models/OfficeModel.php';
require_once __DIR__ . '/../models/AppointmentModel.php';
require_once __DIR__ . '/../models/UserModel.php';

class StaffReportController extends Controller
{
    private ReportModel $reportModel;
    private StaffActionModel $actionModel;
    private OfficeModel $officeModel;
    private AppointmentModel $appointmentModel;
    private UserModel $userModel;

    public function __construct()
    {
        Auth::requireRole(['staff', 'admin']);
        $this->reportModel = new ReportModel();
        $this->actionModel = new StaffActionModel();
        $this->officeModel = new OfficeModel();
        $this->appointmentModel = new AppointmentModel();
        $this->userModel = new UserModel();
    }

    public function index(): void
    {
        $reports = $this->reportModel->getOpenReports();
        $flash = $_SESSION['staff_reports_flash'] ?? null;
        unset($_SESSION['staff_reports_flash']);

        $this->render('staff/reports/index', [
            'reports' => $reports,
            'flash' => $flash,
        ]);
    }

    public function resolved(): void
    {
        $reports = $this->reportModel->getResolvedReports();
        $this->render('staff/reports/resolved', [
            'reports' => $reports,
        ]);
    }

    public function show($id): void
    {
        $reportId = (int) $id;
        $report = $this->reportModel->findById($reportId);
        if ($report === null) {
            $_SESSION['staff_reports_flash'] = 'Report not found.';
            $this->redirect('/staff/reports');
        }

        $target = $this->resolveTarget($report);
        $flash = $_SESSION['staff_reports_flash'] ?? null;
        unset($_SESSION['staff_reports_flash']);

        $this->render('staff/reports/show', [
            'report' => $report,
            'target' => $target,
            'flash' => $flash,
        ]);
    }

    public function setStatus($id): void
    {
        $reportId = (int) $id;
        $status = $_POST['status'] ?? '';
        $allowed = ['open', 'in_progress', 'resolved', 'closed'];

        if (!in_array($status, $allowed, true)) {
            $_SESSION['staff_reports_flash'] = 'Invalid status selected.';
            $this->redirect('/staff/reports/show/' . $reportId);
        }

        $resolvedBy = null;
        if (in_array($status, ['resolved', 'closed'], true)) {
            $user = Auth::user();
            $resolvedBy = $user['id'] ?? null;
        }

        $updated = $this->reportModel->updateStatus($reportId, $status, $resolvedBy);

        if ($updated && $resolvedBy !== null) {
            $this->actionModel->logAction($resolvedBy, 'resolve_report', 'report', $reportId, 'Report marked as ' . $status);
        }

        $_SESSION['staff_reports_flash'] = $updated ? 'Report status updated.' : 'Unable to update status.';
        $this->redirect('/staff/reports/show/' . $reportId);
    }

    private function resolveTarget(array $report): array
    {
        $target = ['type' => $report['target_type'], 'details' => null];
        $id = isset($report['target_id']) ? (int) $report['target_id'] : null;

        if ($id === null) {
            return $target;
        }

        switch ($report['target_type']) {
            case 'office':
                $office = $this->officeModel->findById($id);
                $target['details'] = $office ? [
                    'label' => 'Office',
                    'name' => $office['office_name'],
                    'status' => $office['status'],
                ] : null;
                break;
            case 'appointment':
                $appointment = $this->appointmentModel->findById($id);
                $target['details'] = $appointment ? [
                    'label' => 'Appointment',
                    'datetime' => $appointment['appointment_datetime'],
                ] : null;
                break;
            case 'user':
                $user = $this->userModel->findById($id);
                $target['details'] = $user ? [
                    'label' => 'User',
                    'name' => $user['name'],
                    'email' => $user['email'],
                ] : null;
                break;
            default:
                $target['details'] = null;
        }

        return $target;
    }
}
