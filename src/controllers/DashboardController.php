<?php
require_once __DIR__ . '/../utils/Auth.php';
require_once __DIR__ . '/../models/ProjectModel.php';
require_once __DIR__ . '/../models/TaskModel.php';
require_once __DIR__ . '/../models/UserModel.php';

class DashboardController {
    
    public function index() {
        Auth::requireLogin();
        $user = Auth::user();
        
        $projects = ProjectModel::findAllByUserId($user['id']);
        $stats = UserModel::getStats($user['id']);
        
        $totalTasks = 0;
        $doneTasks = 0;
        
        foreach ($projects as &$project) {
            $project['progress'] = ProjectModel::calculateProgress($project['id']);
            $tasks = TaskModel::findAllByProjectId($project['id']);
            $project['total_tasks'] = count($tasks);
            $project['done_tasks'] = count(array_filter($tasks, fn($t) => $t['is_done']));
            $totalTasks += $project['total_tasks'];
            $doneTasks += $project['done_tasks'];
        }
        
        $stats['total_tasks'] = $totalTasks;
        $stats['done_tasks'] = $doneTasks;
        
        include __DIR__ . '/../views/dashboard/index.php';
    }
}
