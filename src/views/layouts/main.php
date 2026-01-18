<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Progress Tracker Game Dev' ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <?php if (isset($_SESSION['user_id'])): ?>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <a href="/dashboard">Progress Tracker</a>
            </div>
            <div class="nav-menu">
                <a href="/dashboard">Dashboard</a>
                <a href="/projects">Projects</a>
                <span class="nav-user"><?= htmlspecialchars($_SESSION['username']) ?></span>
                <a href="/logout">Logout</a>
            </div>
        </div>
    </nav>
    <?php endif; ?>
    
    <main class="container">
        <?php if (isset($flash_message)): ?>
            <div class="alert alert-<?= $flash_type ?? 'info' ?>">
                <?= htmlspecialchars($flash_message) ?>
            </div>
        <?php endif; ?>
        
        <?= $content ?>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; <?= date('Y') ?> Progress Tracker Game Dev. All rights reserved.</p>
        </div>
    </footer>
    
    <script src="/assets/js/app.js"></script>
</body>
</html>



