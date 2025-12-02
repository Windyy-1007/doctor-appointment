<?php

require_once __DIR__ . '/../models/UserModel.php';

class AdminUserController extends Controller
{
    private UserModel $userModel;

    public function __construct()
    {
        Auth::requireRole(['admin']);
        $this->userModel = new UserModel();
    }

    public function index(): void
    {
        $users = $this->userModel->getAllUsers();
        $flash = $_SESSION['admin_users_flash'] ?? null;
        unset($_SESSION['admin_users_flash']);

        $this->render('admin/users/index', [
            'users' => $users,
            'flash' => $flash,
        ]);
    }

    public function edit($id): void
    {
        $userId = (int) $id;
        $user = $this->userModel->findById($userId);
        if ($user === null) {
            $_SESSION['admin_users_flash'] = 'User not found.';
            $this->redirect('/admin/users');
        }

        $this->render('admin/users/edit', [
            'user' => $user,
            'errors' => [],
        ]);
    }

    public function update($id): void
    {
        $userId = (int) $id;
        $user = $this->userModel->findById($userId);
        if ($user === null) {
            $_SESSION['admin_users_flash'] = 'User not found.';
            $this->redirect('/admin/users');
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = trim($_POST['role'] ?? $user['role']);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        $errors = [];
        if ($name === '') {
            $errors['name'] = 'Name is required.';
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Valid email is required.';
        }

        $allowedRoles = ['admin', 'staff', 'office', 'patient'];
        if (!in_array($role, $allowedRoles, true)) {
            $errors['role'] = 'Invalid role selected.';
        }

        $existing = $this->userModel->findByEmail($email);
        if ($existing !== null && $existing['id'] !== $userId) {
            $errors['email'] = 'That email is already taken.';
        }

        if (!empty($errors)) {
            $user['name'] = $name;
            $user['email'] = $email;
            $user['role'] = $role;
            $user['is_active'] = $isActive;

            $this->render('admin/users/edit', [
                'user' => $user,
                'errors' => $errors,
            ]);
            return;
        }

        $updated = $this->userModel->updateUser($userId, [
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'is_active' => $isActive,
        ]);

        $_SESSION['admin_users_flash'] = $updated ? 'User updated successfully.' : 'Unable to update user.';
        $this->redirect('/admin/users');
    }

    public function deactivate($id): void
    {
        $userId = (int) $id;
        $this->userModel->deactivateUser($userId);
        $_SESSION['admin_users_flash'] = 'User deactivated.';
        $this->redirect('/admin/users');
    }

    public function activate($id): void
    {
        $userId = (int) $id;
        $this->userModel->activateUser($userId);
        $_SESSION['admin_users_flash'] = 'User re-activated.';
        $this->redirect('/admin/users');
    }
}
