<?php

class StaffActionModel extends Model
{
    public function logAction(int $staffId, string $actionType, string $targetType, ?int $targetId, ?string $description = null): bool
    {
        $actions = $this->table('staff_actions');

        $stmt = $this->db->prepare("INSERT INTO {$actions} (staff_id, action_type, target_type, target_id, description) VALUES (:staff_id, :action_type, :target_type, :target_id, :description)");
        return $stmt->execute([
            'staff_id' => $staffId,
            'action_type' => $actionType,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'description' => $description,
        ]);
    }
}
