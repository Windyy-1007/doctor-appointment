<?php

class OfficeModel extends Model
{
    public function getPendingOffices(): array
    {
        $stmt = $this->db->prepare(
            'SELECT o.*, u.name AS owner_name, u.email AS owner_email
            FROM offices o
            JOIN users u ON u.id = o.user_id
            WHERE o.status = :status
            ORDER BY o.created_at ASC'
        );
        $stmt->execute(['status' => 'pending']);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM offices WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $office = $stmt->fetch();

        return $office === false ? null : $office;
    }

    public function setStatus(int $id, string $status): bool
    {
        $allowed = ['pending', 'approved', 'deactivated'];
        if (!in_array($status, $allowed, true)) {
            return false;
        }

        $stmt = $this->db->prepare('UPDATE offices SET status = :status WHERE id = :id');
        return $stmt->execute([
            'status' => $status,
            'id' => $id,
        ]);
    }

    public function countAll(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM offices');
        return (int) $stmt->fetchColumn();
    }
}
