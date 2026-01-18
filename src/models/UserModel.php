<?php
require_once __DIR__ . '/../utils/Database.php';

class UserModel {
    
    public static function findById($id) {
        $user = Database::queryOne(
            "SELECT id, username, email, full_name, role, created_at FROM users WHERE id = ?",
            [$id]
        );
        if ($user && $user['full_name'] === null) {
            $user['full_name'] = '';
        }
        return $user;
    }
    
    public static function findByUsername($username) {
        return Database::queryOne(
            "SELECT * FROM users WHERE username = ? OR email = ?",
            [$username, $username]
        );
    }
    
    public static function create($data) {
        Database::execute(
            "INSERT INTO users (username, email, password, full_name, role) VALUES (?, ?, ?, ?, ?)",
            [$data['username'], $data['email'], $data['password'], $data['full_name'] ?? null, $data['role'] ?? 'Game Dev']
        );
        return Database::lastInsertId();
    }
    
    public static function update($id, $data) {
        $sql = "UPDATE users SET full_name = ?, role = ?, updated_at = NOW() WHERE id = ?";
        return Database::execute($sql, [$data['full_name'] ?? null, $data['role'] ?? 'Game Dev', $id]);
    }
    
    public static function getStats($userId) {
        $stats = Database::queryOne(
            "SELECT 
                COUNT(DISTINCT p.id) as total_projects,
                COUNT(DISTINCT t.id) as total_tasks_done
            FROM users u
            LEFT JOIN projects p ON p.user_id = u.id
            LEFT JOIN tasks t ON t.project_id = p.id AND t.is_done = 1
            WHERE u.id = ?",
            [$userId]
        );
        return $stats ?: ['total_projects' => 0, 'total_tasks_done' => 0];
    }
}

