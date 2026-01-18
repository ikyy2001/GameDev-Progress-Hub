<?php
$page_title = 'Dashboard';
include __DIR__ . '/../layouts/header.php';
?>

<div class="dashboard-header">
    <h1>Dashboard</h1>
    <p>Selamat datang, <strong><?= htmlspecialchars($user['full_name'] ?? $user['username']) ?></strong>!</p>
</div>

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
            <div class="stat-value"><?= $stats['done_tasks'] ?></div>
            <div class="stat-label">Tasks Selesai</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📝</div>
        <div class="stat-content">
            <div class="stat-value"><?= $stats['total_tasks'] ?></div>
            <div class="stat-label">Total Tasks</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🎯</div>
        <div class="stat-content">
            <div class="stat-value"><?= $stats['total_tasks'] > 0 ? round(($stats['done_tasks'] / $stats['total_tasks']) * 100) : 0 ?>%</div>
            <div class="stat-label">Progress Overall</div>
        </div>
    </div>
</div>

<div class="section-header">
    <h2>My Projects</h2>
    <a href="/projects/create" class="btn btn-primary">+ New Project</a>
</div>

<?php if (empty($projects)): ?>
    <div class="empty-state">
        <p>Belum ada project. <a href="/projects/create">Buat project pertama Anda!</a></p>
    </div>
<?php else: ?>
    <div class="projects-grid">
        <?php foreach ($projects as $project): ?>
            <div class="project-card">
                <div class="project-header">
                    <h3><a href="/projects/<?= $project['id'] ?>"><?= htmlspecialchars($project['name']) ?></a></h3>
                    <span class="status-badge status-<?= strtolower($project['status']) ?>"><?= htmlspecialchars($project['status']) ?></span>
                </div>
                <div class="project-meta">
                    <?php if ($project['engine']): ?>
                        <span class="badge"><?= htmlspecialchars($project['engine']) ?></span>
                    <?php endif; ?>
                    <?php if ($project['platform']): ?>
                        <span class="badge"><?= htmlspecialchars($project['platform']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="progress-section">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?= $project['progress'] ?>%"></div>
                    </div>
                    <span class="progress-text"><?= round($project['progress'], 1) ?>%</span>
                </div>
                <div class="project-footer">
                    <span><?= $project['done_tasks'] ?>/<?= $project['total_tasks'] ?> tasks</span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

