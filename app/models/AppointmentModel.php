<?php

class AppointmentModel extends Model
{
    public function getAppointmentsCountPerWeek(int $weeks = 4): array
    {
        $limit = max(1, (int) $weeks);
        $appointments = $this->table('appointments');

        $stmt = $this->db->prepare(
            "SELECT
                DATE_FORMAT(DATE_SUB(appointment_datetime, INTERVAL WEEKDAY(appointment_datetime) DAY), '%Y-%m-%d') AS week_start,
                DATE_FORMAT(DATE_ADD(DATE_SUB(appointment_datetime, INTERVAL WEEKDAY(appointment_datetime) DAY), INTERVAL 6 DAY), '%Y-%m-%d') AS week_end,
                COUNT(*) AS total_appointments
            FROM {$appointments}
            WHERE appointment_datetime >= DATE_SUB(CURDATE(), INTERVAL :weeks WEEK)
            GROUP BY week_start
            ORDER BY week_start DESC
            LIMIT {$limit}"
        );
        $stmt->execute(['weeks' => $limit]);

        return $stmt->fetchAll();
    }

    public function countAll(): int
    {
        $appointments = $this->table('appointments');

        $stmt = $this->db->query("SELECT COUNT(*) FROM {$appointments}");
        return (int) $stmt->fetchColumn();
    }

    public function findById(int $id): ?array
    {
        $appointments = $this->table('appointments');

        $stmt = $this->db->prepare("SELECT * FROM {$appointments} WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $appointment = $stmt->fetch();
        return $appointment === false ? null : $appointment;
    }

    public function getAppointmentsForOffice(int $officeId, ?string $status = null): array
    {
        $appointments = $this->table('appointments');
        $doctors = $this->table('doctors');
        $users = $this->table('users');

        $sql = "SELECT a.*, d.name AS doctor_name, u.name AS patient_name, u.email AS patient_email FROM {$appointments} a JOIN {$doctors} d ON d.id = a.doctor_id JOIN {$users} u ON u.id = a.patient_id WHERE a.office_id = :office_id";
        $params = ['office_id' => $officeId];

        if ($status !== null) {
            $sql .= ' AND a.status = :status';
            $params['status'] = $status;
        }

        $sql .= ' ORDER BY a.appointment_datetime DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getAppointmentsForOfficeInRange(int $officeId, string $startDate, string $endDate): array
    {
        $appointments = $this->table('appointments');
        $users = $this->table('users');

        $stmt = $this->db->prepare(
            "SELECT a.id, a.doctor_id, a.appointment_datetime, a.status, a.patient_id, u.name AS patient_name, u.email AS patient_email FROM {$appointments} a
            JOIN {$users} u ON u.id = a.patient_id
            WHERE a.office_id = :office_id AND a.appointment_datetime BETWEEN :start AND :end
            ORDER BY a.appointment_datetime ASC"
        );
        $stmt->execute(['office_id' => $officeId, 'start' => $startDate, 'end' => $endDate]);
        return $stmt->fetchAll();
    }

    public function updateStatus(int $appointmentId, int $officeId, string $status): bool
    {
        $appointments = $this->table('appointments');

        $stmt = $this->db->prepare("UPDATE {$appointments} SET status = :status WHERE id = :id AND office_id = :office_id");
        return $stmt->execute(['status' => $status, 'id' => $appointmentId, 'office_id' => $officeId]);
    }

    public function getBookedSlotsForDoctorInRange(int $doctorId, int $officeId, string $startDateTime, string $endDateTime): array
    {
        $appointments = $this->table('appointments');
        $officeSlots = $this->table('office_slots');

        $stmt = $this->db->prepare(
            "SELECT appointment_datetime AS datetime FROM {$appointments}
            WHERE doctor_id = :doctor_id AND appointment_datetime BETWEEN :start AND :end AND status != :cancelled
            UNION
            SELECT slot_datetime AS datetime FROM {$officeSlots}
            WHERE doctor_id = :doctor_id AND office_id = :office_id AND slot_datetime BETWEEN :start AND :end AND status = 'blocked'"
        );
        $stmt->execute([
            'doctor_id' => $doctorId,
            'start' => $startDateTime,
            'end' => $endDateTime,
            'office_id' => $officeId,
            'cancelled' => 'cancelled',
        ]);

        return array_column($stmt->fetchAll(), 'datetime');
    }

    public function createAppointment(int $patientId, int $doctorId, int $officeId, string $datetime): int
    {
        $appointments = $this->table('appointments');

        $stmt = $this->db->prepare(
            "INSERT INTO {$appointments} (patient_id, doctor_id, office_id, appointment_datetime, status, created_at)
            VALUES (:patient_id, :doctor_id, :office_id, :appointment_datetime, :status, :created_at)"
        );

        $status = 'confirmed'; // appointments are confirmed immediately; alternatively use 'pending' if review is needed
        $stmt->execute([
            'patient_id' => $patientId,
            'doctor_id' => $doctorId,
            'office_id' => $officeId,
            'appointment_datetime' => $datetime,
            'status' => $status,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function getAppointmentsForPatient(int $patientId): array
    {
        $appointments = $this->table('appointments');
        $doctors = $this->table('doctors');
        $offices = $this->table('offices');

        $stmt = $this->db->prepare(
            "SELECT a.*, d.name AS doctor_name, o.office_name, o.address
            FROM {$appointments} a
            JOIN {$doctors} d ON d.id = a.doctor_id
            JOIN {$offices} o ON o.id = a.office_id
            WHERE a.patient_id = :patient_id
            ORDER BY a.appointment_datetime DESC"
        );
        $stmt->execute(['patient_id' => $patientId]);
        return $stmt->fetchAll();
    }

    public function findForPatient(int $appointmentId, int $patientId): ?array
    {
        $appointments = $this->table('appointments');

        $stmt = $this->db->prepare("SELECT * FROM {$appointments} WHERE id = :id AND patient_id = :patient_id");
        $stmt->execute(['id' => $appointmentId, 'patient_id' => $patientId]);
        $appointment = $stmt->fetch();
        return $appointment === false ? null : $appointment;
    }

    public function updateStatusForPatient(int $appointmentId, int $patientId, string $status): bool
    {
        $appointments = $this->table('appointments');

        $stmt = $this->db->prepare("UPDATE {$appointments} SET status = :status WHERE id = :id AND patient_id = :patient_id");
        return $stmt->execute(['status' => $status, 'id' => $appointmentId, 'patient_id' => $patientId]);
    }

    public function updateDatetimeForPatient(int $appointmentId, int $patientId, string $newDatetime): bool
    {
        $appointments = $this->table('appointments');

        $stmt = $this->db->prepare("UPDATE {$appointments} SET appointment_datetime = :datetime WHERE id = :id AND patient_id = :patient_id");
        return $stmt->execute(['datetime' => $newDatetime, 'id' => $appointmentId, 'patient_id' => $patientId]);
    }
}
