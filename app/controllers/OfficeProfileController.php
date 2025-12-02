<?php

require_once __DIR__ . '/../models/OfficeModel.php';

class OfficeProfileController extends Controller
{
    private OfficeModel $officeModel;

    public function __construct()
    {
        Auth::requireRole(['office']);
        $this->officeModel = new OfficeModel();
    }

    public function edit(): void
    {
        $office = $this->getOffice();
        if ($office === null) {
            $this->redirect('/');
            return;
        }

        $flash = $_SESSION['office_profile_flash'] ?? null;
        unset($_SESSION['office_profile_flash']);

        $this->render('office/profile/edit', [
            'office' => $office,
            'errors' => [],
            'flash' => $flash,
        ]);
    }

    public function update(): void
    {
        $office = $this->getOffice();
        if ($office === null) {
            $this->redirect('/');
            return;
        }

        $officeId = (int) $office['id'];
        $data = [
            'office_name' => trim($_POST['office_name'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'website' => trim($_POST['website'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
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

        $logoPath = null;
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] !== UPLOAD_ERR_NO_FILE) {
            $logoUploadResult = $this->handleLogoUpload($_FILES['logo']);
            if (is_string($logoUploadResult)) {
                $errors['logo'] = $logoUploadResult;
            } else {
                $logoPath = $logoUploadResult;
            }
        }

        if (!empty($errors)) {
            $prefill = array_merge($office, $data);
            $this->render('office/profile/edit', [
                'office' => $prefill,
                'errors' => $errors,
                'flash' => null,
            ]);
            return;
        }

        $updated = $this->officeModel->updateProfile($officeId, $data);
        if ($logoPath !== null) {
            $this->officeModel->updateLogo($officeId, $logoPath);
        }

        $_SESSION['office_profile_flash'] = $updated ? 'Profile updated.' : 'Unable to update profile.';
        $this->redirect('/office/profile/edit');
    }

    private function getOffice(): ?array
    {
        $userId = $_SESSION['user_id'] ?? null;
        if ($userId === null) {
            return null;
        }

        return $this->officeModel->getByUserId((int) $userId);
    }

    private function handleLogoUpload(array $file): string|bool
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return 'Failed to upload logo.';
        }

        $maxSize = 2 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            return 'Logo must be smaller than 2MB.';
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($mime, $allowed, true)) {
            return 'Only JPG, PNG or WEBP logos are allowed.';
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($ext === '') {
            $ext = $mime === 'image/png' ? 'png' : ($mime === 'image/webp' ? 'webp' : 'jpg');
        }

        $uploadsDir = __DIR__ . '/../../public/uploads/logos';
        if (!is_dir($uploadsDir) && !mkdir($uploadsDir, 0755, true) && !is_dir($uploadsDir)) {
            return 'Unable to save logo at this time.';
        }

        $filename = uniqid('logo_', true) . '.' . $ext;
        $destination = $uploadsDir . '/' . $filename;
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return 'Failed to save uploaded logo.';
        }

        return 'uploads/logos/' . $filename;
    }
}
