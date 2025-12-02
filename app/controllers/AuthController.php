<?php

require_once __DIR__ . '/../models/UserModel.php';

class AuthController extends Controller
{
    // Display registration form
    public function showRegisterForm(): void
    {
        $this->render('auth/register', [
            'errors' => [],
            'old' => [],
        ]);
    }

    // Handle registration submission
    public function register(): void
    {
        $errors = [];
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if ($name === '') {
            $errors['name'] = 'Name is required.';
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Valid email is required.';
        }

        if (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters.';
        }

        if ($password !== $confirm) {
            $errors['confirm_password'] = 'Passwords do not match.';
        }

        $userModel = new UserModel();

        if (empty($errors) && $userModel->findByEmail($email)) {
            $errors['email'] = 'An account with that email already exists.';
        }

        if (!empty($errors)) {
            $this->render('auth/register', [
                'errors' => $errors,
                'old' => [
                    'name' => $name,
                    'email' => $email,
                ],
            ]);
            return;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $created = $userModel->createPatient($name, $email, $passwordHash);

        if ($created) {
            $_SESSION['auth_success'] = 'Account created, please log in.';
            $this->redirect('/auth/login');
        } else {
            $this->render('auth/register', [
                'errors' => ['general' => 'Could not create account. Please try again.'],
                'old' => [
                    'name' => $name,
                    'email' => $email,
                ],
            ]);
        }
    }

    // Display login form
    public function showLoginForm(): void
    {
        $successMessage = $_SESSION['auth_success'] ?? null;
        unset($_SESSION['auth_success']);

        $this->render('auth/login', [
            'error' => null,
            'old' => [],
            'success' => $successMessage,
        ]);
    }

    // Handle login submission
    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $error = null;

        if ($email === '' || $password === '') {
            $error = 'Email and password are required.';
        }

        $userModel = new UserModel();
        $user = null;

        if ($error === null) {
            $user = $userModel->findByEmail($email);

            if ($user === null || !password_verify($password, $user['password_hash'])) {
                $error = 'Invalid credentials.';
            }
        }

        if ($error !== null) {
            $this->render('auth/login', [
                'error' => $error,
                'old' => ['email' => $email],
                'success' => null,
            ]);
            return;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        $this->redirect('/home/index');
    }

    // Logout current user
    public function logout(): void
    {
        session_unset();
        session_destroy();
        session_start();

        $this->redirect('/auth/login');
    }
}
