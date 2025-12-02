<?php

class AppointmentModel extends Model
{
    public function getAppointmentsCountPerWeek(int $weeks = 4): array
    {
        $limit = max(1, (int) $weeks);
        $stmt = $this->db->prepare(
            "SELECT
                DATE_FORMAT(DATE_SUB(appointment_datetime, INTERVAL WEEKDAY(appointment_datetime) DAY), '%Y-%m-%d') AS week_start,
                DATE_FORMAT(DATE_ADD(DATE_SUB(appointment_datetime, INTERVAL WEEKDAY(appointment_datetime) DAY), INTERVAL 6 DAY), '%Y-%m-%d') AS week_end,
                COUNT(*) AS total_appointments
            FROM appointments
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
        $stmt = $this->db->query('SELECT COUNT(*) FROM appointments');
        return (int) $stmt->fetchColumn();
    }
}
