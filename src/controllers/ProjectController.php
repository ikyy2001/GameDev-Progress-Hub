<?php
require_once __DIR__ . '/../utils/Auth.php';
require_once __DIR__ . '/../models/ProjectModel.php';
require_once __DIR__ . '/../models/TaskModel.php';
require_once __DIR__ . '/../models/ActivityModel.php';

class ProjectController {
    
    public function index() {
        Auth::requireLogin();
        $user = Auth::user();
        $projects = ProjectModel::findAllByUserId($user['id']);
        
        foreach ($projects as &$project) {
            $project['progress'] = ProjectModel::calculateProgress($project['id']);
        }
        
        include __DIR__ . '/../views/projects/index.php';
    }
    
    public function show() {
        Auth::requireLogin();
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id <= 0) {
            header('Location: /projects');
            exit;
        }
        $project = ProjectModel::findById($id);
        
        if (!$project || $project['user_id'] != Auth::userId()) {
            header('Location: /projects');
            exit;
        }
        
        $tasks = TaskModel::findAllByProjectId($id);
        $activities = ActivityModel::findByProjectId($id, 20);
        $project['progress'] = ProjectModel::calculateProgress($id);
        
        include __DIR__ . '/../views/projects/show.php';
    }
    
    public function createForm() {
        Auth::requireLogin();
        include __DIR__ . '/../views/projects/create.php';
    }
    
    public function create() {
        Auth::requireLogin();
        
        $data = [
            'user_id' => Auth::userId(),
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? null,
            'genre' => $_POST['genre'] ?? null,
            'engine' => $_POST['engine'] ?? null,
            'platform' => $_POST['platform'] ?? null,
            'target_rilis' => $_POST['target_rilis'] ?? null,
            'status' => $_POST['status'] ?? 'Planning'
        ];
        
        require_once __DIR__ . '/../utils/Validator.php';
        
        $data['name'] = Validator::sanitize($data['name']);
        $data['description'] = $data['description'] ? Validator::sanitize($data['description']) : null;
        
        if (!Validator::validateRequired($data['name']) || !Validator::validateLength($data['name'], 1, 100)) {
            $_SESSION['error'] = 'Nama project wajib diisi (max 100 karakter)';
            header('Location: /projects/create');
            exit;
        }
        
        if ($data['target_rilis'] && !Validator::validateDate($data['target_rilis'])) {
            $_SESSION['error'] = 'Format tanggal tidak valid';
            header('Location: /projects/create');
            exit;
        }
        
        $projectId = ProjectModel::create($data);
        
        ActivityModel::log(
            Auth::userId(),
            'project_created',
            "Project '{$data['name']}' dibuat",
            $projectId
        );
        
        header('Location: /projects/' . $projectId);
        exit;
    }
    
    public function editForm() {
        Auth::requireLogin();
        $id = $_GET['id'] ?? 0;
        $project = ProjectModel::findById($id);
        
        if (!$project || $project['user_id'] != Auth::userId()) {
            header('Location: /projects');
            exit;
        }
        
        include __DIR__ . '/../views/projects/edit.php';
    }
    
    public function update() {
        Auth::requireLogin();
        $id = $_POST['id'] ?? 0;
        $project = ProjectModel::findById($id);
        
        if (!$project || $project['user_id'] != Auth::userId()) {
            header('Location: /projects');
            exit;
        }
        
        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? null,
            'genre' => $_POST['genre'] ?? null,
            'engine' => $_POST['engine'] ?? null,
            'platform' => $_POST['platform'] ?? null,
            'target_rilis' => $_POST['target_rilis'] ?? null,
            'status' => $_POST['status'] ?? 'Planning'
        ];
        
        if (empty($data['name'])) {
            $_SESSION['error'] = 'Nama project wajib diisi';
            header('Location: /projects/' . $id . '/edit');
            exit;
        }
        
        ProjectModel::update($id, $data);
        
        ActivityModel::log(
            Auth::userId(),
            'project_updated',
            "Project '{$data['name']}' diupdate",
            $id
        );
        
        header('Location: /projects/' . $id);
        exit;
    }
    
    public function delete() {
        Auth::requireLogin();
        $id = $_POST['id'] ?? 0;
        $project = ProjectModel::findById($id);
        
        if (!$project || $project['user_id'] != Auth::userId()) {
            header('Location: /projects');
            exit;
        }
        
        ProjectModel::delete($id);
        
        ActivityModel::log(
            Auth::userId(),
            'project_deleted',
            "Project '{$project['name']}' dihapus",
            null
        );
        
        header('Location: /projects');
        exit;
    }
}

