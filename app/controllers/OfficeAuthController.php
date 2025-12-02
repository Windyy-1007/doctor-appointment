<?php

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/OfficeModel.php';

class OfficeAuthController extends Controller
{
    private UserModel $userModel;
    private OfficeModel $officeModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->officeModel = new OfficeModel();
    }

    public function showRegisterForm(): void
    {
        $success = $_SESSION['office_register_success'] ?? null;
        unset($_SESSION['office_register_success']);

        $this->render('office/auth/register', [
            'errors' => [],
            'old' => [],
            'success' => $success,
        ]);
    }

    public function register(): void
    {
        $errors = [];
        $officeName = trim($_POST['office_name'] ?? '');
        $contactName = trim($_POST['contact_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $website = trim($_POST['website'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($officeName === '') {
            $errors['office_name'] = 'Office name is required.';
        }

        if ($address === '') {
            $errors['address'] = 'Address is required.';
        }

        if ($phone === '') {
            $errors['phone'] = 'Phone number is required.';
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Valid email is required.';
        }

        if (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters.';
        }

        if ($password !== $confirmPassword) {
            $errors['confirm_password'] = 'Passwords must match.';
        }

        if ($this->userModel->findByEmail($email)) {
            $errors['email'] = 'Email is already in use.';
        }

        if (!empty($errors)) {
            $this->render('office/auth/register', [
                'errors' => $errors,
                'old' => [
                    'office_name' => $officeName,
                    'contact_name' => $contactName,
                    'email' => $email,
                    'address' => $address,
                    'phone' => $phone,
                    'website' => $website,
                    'description' => $description,
                ],
                'success' => null,
            ]);
            return;
        }

        $accountName = $contactName !== '' ? $contactName : $officeName;
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $userId = $this->userModel->createOfficeAccount($accountName, $email, $passwordHash);

        if ($userId === 0) {
            $errors['general'] = 'Unable to create your account right now. Please try again later.';
            $this->render('office/auth/register', [
                'errors' => $errors,
                'old' => [
                    'office_name' => $officeName,
                    'contact_name' => $contactName,
                    'email' => $email,
                    'address' => $address,
                    'phone' => $phone,
                    'website' => $website,
                    'description' => $description,
                ],
                'success' => null,
            ]);
            return;
        }

        $this->officeModel->createOfficeProfile($userId, [
            'office_name' => $officeName,
            'address' => $address,
            'phone' => $phone,
            'website' => $website,
            'description' => $description,
            'status' => 'pending',
        ]);

        $_SESSION['office_register_success'] = 'Thanks for registering. Your profile is pending approval.';
        $this->redirect('/office/register');
    }
}
