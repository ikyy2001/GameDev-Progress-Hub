<?php
$page_title = 'Edit Project';
include __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">
    <h1>Edit Project</h1>
    <a href="/projects/<?= $project['id'] ?>" class="btn">← Back</a>
</div>

<form method="POST" action="/projects/<?= $project['id'] ?>/edit" class="form-box">
    <input type="hidden" name="id" value="<?= $project['id'] ?>">
    
    <div class="form-group">
        <label>Project Name *</label>
        <input type="text" name="name" value="<?= htmlspecialchars($project['name']) ?>" required>
    </div>
    
    <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="4"><?= htmlspecialchars($project['description'] ?? '') ?></textarea>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label>Genre</label>
            <input type="text" name="genre" value="<?= htmlspecialchars($project['genre'] ?? '') ?>" placeholder="e.g. RPG, Platformer">
        </div>
        <div class="form-group">
            <label>Engine</label>
            <select name="engine">
                <option value="">Select Engine</option>
                <option value="Unity" <?= $project['engine'] == 'Unity' ? 'selected' : '' ?>>Unity</option>
                <option value="Godot" <?= $project['engine'] == 'Godot' ? 'selected' : '' ?>>Godot</option>
                <option value="Unreal" <?= $project['engine'] == 'Unreal' ? 'selected' : '' ?>>Unreal Engine</option>
                <option value="Roblox" <?= $project['engine'] == 'Roblox' ? 'selected' : '' ?>>Roblox Studio</option>
                <option value="GameMaker" <?= $project['engine'] == 'GameMaker' ? 'selected' : '' ?>>GameMaker</option>
                <option value="Construct" <?= $project['engine'] == 'Construct' ? 'selected' : '' ?>>Construct 3</option>
                <option value="Other" <?= $project['engine'] == 'Other' ? 'selected' : '' ?>>Other</option>
            </select>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label>Platform</label>
            <select name="platform">
                <option value="">Select Platform</option>
                <option value="PC" <?= $project['platform'] == 'PC' ? 'selected' : '' ?>>PC</option>
                <option value="Mobile" <?= $project['platform'] == 'Mobile' ? 'selected' : '' ?>>Mobile</option>
                <option value="Web" <?= $project['platform'] == 'Web' ? 'selected' : '' ?>>Web</option>
                <option value="Console" <?= $project['platform'] == 'Console' ? 'selected' : '' ?>>Console</option>
                <option value="Multi-Platform" <?= $project['platform'] == 'Multi-Platform' ? 'selected' : '' ?>>Multi-Platform</option>
            </select>
        </div>
        <div class="form-group">
            <label>Target Release Date</label>
            <input type="date" name="target_rilis" value="<?= $project['target_rilis'] ?? '' ?>">
        </div>
    </div>
    
    <div class="form-group">
        <label>Status</label>
        <select name="status">
            <option value="Planning" <?= $project['status'] == 'Planning' ? 'selected' : '' ?>>Planning</option>
            <option value="Development" <?= $project['status'] == 'Development' ? 'selected' : '' ?>>Development</option>
            <option value="Testing" <?= $project['status'] == 'Testing' ? 'selected' : '' ?>>Testing</option>
            <option value="Released" <?= $project['status'] == 'Released' ? 'selected' : '' ?>>Released</option>
        </select>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Update Project</button>
        <a href="/projects/<?= $project['id'] ?>" class="btn">Cancel</a>
    </div>
</form>

<?php include __DIR__ . '/../layouts/footer.php'; ?>


