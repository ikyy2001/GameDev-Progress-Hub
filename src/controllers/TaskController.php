<?php
require_once __DIR__ . '/../utils/Auth.php';
require_once __DIR__ . '/../models/TaskModel.php';
require_once __DIR__ . '/../models/ProjectModel.php';
require_once __DIR__ . '/../models/ActivityModel.php';

class TaskController {
    
    public function create() {
        Auth::requireLogin();
        
        $projectId = $_POST['project_id'] ?? 0;
        $project = ProjectModel::findById($projectId);
        
        if (!$project || $project['user_id'] != Auth::userId()) {
            header('Location: /projects');
            exit;
        }
        
        $data = [
            'project_id' => $projectId,
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? null,
            'priority' => $_POST['priority'] ?? 'Medium',
            'deadline' => $_POST['deadline'] ?? null,
            'is_done' => 0
        ];
        
        require_once __DIR__ . '/../utils/Validator.php';
        
        $data['title'] = Validator::sanitize($data['title']);
        $data['description'] = $data['description'] ? Validator::sanitize($data['description']) : null;
        
        if (!Validator::validateRequired($data['title']) || !Validator::validateLength($data['title'], 1, 200)) {
            $_SESSION['error'] = 'Judul task wajib diisi (max 200 karakter)';
            header('Location: /projects/' . $projectId);
            exit;
        }
        
        if ($data['deadline'] && !Validator::validateDate($data['deadline'])) {
            $_SESSION['error'] = 'Format tanggal deadline tidak valid';
            header('Location: /projects/' . $projectId);
            exit;
        }
        
        $taskId = TaskModel::create($data);
        ProjectModel::calculateProgress($projectId);
        
        ActivityModel::log(
            Auth::userId(),
            'task_created',
            "Task '{$data['title']}' dibuat",
            $projectId,
            $taskId
        );
        
        header('Location: /projects/' . $projectId);
        exit;
    }
    
    public function updateStatus() {
        Auth::requireLogin();
        
        // Get task_id from POST or GET (route parameter)
        $taskId = $_POST['task_id'] ?? $_GET['id'] ?? 0;
        $taskId = intval($taskId);
        
        if ($taskId <= 0) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Invalid task ID']);
            exit;
        }
        
        $task = TaskModel::findById($taskId);
        
        if (!$task) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Task not found']);
            exit;
        }
        
        $project = ProjectModel::findById($task['project_id']);
        if (!$project || $project['user_id'] != Auth::userId()) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Access denied']);
            exit;
        }
        
        $isDone = TaskModel::toggleDone($taskId);
        $progress = ProjectModel::calculateProgress($task['project_id']);
        
        $activityType = $isDone ? 'task_completed' : 'task_updated';
        $description = $isDone ? "Task '{$task['title']}' diselesaikan" : "Task '{$task['title']}' diupdate";
        
        ActivityModel::log(
            Auth::userId(),
            $activityType,
            $description,
            $task['project_id'],
            $taskId
        );
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'is_done' => (bool)$isDone,
            'progress' => round($progress, 2)
        ]);
        exit;
    }
    
    public function update() {
        Auth::requireLogin();
        
        $taskId = $_POST['id'] ?? 0;
        $task = TaskModel::findById($taskId);
        
        if (!$task) {
            header('Location: /projects');
            exit;
        }
        
        $project = ProjectModel::findById($task['project_id']);
        if (!$project || $project['user_id'] != Auth::userId()) {
            header('Location: /projects');
            exit;
        }
        
        $data = [
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? null,
            'priority' => $_POST['priority'] ?? 'Medium',
            'deadline' => $_POST['deadline'] ?? null,
            'is_done' => isset($_POST['is_done']) ? 1 : 0
        ];
        
        if (empty($data['title'])) {
            $_SESSION['error'] = 'Judul task wajib diisi';
            header('Location: /projects/' . $task['project_id']);
            exit;
        }
        
        TaskModel::update($taskId, $data);
        ProjectModel::calculateProgress($task['project_id']);
        
        ActivityModel::log(
            Auth::userId(),
            'task_updated',
            "Task '{$data['title']}' diupdate",
            $task['project_id'],
            $taskId
        );
        
        header('Location: /projects/' . $task['project_id']);
        exit;
    }
    
    public function delete() {
        Auth::requireLogin();
        
        $taskId = $_POST['id'] ?? 0;
        $task = TaskModel::findById($taskId);
        
        if (!$task) {
            header('Location: /projects');
            exit;
        }
        
        $project = ProjectModel::findById($task['project_id']);
        if (!$project || $project['user_id'] != Auth::userId()) {
            header('Location: /projects');
            exit;
        }
        
        $projectId = $task['project_id'];
        TaskModel::delete($taskId);
        ProjectModel::calculateProgress($projectId);
        
        ActivityModel::log(
            Auth::userId(),
            'task_deleted',
            "Task '{$task['title']}' dihapus",
            $projectId,
            null
        );
        
        header('Location: /projects/' . $projectId);
        exit;
    }
}

