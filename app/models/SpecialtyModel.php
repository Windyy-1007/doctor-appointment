<?php

class SpecialtyModel extends Model
{
    public function getAll(): array
    {
        $stmt = $this->db->query('SELECT id, name, description FROM specialties ORDER BY name ASC');
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, name, description FROM specialties WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $specialty = $stmt->fetch();

        return $specialty === false ? null : $specialty;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO specialties (name, description) VALUES (:name, :description)');
        return $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE specialties SET name = :name, description = :description WHERE id = :id');
        return $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        // Deleting a specialty may fail if doctors reference it; the database should enforce restrictions
        $stmt = $this->db->prepare('DELETE FROM specialties WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
