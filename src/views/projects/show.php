<?php
$page_title = $project['name'];
include __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">
    <div>
        <h1><?= htmlspecialchars($project['name']) ?></h1>
        <div class="project-meta-top">
            <span class="status-badge status-<?= strtolower($project['status']) ?>"><?= htmlspecialchars($project['status']) ?></span>
            <?php if ($project['engine']): ?>
                <span class="badge"><?= htmlspecialchars($project['engine']) ?></span>
            <?php endif; ?>
            <?php if ($project['platform']): ?>
                <span class="badge"><?= htmlspecialchars($project['platform']) ?></span>
            <?php endif; ?>
        </div>
    </div>
    <div class="page-actions">
        <a href="/projects/<?= $project['id'] ?>/edit" class="btn btn-secondary">Edit Project</a>
        <a href="/projects" class="btn">← Back</a>
    </div>
</div>

<?php if ($project['description']): ?>
    <div class="info-box">
        <p><?= nl2br(htmlspecialchars($project['description'])) ?></p>
    </div>
<?php endif; ?>

<div class="info-grid">
    <?php if ($project['genre']): ?>
        <div class="info-item">
            <strong>Genre:</strong> <?= htmlspecialchars($project['genre']) ?>
        </div>
    <?php endif; ?>
    <?php if ($project['target_rilis']): ?>
        <div class="info-item">
            <strong>Target Rilis:</strong> <?= date('d M Y', strtotime($project['target_rilis'])) ?>
        </div>
    <?php endif; ?>
    <div class="info-item">
        <strong>Created:</strong> <?= date('d M Y', strtotime($project['created_at'])) ?>
    </div>
</div>

<div class="progress-section-large">
    <h2>Project Progress</h2>
    <div class="progress-bar-large">
        <div class="progress-fill" style="width: <?= $project['progress'] ?>%"></div>
    </div>
    <p class="progress-percentage"><?= round($project['progress'], 1) ?>% Complete</p>
</div>

<div class="section-header">
    <h2>Tasks</h2>
    <button onclick="document.getElementById('taskForm').style.display='block'" class="btn btn-primary">+ New Task</button>
</div>

<div id="taskForm" class="form-box" style="display:none;">
    <h3>Add New Task</h3>
    <form method="POST" action="/tasks/create">
        <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
        <div class="form-group">
            <label>Title *</label>
            <input type="text" name="title" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3"></textarea>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Priority</label>
                <select name="priority">
                    <option value="Low">Low</option>
                    <option value="Medium" selected>Medium</option>
                    <option value="High">High</option>
                </select>
            </div>
            <div class="form-group">
                <label>Deadline</label>
                <input type="date" name="deadline">
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Create Task</button>
            <button type="button" onclick="document.getElementById('taskForm').style.display='none'" class="btn">Cancel</button>
        </div>
    </form>
</div>

<?php if (empty($tasks)): ?>
    <div class="empty-state">
        <p>Belum ada task. Tambahkan task pertama Anda!</p>
    </div>
<?php else: ?>
    <div class="tasks-list">
        <?php foreach ($tasks as $task): ?>
            <div class="task-item <?= $task['is_done'] ? 'task-done' : '' ?>">
                <div class="task-checkbox">
                    <input type="checkbox" <?= $task['is_done'] ? 'checked' : '' ?> 
                           onchange="toggleTask(<?= $task['id'] ?>, this.checked)">
                </div>
                <div class="task-content">
                    <div class="task-header">
                        <h4><?= htmlspecialchars($task['title']) ?></h4>
                        <span class="priority-badge priority-<?= strtolower($task['priority']) ?>"><?= htmlspecialchars($task['priority']) ?></span>
                    </div>
                    <?php if ($task['description']): ?>
                        <p class="task-description"><?= nl2br(htmlspecialchars($task['description'])) ?></p>
                    <?php endif; ?>
                    <div class="task-meta">
                        <?php if ($task['deadline']): ?>
                            <span>📅 <?= date('d M Y', strtotime($task['deadline'])) ?></span>
                        <?php endif; ?>
                        <span>Created: <?= date('d M Y', strtotime($task['created_at'])) ?></span>
                    </div>
                </div>
                <div class="task-actions">
                    <form method="POST" action="/tasks/<?= $task['id'] ?>/delete" style="display:inline;" onsubmit="return confirm('Yakin hapus task ini?');">
                        <input type="hidden" name="id" value="<?= $task['id'] ?>">
                        <button type="submit" class="btn-icon" title="Delete">🗑️</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="section-header">
    <h2>Activity Log</h2>
</div>
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
                    <span class="activity-time"><?= date('d M Y H:i', strtotime($activity['created_at'])) ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>


