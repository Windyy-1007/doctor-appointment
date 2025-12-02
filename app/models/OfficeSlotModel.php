<?php

class OfficeSlotModel extends Model
{
    private const STATUS_AVAILABLE = 'available';
    private const STATUS_BLOCKED = 'blocked';

    public function getSlotsForOfficeInRange(int $officeId, string $start, string $end): array
    {
        $slots = $this->table('office_slots');

        $stmt = $this->db->prepare("SELECT * FROM {$slots} WHERE office_id = :office_id AND slot_datetime BETWEEN :start AND :end ORDER BY slot_datetime ASC");
        $stmt->execute([
            'office_id' => $officeId,
            'start' => $start,
            'end' => $end,
        ]);

        return $stmt->fetchAll();
    }

    public function findByDoctorAndDatetime(int $officeId, int $doctorId, string $datetime): ?array
    {
        $slots = $this->table('office_slots');

        $stmt = $this->db->prepare("SELECT * FROM {$slots} WHERE office_id = :office_id AND doctor_id = :doctor_id AND slot_datetime = :slot_datetime LIMIT 1");
        $stmt->execute([
            'office_id' => $officeId,
            'doctor_id' => $doctorId,
            'slot_datetime' => $datetime,
        ]);

        $slot = $stmt->fetch();
        return $slot === false ? null : $slot;
    }

    public function upsertSlot(int $officeId, int $doctorId, string $datetime, string $status, ?string $note = null): bool
    {
        $existing = $this->findByDoctorAndDatetime($officeId, $doctorId, $datetime);
        if ($existing !== null) {
            return $this->updateStatus($officeId, $existing['id'], $status, $note);
        }

        return $this->createSlot($officeId, $doctorId, $datetime, $status, $note);
    }

    public function createSlot(int $officeId, int $doctorId, string $datetime, string $status, ?string $note = null): bool
    {
        $slots = $this->table('office_slots');

        $stmt = $this->db->prepare(
            "INSERT INTO {$slots} (office_id, doctor_id, slot_datetime, status, note, created_at)
            VALUES (:office_id, :doctor_id, :slot_datetime, :status, :note, :created_at)"
        );

        return $stmt->execute([
            'office_id' => $officeId,
            'doctor_id' => $doctorId,
            'slot_datetime' => $datetime,
            'status' => $status,
            'note' => $note,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function updateStatus(int $officeId, int $slotId, string $status, ?string $note = null): bool
    {
        $slots = $this->table('office_slots');

        $stmt = $this->db->prepare("UPDATE {$slots} SET status = :status, note = :note WHERE id = :id AND office_id = :office_id");
        return $stmt->execute([
            'status' => $status,
            'note' => $note,
            'id' => $slotId,
            'office_id' => $officeId,
        ]);
    }

    public function deleteSlot(int $officeId, int $slotId): bool
    {
        $slots = $this->table('office_slots');

        $stmt = $this->db->prepare("DELETE FROM {$slots} WHERE id = :id AND office_id = :office_id");
        return $stmt->execute([
            'id' => $slotId,
            'office_id' => $officeId,
        ]);
    }
}
