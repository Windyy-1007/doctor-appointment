<?php

require_once __DIR__ . '/../models/OfficeModel.php';
require_once __DIR__ . '/../models/UserModel.php';

class AdminOfficeController extends Controller
{
    private OfficeModel $officeModel;
    private UserModel $userModel;

    public function __construct()
    {
        Auth::requireRole(['admin']);
        $this->officeModel = new OfficeModel();
        $this->userModel = new UserModel();
    }

    public function index(): void
    {
        $this->pending();
    }

    public function pending(): void
    {
        $offices = $this->officeModel->getPendingOffices();
        $flash = $_SESSION['admin_offices_flash'] ?? null;
        unset($_SESSION['admin_offices_flash']);

        $this->render('admin/offices/pending', [
            'offices' => $offices,
            'flash' => $flash,
        ]);
    }

    public function approve($id): void
    {
        $updated = $this->officeModel->setStatus((int) $id, 'approved');
        // If office status update succeeded, activate the associated user account
        if ($updated) {
            $office = $this->officeModel->findById((int) $id);
            if ($office && isset($office['user_id'])) {
                $this->userModel->activateUser((int) $office['user_id']);
            }
        }

        $_SESSION['admin_offices_flash'] = $updated ? 'Office approved.' : 'Could not approve office.';
        $this->redirect('/admin/offices/pending');
    }

    public function reject($id): void
    {
        // Reject means the office remains inactive and is marked as deactivated
        $updated = $this->officeModel->setStatus((int) $id, 'deactivated');
        // If deactivation succeeded, ensure the user account is deactivated as well
        if ($updated) {
            $office = $this->officeModel->findById((int) $id);
            if ($office && isset($office['user_id'])) {
                $this->userModel->deactivateUser((int) $office['user_id']);
            }
        }

        $_SESSION['admin_offices_flash'] = $updated ? 'Office rejected.' : 'Could not reject office.';
        $this->redirect('/admin/offices/pending');
    }
}
