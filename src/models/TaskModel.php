<?php
require_once __DIR__ . '/../utils/Database.php';

class TaskModel {
    
    public static function findAllByProjectId($projectId) {
        return Database::query(
            "SELECT * FROM tasks WHERE project_id = ? ORDER BY priority DESC, created_at DESC",
            [$projectId]
        );
    }
    
    public static function findById($id) {
        return Database::queryOne(
            "SELECT * FROM tasks WHERE id = ?",
            [$id]
        );
    }
    
    public static function create($data) {
        Database::execute(
            "INSERT INTO tasks (project_id, title, description, priority, deadline, is_done) 
             VALUES (?, ?, ?, ?, ?, ?)",
            [
                $data['project_id'],
                $data['title'],
                $data['description'] ?? null,
                $data['priority'] ?? 'Medium',
                $data['deadline'] ?? null,
                $data['is_done'] ?? 0
            ]
        );
        return Database::lastInsertId();
    }
    
    public static function update($id, $data) {
        return Database::execute(
            "UPDATE tasks SET title = ?, description = ?, priority = ?, deadline = ?, is_done = ?, updated_at = NOW() WHERE id = ?",
            [
                $data['title'],
                $data['description'] ?? null,
                $data['priority'] ?? 'Medium',
                $data['deadline'] ?? null,
                $data['is_done'] ?? 0,
                $id
            ]
        );
    }
    
    public static function toggleDone($id) {
        $task = self::findById($id);
        if ($task) {
            $newStatus = $task['is_done'] ? 0 : 1;
            $result = Database::execute("UPDATE tasks SET is_done = ?, updated_at = NOW() WHERE id = ?", [$newStatus, $id]);
            if ($result !== false) {
                return $newStatus;
            }
        }
        return null;
    }
    
    public static function delete($id) {
        return Database::execute("DELETE FROM tasks WHERE id = ?", [$id]);
    }
}


