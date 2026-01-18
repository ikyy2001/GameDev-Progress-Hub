<?php
/**
 * Progress Calculator
 * Logika perhitungan progress otomatis
 */

require_once __DIR__ . '/Database.php';

class ProgressCalculator {
    
    /**
     * Get task progress percentage
     * 
     * @param array $task
     * @return float
     */
    public static function getTaskProgress($task) {
        switch ($task['status']) {
            case 'Done':
                return 100;
            case 'On Going':
                return 50;
            case 'Not Started':
            default:
                return 0;
        }
    }
    
    /**
     * Calculate milestone progress
     * 
     * @param int $milestone_id
     * @return float Progress percentage (0-100)
     */
    public static function calculateMilestoneProgress($milestone_id) {
        // Ambil semua task di milestone
        $sql = "SELECT id, status FROM tasks WHERE milestone_id = ?";
        $tasks = Database::query($sql, [$milestone_id]);
        
        if (empty($tasks)) {
            Database::execute(
                "UPDATE milestones SET progress = 0 WHERE id = ?",
                [$milestone_id]
            );
            return 0;
        }
        
        $totalProgress = 0;
        $taskCount = count($tasks);
        
        foreach ($tasks as $task) {
            $taskProgress = self::getTaskProgress($task);
            $totalProgress += $taskProgress;
        }
        
        $milestoneProgress = ($totalProgress / $taskCount);
        
        // Update database
        Database::execute(
            "UPDATE milestones SET progress = ? WHERE id = ?",
            [$milestoneProgress, $milestone_id]
        );
        
        return $milestoneProgress;
    }
    
    /**
     * Calculate project progress
     * 
     * @param int $project_id
     * @return float Progress percentage (0-100)
     */
    public static function calculateProjectProgress($project_id) {
        // Ambil semua milestone di project
        $sql = "SELECT id, weight FROM milestones WHERE project_id = ?";
        $milestones = Database::query($sql, [$project_id]);
        
        if (empty($milestones)) {
            Database::execute(
                "UPDATE projects SET progress = 0 WHERE id = ?",
                [$project_id]
            );
            return 0;
        }
        
        $totalWeightedProgress = 0;
        $totalWeight = 0;
        
        foreach ($milestones as $milestone) {
            // Pastikan milestone progress sudah ter-update
            $milestoneProgress = self::calculateMilestoneProgress($milestone['id']);
            
            $weight = $milestone['weight'] ?? 1;
            $totalWeightedProgress += ($milestoneProgress * $weight);
            $totalWeight += $weight;
        }
        
        $projectProgress = ($totalWeight > 0) 
            ? ($totalWeightedProgress / $totalWeight) 
            : 0;
        
        // Update database
        Database::execute(
            "UPDATE projects SET progress = ? WHERE id = ?",
            [$projectProgress, $project_id]
        );
        
        return $projectProgress;
    }
    
    /**
     * Check if project has unresolved bugs
     * 
     * @param int $project_id
     * @return bool
     */
    public static function hasUnresolvedBugs($project_id) {
        $sql = "SELECT COUNT(*) as count 
                FROM tasks t
                INNER JOIN milestones m ON t.milestone_id = m.id
                WHERE m.project_id = ? 
                AND t.is_bug = 1 
                AND t.status != 'Done'";
        
        $result = Database::queryOne($sql, [$project_id]);
        return ($result && $result['count'] > 0);
    }
    
    /**
     * Determine project status based on progress and bugs
     * 
     * @param int $project_id
     * @return string Status (Concept/Prototype/Alpha/Beta/Release/Blocked)
     */
    public static function determineProjectStatus($project_id) {
        // Check unresolved bugs
        if (self::hasUnresolvedBugs($project_id)) {
            Database::execute(
                "UPDATE projects SET status = 'Blocked' WHERE id = ?",
                [$project_id]
            );
            return 'Blocked';
        }
        
        // Get project progress
        $project = Database::queryOne(
            "SELECT progress FROM projects WHERE id = ?",
            [$project_id]
        );
        
        if (!$project) {
            return 'Concept';
        }
        
        $progress = (float)$project['progress'];
        
        // Determine status
        $status = 'Concept';
        if ($progress >= 100) {
            $status = 'Release';
        } elseif ($progress >= 71) {
            $status = 'Beta';
        } elseif ($progress >= 41) {
            $status = 'Alpha';
        } elseif ($progress >= 11) {
            $status = 'Prototype';
        }
        
        // Update database
        Database::execute(
            "UPDATE projects SET status = ? WHERE id = ?",
            [$status, $project_id]
        );
        
        return $status;
    }
    
    /**
     * Recalculate progress from task change
     * 
     * @param int|null $task_id
     * @param int|null $milestone_id
     * @param int|null $project_id
     */
    public static function recalculateProgress($task_id = null, $milestone_id = null, $project_id = null) {
        // Jika task_id diberikan, mulai dari milestone yang memiliki task ini
        if ($task_id) {
            $task = Database::queryOne(
                "SELECT milestone_id FROM tasks WHERE id = ?",
                [$task_id]
            );
            if ($task) {
                $milestone_id = $task['milestone_id'];
            }
        }
        
        // Jika milestone_id diberikan, mulai dari project yang memiliki milestone ini
        if ($milestone_id && !$project_id) {
            $milestone = Database::queryOne(
                "SELECT project_id FROM milestones WHERE id = ?",
                [$milestone_id]
            );
            if ($milestone) {
                $project_id = $milestone['project_id'];
            }
        }
        
        // Recalculate milestone (jika milestone_id ada)
        if ($milestone_id) {
            self::calculateMilestoneProgress($milestone_id);
        }
        
        // Recalculate project (harus ada project_id)
        if ($project_id) {
            self::calculateProjectProgress($project_id);
            self::determineProjectStatus($project_id);
        }
    }
}



