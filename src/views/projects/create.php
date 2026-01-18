<?php
$page_title = 'Create Project';
include __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">
    <h1>Create New Project</h1>
    <a href="/projects" class="btn">← Back</a>
</div>

<form method="POST" action="/projects/create" class="form-box">
    <div class="form-group">
        <label>Project Name *</label>
        <input type="text" name="name" required placeholder="Nama game Anda">
    </div>
    
    <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="4" placeholder="Deskripsi project"></textarea>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label>Genre</label>
            <input type="text" name="genre" placeholder="e.g. RPG, Platformer, Puzzle">
        </div>
        <div class="form-group">
            <label>Engine</label>
            <select name="engine">
                <option value="">Select Engine</option>
                <option value="Unity">Unity</option>
                <option value="Godot">Godot</option>
                <option value="Unreal">Unreal Engine</option>
                <option value="Roblox">Roblox Studio</option>
                <option value="GameMaker">GameMaker</option>
                <option value="Construct">Construct 3</option>
                <option value="Other">Other</option>
            </select>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label>Platform</label>
            <select name="platform">
                <option value="">Select Platform</option>
                <option value="PC">PC</option>
                <option value="Mobile">Mobile</option>
                <option value="Web">Web</option>
                <option value="Console">Console</option>
                <option value="Multi-Platform">Multi-Platform</option>
            </select>
        </div>
        <div class="form-group">
            <label>Target Release Date</label>
            <input type="date" name="target_rilis">
        </div>
    </div>
    
    <div class="form-group">
        <label>Status</label>
        <select name="status">
            <option value="Planning">Planning</option>
            <option value="Development" selected>Development</option>
            <option value="Testing">Testing</option>
            <option value="Released">Released</option>
        </select>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Create Project</button>
        <a href="/projects" class="btn">Cancel</a>
    </div>
</form>

<?php include __DIR__ . '/../layouts/footer.php'; ?>


