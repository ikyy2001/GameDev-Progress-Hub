<?php
$page_title = 'Projects';
include __DIR__ . '/../layouts/header.php';
?>

<div class="section-header">
    <h1>My Projects</h1>
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
                <?php if ($project['description']): ?>
                    <p class="project-description"><?= htmlspecialchars(substr($project['description'], 0, 100)) ?><?= strlen($project['description']) > 100 ? '...' : '' ?></p>
                <?php endif; ?>
                <div class="project-meta">
                    <?php if ($project['genre']): ?>
                        <span class="badge"><?= htmlspecialchars($project['genre']) ?></span>
                    <?php endif; ?>
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
                <div class="project-actions">
                    <a href="/projects/<?= $project['id'] ?>" class="btn btn-sm">View</a>
                    <a href="/projects/<?= $project['id'] ?>/edit" class="btn btn-sm btn-secondary">Edit</a>
                    <form method="POST" action="/projects/<?= $project['id'] ?>/delete" style="display:inline;" onsubmit="return confirm('Yakin hapus project ini?');">
                        <input type="hidden" name="id" value="<?= $project['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>


