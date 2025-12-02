<?php

class UserModel extends Model
{
    // Find a user by email address
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch();

        return $user === false ? null : $user;
    }

    // Create a patient account with the provided data
    public function createPatient(string $name, string $email, string $passwordHash): bool
    {
        $stmt = $this->db->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (:name, :email, :password_hash, :role)');

        return $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password_hash' => $passwordHash,
            'role' => 'patient',
        ]);
    }

    // Find user by primary key
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);

        $user = $stmt->fetch();

        return $user === false ? null : $user;
    }

    // Retrieve every user with their active flag (assumes `is_active` TINYINT column exists)
    public function getAllUsers(): array
    {
        $stmt = $this->db->query('SELECT id, name, email, role, created_at, IFNULL(is_active, 1) AS is_active FROM users ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    // Update name/email/role/active state for an existing user
    public function updateUser(int $id, array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE users SET name = :name, email = :email, role = :role, is_active = :is_active WHERE id = :id');
        return $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'is_active' => $data['is_active'] ? 1 : 0,
            'id' => $id,
        ]);
    }

    private function setActive(int $id, bool $active): bool
    {
        $stmt = $this->db->prepare('UPDATE users SET is_active = :active WHERE id = :id');
        return $stmt->execute([
            'active' => $active ? 1 : 0,
            'id' => $id,
        ]);
    }

    public function deactivateUser(int $id): bool
    {
        return $this->setActive($id, false);
    }

    public function activateUser(int $id): bool
    {
        return $this->setActive($id, true);
    }

    public function countByRole(string $role): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE role = :role');
        $stmt->execute(['role' => $role]);
        return (int) $stmt->fetchColumn();
    }

    public function countAll(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM users');
        return (int) $stmt->fetchColumn();
    }
}
