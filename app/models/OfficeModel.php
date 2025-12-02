<?php

class OfficeModel extends Model
{
    public function createOfficeProfile(int $userId, array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO offices (user_id, office_name, address, phone, website, description, status, created_at)
            VALUES (:user_id, :office_name, :address, :phone, :website, :description, :status, :created_at)'
        );

        $stmt->execute([
            'user_id' => $userId,
            'office_name' => $data['office_name'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'website' => $data['website'] ?? null,
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'pending',
            'created_at' => $data['created_at'] ?? date('Y-m-d H:i:s'),
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function getByUserId(int $userId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM offices WHERE user_id = :user_id LIMIT 1');
        $stmt->execute(['user_id' => $userId]);
        $office = $stmt->fetch();

        return $office === false ? null : $office;
    }

    public function updateProfile(int $officeId, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE offices SET office_name = :office_name, address = :address, phone = :phone, website = :website, description = :description WHERE id = :id'
        );
        return $stmt->execute([
            'office_name' => $data['office_name'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'website' => $data['website'] ?? null,
            'description' => $data['description'] ?? null,
            'id' => $officeId,
        ]);
    }

    public function updateLogo(int $officeId, string $logoPath): bool
    {
        $stmt = $this->db->prepare('UPDATE offices SET logo = :logo WHERE id = :id');
        return $stmt->execute(['logo' => $logoPath, 'id' => $officeId]);
    }

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

    public function getAllWithOpenReportCount(): array
    {
        $stmt = $this->db->query(
            "SELECT o.*, COUNT(r.id) AS open_reports
            FROM offices o
            LEFT JOIN reports r ON r.target_type = 'office' AND r.target_id = o.id AND r.status IN ('open', 'in_progress')
            GROUP BY o.id
            ORDER BY o.created_at DESC"
        );

        return $stmt->fetchAll();
    }

    public function updateOfficeDetails(int $id, array $data): bool
    {
        $allowed = ['pending', 'approved', 'deactivated'];
        if (!in_array($data['status'], $allowed, true)) {
            return false;
        }

        $stmt = $this->db->prepare(
            'UPDATE offices SET office_name = :office_name, address = :address, phone = :phone, description = :description, status = :status WHERE id = :id'
        );

        return $stmt->execute([
            'office_name' => $data['office_name'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'description' => $data['description'],
            'status' => $data['status'],
            'id' => $id,
        ]);
    }

    public function countAll(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM offices');
        return (int) $stmt->fetchColumn();
    }

    public function getApprovedBySpecialty(int $specialtyId, ?string $searchTerm = null): array
    {
        $sql = "SELECT DISTINCT o.*
            FROM offices o
            JOIN doctors d ON d.office_id = o.id
            WHERE o.status = 'approved' AND d.specialty_id = :specialty_id";
        $params = ['specialty_id' => $specialtyId];

        if (!empty($searchTerm)) {
            $sql .= ' AND (o.office_name LIKE :term OR o.address LIKE :term OR o.description LIKE :term)';
            $params['term'] = '%' . $searchTerm . '%';
        }

        $sql .= ' ORDER BY o.office_name ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getApprovedBySearch(?string $searchTerm): array
    {
        $sql = "SELECT o.*
            FROM offices o
            WHERE o.status = 'approved'";
        $params = [];

        if (!empty($searchTerm)) {
            $sql .= ' AND (o.office_name LIKE :term OR o.address LIKE :term OR o.description LIKE :term)';
            $params['term'] = '%' . $searchTerm . '%';
        }

        $sql .= ' ORDER BY o.office_name ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
