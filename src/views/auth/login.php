<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Progress Tracker</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-box">
            <h1>🎮 Progress Tracker</h1>
            <h2>Login</h2>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <form method="POST" action="/login">
                <div class="form-group">
                    <label>Username atau Email</label>
                    <input type="text" name="username" required autofocus>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
            
            <p class="auth-footer">
                Belum punya akun? <a href="/register">Daftar di sini</a>
            </p>
            
            <small class="auth-hint">Default: admin / password123</small>
        </div>
    </div>
</body>
</html>


