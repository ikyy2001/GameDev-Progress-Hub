<?php
$page_title = 'Profile';
include __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">
    <h1>My Profile</h1>
</div>

<div class="profile-section">
    <h2>Profile Information</h2>
    <form method="POST" action="/profile/update" class="form-box">
        <div class="form-group">
            <label>Username</label>
            <input type="text" value="<?= htmlspecialchars($user['username']) ?>" disabled>
            <small>Username cannot be changed</small>
        </div>
        
        <div class="form-group">
            <label>Email</label>
            <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
            <small>Email cannot be changed</small>
        </div>
        
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" placeholder="Your full name">
        </div>
        
        <div class="form-group">
            <label>Role</label>
            <select name="role">
                <option value="Game Dev" <?= $user['role'] == 'Game Dev' ? 'selected' : '' ?>>Game Dev</option>
                <option value="Student" <?= $user['role'] == 'Student' ? 'selected' : '' ?>>Student</option>
            </select>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </div>
    </form>
</div>

<div class="stats-section">
    <h2>Statistics</h2>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📁</div>
            <div class="stat-content">
                <div class="stat-value"><?= $stats['total_projects'] ?></div>
                <div class="stat-label">Total Projects</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-content">
                <div class="stat-value"><?= $stats['total_tasks_done'] ?></div>
                <div class="stat-label">Tasks Completed</div>
            </div>
        </div>
    </div>
</div>

<div class="activity-section">
    <h2>Recent Activities</h2>
    <div class="activity-timeline">
        <?php if (empty($activities)): ?>
            <p class="empty-state">Belum ada aktivitas</p>
        <?php else: ?>
            <?php foreach ($activities as $activity): ?>
                <div class="activity-item">
                    <div class="activity-icon">
                        <?php
                        $icons = [
                            'project_created' => '📁',
                            'project_updated' => '✏️',
                            'project_deleted' => '🗑️',
                            'task_created' => '➕',
                            'task_completed' => '✅',
                            'task_updated' => '✏️',
                            'task_deleted' => '🗑️'
                        ];
                        echo $icons[$activity['activity_type']] ?? '📝';
                        ?>
                    </div>
                    <div class="activity-content">
                        <p><?= htmlspecialchars($activity['description']) ?></p>
                        <?php if ($activity['project_name']): ?>
                            <small>Project: <?= htmlspecialchars($activity['project_name']) ?></small>
                        <?php endif; ?>
                        <span class="activity-time"><?= date('d M Y H:i', strtotime($activity['created_at'])) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>


