<?php

class Auth
{
    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }

        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'] ?? null,
            'role' => $_SESSION['user_role'] ?? null,
        ];
    }

    public static function check(): bool
    {
        return !empty($_SESSION['user_id']);
    }

    public static function roleIs(string $role): bool
    {
        return self::check() && ($_SESSION['user_role'] ?? '') === $role;
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }
    }

    public static function requireRole(array $roles): void
    {
        self::requireLogin();

        $role = $_SESSION['user_role'] ?? '';
        if (!in_array($role, $roles, true)) {
            http_response_code(403);
            echo '<h1>403 Forbidden</h1><p>You do not have permission to view this page.</p>';
            exit;
        }
    }
}
