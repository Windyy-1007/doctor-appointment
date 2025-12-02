<?php

require_once __DIR__ . '/../models/OfficeModel.php';
require_once __DIR__ . '/../models/StaffActionModel.php';
require_once __DIR__ . '/../models/ReportModel.php';

class StaffModerationController extends Controller
{
    private OfficeModel $officeModel;
    private StaffActionModel $actionModel;
    private ReportModel $reportModel;

    public function __construct()
    {
        Auth::requireRole(['staff', 'admin']);
        $this->officeModel = new OfficeModel();
        $this->actionModel = new StaffActionModel();
        $this->reportModel = new ReportModel();
    }

    public function offices(): void
    {
        $offices = $this->officeModel->getAllWithOpenReportCount();
        $flash = $_SESSION['staff_moderation_flash'] ?? null;
        unset($_SESSION['staff_moderation_flash']);

        $this->render('staff/moderation/offices', [
            'offices' => $offices,
            'flash' => $flash,
        ]);
    }

    public function editOffice($id): void
    {
        $office = $this->officeModel->findById((int) $id);
        if ($office === null) {
            $_SESSION['staff_moderation_flash'] = 'Office not found.';
            $this->redirect('/staff/moderation/offices');
        }

        $this->render('staff/moderation/edit_office', [
            'office' => $office,
            'errors' => [],
        ]);
    }

    public function updateOffice($id): void
    {
        $officeId = (int) $id;
        $office = $this->officeModel->findById($officeId);
        if ($office === null) {
            $_SESSION['staff_moderation_flash'] = 'Office not found.';
            $this->redirect('/staff/moderation/offices');
        }

        $data = [
            'office_name' => trim($_POST['office_name'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'status' => trim($_POST['status'] ?? $office['status']),
        ];

        $errors = [];
        if ($data['office_name'] === '') {
            $errors['office_name'] = 'Office name is required.';
        }

        if ($data['address'] === '') {
            $errors['address'] = 'Address is required.';
        }

        if ($data['phone'] === '') {
            $errors['phone'] = 'Phone is required.';
        }

        if (!empty($errors)) {
            $office = array_merge($office, $data);
            $this->render('staff/moderation/edit_office', [
                'office' => $office,
                'errors' => $errors,
            ]);
            return;
        }

        $updated = $this->officeModel->updateOfficeDetails($officeId, $data);
        $user = Auth::user();
        $staffId = $user['id'] ?? null;
        if ($staffId !== null) {
            $this->actionModel->logAction($staffId, 'edit_office', 'office', $officeId, 'Office profile edited by staff.');
        }

        $_SESSION['staff_moderation_flash'] = $updated ? 'Office updated.' : 'Unable to update office.';
        $this->redirect('/staff/moderation/offices');
    }

    public function deactivateOffice($id): void
    {
        $officeId = (int) $id;
        $success = $this->officeModel->setStatus($officeId, 'deactivated');
        $user = Auth::user();
        $staffId = $user['id'] ?? null;

        if ($staffId !== null) {
            $this->actionModel->logAction($staffId, 'deactivate_office', 'office', $officeId, 'Office deactivated due to moderation.');
        }

        $_SESSION['staff_moderation_flash'] = $success ? 'Office deactivated.' : 'Unable to deactivate office.';
        $this->redirect('/staff/moderation/offices');
    }

    // Convenience aliases for clean URLs
    public function edit($id): void
    {
        $this->editOffice($id);
    }

    public function update($id): void
    {
        $this->updateOffice($id);
    }

    public function deactivate($id): void
    {
        $this->deactivateOffice($id);
    }
}
