<?php
/**
 * Authentication Utility
 * Helper untuk authentication dan authorization
 */

require_once __DIR__ . '/Database.php';

class Auth {
    
    /**
     * Check if user is logged in
     * 
     * @return bool
     */
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    /**
     * Get current user ID
     * 
     * @return int|null
     */
    public static function userId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Get current user data
     * 
     * @return array|false
     */
    public static function user() {
        if (!self::isLoggedIn()) {
            return false;
        }
        
        $user = Database::queryOne(
            "SELECT id, username, email, full_name, role FROM users WHERE id = ?",
            [self::userId()]
        );
        
        // Ensure full_name is set (even if NULL)
        if ($user && !isset($user['full_name'])) {
            $user['full_name'] = null;
        }
        
        return $user;
    }
    
    /**
     * Check if user has permission
     * 
     * @param string $requiredRole
     * @return bool
     */
    public static function hasRole($requiredRole) {
        $user = self::user();
        if (!$user) {
            return false;
        }
        
        $roleHierarchy = [
            'viewer' => 1,
            'collaborator' => 2,
            'owner' => 3
        ];
        
        $userLevel = $roleHierarchy[$user['role']] ?? 0;
        $requiredLevel = $roleHierarchy[$requiredRole] ?? 0;
        
        return $userLevel >= $requiredLevel;
    }
    
    /**
     * Check if user owns project
     * 
     * @param int $project_id
     * @return bool
     */
    public static function ownsProject($project_id) {
        if (!self::isLoggedIn()) {
            return false;
        }
        
        $project = Database::queryOne(
            "SELECT owner_id FROM projects WHERE id = ?",
            [$project_id]
        );
        
        return $project && $project['owner_id'] == self::userId();
    }
    
    /**
     * Check if user can access project
     * 
     * @param int $project_id
     * @return bool
     */
    public static function canAccessProject($project_id) {
        if (!self::isLoggedIn()) {
            return false;
        }
        
        // Check if owner
        if (self::ownsProject($project_id)) {
            return true;
        }
        
        // Check if collaborator
        $access = Database::queryOne(
            "SELECT role FROM project_users WHERE project_id = ? AND user_id = ?",
            [$project_id, self::userId()]
        );
        
        return $access !== false;
    }
    
    /**
     * Login user
     * 
     * @param string $username
     * @param string $password
     * @return array|false User data on success, false on failure
     */
    public static function login($username, $password) {
        $user = Database::queryOne(
            "SELECT * FROM users WHERE username = ? OR email = ?",
            [$username, $username]
        );
        
        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }
        
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        return $user;
    }
    
    /**
     * Logout user
     */
    public static function logout() {
        session_unset();
        session_destroy();
    }
    
    /**
     * Require login (redirect if not logged in)
     */
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: /login');
            exit;
        }
    }
    
    /**
     * Require role (redirect if doesn't have role)
     * 
     * @param string $role
     */
    public static function requireRole($role) {
        self::requireLogin();
        if (!self::hasRole($role)) {
            header('Location: /dashboard');
            exit;
        }
    }
}


