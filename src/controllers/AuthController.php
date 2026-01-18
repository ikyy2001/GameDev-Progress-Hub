<?php
/**
 * Authentication Controller
 * Minimal controller untuk testing
 */

require_once __DIR__ . '/../utils/Auth.php';
require_once __DIR__ . '/../utils/Database.php';

class AuthController {
    
    public function loginForm() {
        if (Auth::isLoggedIn()) {
            header('Location: /dashboard');
            exit;
        }
        include __DIR__ . '/../views/auth/login.php';
    }
    
    public function login() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($username) || empty($password)) {
            $_SESSION['error'] = 'Username dan password harus diisi';
            header('Location: /login');
            exit;
        }
        
        $user = Auth::login($username, $password);
        
        if ($user) {
            header('Location: /dashboard');
            exit;
        } else {
            $_SESSION['error'] = 'Username atau password salah';
            header('Location: /login');
            exit;
        }
    }
    
    public function registerForm() {
        if (Auth::isLoggedIn()) {
            header('Location: /dashboard');
            exit;
        }
        include __DIR__ . '/../views/auth/register.php';
    }
    
    public function register() {
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($username) || empty($email) || empty($password)) {
            $_SESSION['error'] = 'Semua field harus diisi';
            header('Location: /register');
            exit;
        }
        
        require_once __DIR__ . '/../utils/Validator.php';
        
        // Validate input
        $username = Validator::sanitize($username);
        $email = Validator::sanitize($email);
        
        if (!Validator::validateLength($username, 3, 50)) {
            $_SESSION['error'] = 'Username harus 3-50 karakter';
            header('Location: /register');
            exit;
        }
        
        if (!Validator::validateEmail($email)) {
            $_SESSION['error'] = 'Email tidak valid';
            header('Location: /register');
            exit;
        }
        
        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Password minimal 6 karakter';
            header('Location: /register');
            exit;
        }
        
        // Check if username/email already exists
        $existing = Database::queryOne(
            "SELECT id FROM users WHERE username = ? OR email = ?",
            [$username, $email]
        );
        
        if ($existing) {
            $_SESSION['error'] = 'Username atau email sudah digunakan';
            header('Location: /register');
            exit;
        }
        
        // Insert new user
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $result = Database::execute(
            "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'Game Dev')",
            [$username, $email, $hashedPassword]
        );
        
        if ($result) {
            $_SESSION['success'] = 'Registrasi berhasil! Silakan login.';
            header('Location: /login');
            exit;
        } else {
            $_SESSION['error'] = 'Registrasi gagal. Silakan coba lagi.';
            header('Location: /register');
            exit;
        }
    }
    
    public function logout() {
        Auth::logout();
        header('Location: /login');
        exit;
    }
}


