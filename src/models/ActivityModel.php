<?php
require_once __DIR__ . '/../utils/Database.php';

class ActivityModel {
    
    public static function log($userId, $activityType, $description, $projectId = null, $taskId = null) {
        return Database::execute(
            "INSERT INTO activity_logs (user_id, project_id, task_id, activity_type, description) 
             VALUES (?, ?, ?, ?, ?)",
            [$userId, $projectId, $taskId, $activityType, $description]
        );
    }
    
    public static function findByUserId($userId, $limit = 50) {
        return Database::query(
            "SELECT al.*, p.name as project_name, t.title as task_title 
             FROM activity_logs al
             LEFT JOIN projects p ON p.id = al.project_id
             LEFT JOIN tasks t ON t.id = al.task_id
             WHERE al.user_id = ? 
             ORDER BY al.created_at DESC 
             LIMIT ?",
            [$userId, $limit]
        );
    }
    
    public static function findByProjectId($projectId, $limit = 50) {
        return Database::query(
            "SELECT al.*, u.username, t.title as task_title 
             FROM activity_logs al
             LEFT JOIN users u ON u.id = al.user_id
             LEFT JOIN tasks t ON t.id = al.task_id
             WHERE al.project_id = ? 
             ORDER BY al.created_at DESC 
             LIMIT ?",
            [$projectId, $limit]
        );
    }
}


