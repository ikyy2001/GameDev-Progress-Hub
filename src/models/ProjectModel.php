<?php
require_once __DIR__ . '/../utils/Database.php';

class ProjectModel {
    
    public static function findAllByUserId($userId) {
        return Database::query(
            "SELECT * FROM projects WHERE user_id = ? ORDER BY created_at DESC",
            [$userId]
        );
    }
    
    public static function findById($id) {
        return Database::queryOne(
            "SELECT * FROM projects WHERE id = ?",
            [$id]
        );
    }
    
    public static function create($data) {
        Database::execute(
            "INSERT INTO projects (user_id, name, description, genre, engine, platform, target_rilis, status) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['user_id'],
                $data['name'],
                $data['description'] ?? null,
                $data['genre'] ?? null,
                $data['engine'] ?? null,
                $data['platform'] ?? null,
                $data['target_rilis'] ?? null,
                $data['status'] ?? 'Planning'
            ]
        );
        return Database::lastInsertId();
    }
    
    public static function update($id, $data) {
        return Database::execute(
            "UPDATE projects SET name = ?, description = ?, genre = ?, engine = ?, platform = ?, 
             target_rilis = ?, status = ?, updated_at = NOW() WHERE id = ?",
            [
                $data['name'],
                $data['description'] ?? null,
                $data['genre'] ?? null,
                $data['engine'] ?? null,
                $data['platform'] ?? null,
                $data['target_rilis'] ?? null,
                $data['status'] ?? 'Planning',
                $id
            ]
        );
    }
    
    public static function delete($id) {
        return Database::execute("DELETE FROM projects WHERE id = ?", [$id]);
    }
    
    public static function updateProgress($id, $progress) {
        return Database::execute(
            "UPDATE projects SET progress = ? WHERE id = ?",
            [$progress, $id]
        );
    }
    
    public static function calculateProgress($projectId) {
        $stats = Database::queryOne(
            "SELECT 
                COUNT(*) as total_tasks,
                SUM(CASE WHEN is_done = 1 THEN 1 ELSE 0 END) as done_tasks
            FROM tasks WHERE project_id = ?",
            [$projectId]
        );
        
        if ($stats && $stats['total_tasks'] > 0) {
            $progress = ($stats['done_tasks'] / $stats['total_tasks']) * 100;
            self::updateProgress($projectId, $progress);
            return $progress;
        }
        
        self::updateProgress($projectId, 0);
        return 0;
    }
}


