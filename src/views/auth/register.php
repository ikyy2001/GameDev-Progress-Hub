<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Progress Tracker</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-box">
            <h1>🎮 Progress Tracker</h1>
            <h2>Register</h2>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <form method="POST" action="/register">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required autofocus>
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label>Password (min. 6 karakter)</label>
                    <input type="password" name="password" required minlength="6">
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Daftar</button>
            </form>
            
            <p class="auth-footer">
                Sudah punya akun? <a href="/login">Login di sini</a>
            </p>
        </div>
    </div>
</body>
</html>


