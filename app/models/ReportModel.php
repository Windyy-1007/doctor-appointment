<?php

class ReportModel extends Model
{
    protected array $openStatuses = ['open', 'in_progress'];
    protected array $resolvedStatuses = ['resolved', 'closed'];

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO reports (reporter_id, target_type, target_id, category, title, description, status, created_at) 
            VALUES (:reporter_id, :target_type, :target_id, :category, :title, :description, :status, :created_at)'
        );

        return $stmt->execute([
            'reporter_id' => $data['reporter_id'] ?? null,
            'target_type' => $data['target_type'],
            'target_id' => $data['target_id'] ?? null,
            'category' => $data['category'],
            'title' => $data['title'],
            'description' => $data['description'],
            'status' => $data['status'] ?? 'open',
            'created_at' => $data['created_at'] ?? date('Y-m-d H:i:s'),
        ]);
    }

    public function getOpenReports(): array
    {
        $placeholders = implode(',', array_fill(0, count($this->openStatuses), '?'));
        $stmt = $this->db->prepare("SELECT * FROM reports WHERE status IN ({$placeholders}) ORDER BY created_at DESC");
        $stmt->execute($this->openStatuses);

        return $stmt->fetchAll();
    }

    public function getResolvedReports(): array
    {
        $placeholders = implode(',', array_fill(0, count($this->resolvedStatuses), '?'));
        $stmt = $this->db->prepare("SELECT * FROM reports WHERE status IN ({$placeholders}) ORDER BY resolved_at DESC");
        $stmt->execute($this->resolvedStatuses);

        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM reports WHERE id = :id');
        $stmt->execute(['id' => $id]);

        $report = $stmt->fetch();
        return $report === false ? null : $report;
    }

    public function updateStatus(int $id, string $status, ?int $resolvedBy = null): bool
    {
        if (!in_array($status, array_merge($this->openStatuses, $this->resolvedStatuses), true)) {
            return false;
        }

        if (in_array($status, $this->resolvedStatuses, true)) {
            $stmt = $this->db->prepare(
                'UPDATE reports SET status = :status, resolved_by = :resolved_by, resolved_at = :resolved_at WHERE id = :id'
            );

            return $stmt->execute([
                'status' => $status,
                'resolved_by' => $resolvedBy,
                'resolved_at' => date('Y-m-d H:i:s'),
                'id' => $id,
            ]);
        }

        $stmt = $this->db->prepare('UPDATE reports SET status = :status, resolved_by = NULL, resolved_at = NULL WHERE id = :id');
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }
}
