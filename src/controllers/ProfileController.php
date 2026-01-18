<?php
require_once __DIR__ . '/../utils/Auth.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/ActivityModel.php';

class ProfileController {
    
    public function index() {
        Auth::requireLogin();
        $user = Auth::user();
        $stats = UserModel::getStats($user['id']);
        $activities = ActivityModel::findByUserId($user['id'], 20);
        
        include __DIR__ . '/../views/profile/index.php';
    }
    
    public function update() {
        Auth::requireLogin();
        
        $data = [
            'full_name' => $_POST['full_name'] ?? null,
            'role' => $_POST['role'] ?? 'Game Dev'
        ];
        
        UserModel::update(Auth::userId(), $data);
        
        $_SESSION['success'] = 'Profile berhasil diupdate';
        header('Location: /profile');
        exit;
    }
}


