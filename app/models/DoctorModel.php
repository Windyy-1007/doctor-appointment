<?php

class DoctorModel extends Model
{
    public function getByOfficeId(int $officeId): array
    {
        $doctors = $this->table('doctors');
        $specialties = $this->table('specialties');

        $stmt = $this->db->prepare("SELECT d.*, s.name AS specialty_name FROM {$doctors} d LEFT JOIN {$specialties} s ON s.id = d.specialty_id WHERE d.office_id = :office_id ORDER BY d.name ASC");
        $stmt->execute(['office_id' => $officeId]);
        return $stmt->fetchAll();
    }

    public function getByOfficeAndSpecialty(int $officeId, int $specialtyId): array
    {
        $doctors = $this->table('doctors');
        $specialties = $this->table('specialties');

        $stmt = $this->db->prepare("SELECT d.*, s.name AS specialty_name FROM {$doctors} d LEFT JOIN {$specialties} s ON s.id = d.specialty_id WHERE d.office_id = :office_id AND d.specialty_id = :specialty_id ORDER BY d.name ASC");
        $stmt->execute(['office_id' => $officeId, 'specialty_id' => $specialtyId]);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $doctors = $this->table('doctors');

        $stmt = $this->db->prepare("SELECT * FROM {$doctors} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $doctor = $stmt->fetch();
        return $doctor === false ? null : $doctor;
    }

    public function create(int $officeId, array $data): bool
    {
        $doctors = $this->table('doctors');

        $stmt = $this->db->prepare("INSERT INTO {$doctors} (office_id, name, specialty_id, bio) VALUES (:office_id, :name, :specialty_id, :bio)");
        return $stmt->execute([
            'office_id' => $officeId,
            'name' => $data['name'],
            'specialty_id' => $data['specialty_id'],
            'bio' => $data['bio'] ?? null,
        ]);
    }

    public function update(int $id, int $officeId, array $data): bool
    {
        $doctors = $this->table('doctors');

        $stmt = $this->db->prepare("UPDATE {$doctors} SET name = :name, specialty_id = :specialty_id, bio = :bio WHERE id = :id AND office_id = :office_id");
        return $stmt->execute([
            'name' => $data['name'],
            'specialty_id' => $data['specialty_id'],
            'bio' => $data['bio'] ?? null,
            'id' => $id,
            'office_id' => $officeId,
        ]);
    }

    public function delete(int $id, int $officeId): bool
    {
        $doctors = $this->table('doctors');

        $stmt = $this->db->prepare("DELETE FROM {$doctors} WHERE id = :id AND office_id = :office_id");
        return $stmt->execute(['id' => $id, 'office_id' => $officeId]);
    }
}
