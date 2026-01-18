<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Progress Tracker' ?> - Game Dev Progress Tracker</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <a href="/dashboard">🎮 Progress Tracker</a>
            </div>
            <div class="nav-menu">
                <a href="/dashboard">Dashboard</a>
                <a href="/projects">Projects</a>
                <a href="/profile">Profile</a>
                <span class="nav-user"><?= htmlspecialchars($_SESSION['username'] ?? '') ?></span>
                <a href="/logout" class="btn-logout">Logout</a>
            </div>
        </div>
    </nav>
    <main class="container">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>


