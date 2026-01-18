<?php
/**
 * Application Configuration
 * Progress Tracker Game Dev
 */

// Application settings
define('APP_NAME', 'Progress Tracker Game Dev');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/progress-tracker');
define('BASE_PATH', dirname(__DIR__));

// Session settings
define('SESSION_LIFETIME', 3600); // 1 hour

// Security settings
define('CSRF_TOKEN_NAME', 'csrf_token');
define('PASSWORD_MIN_LENGTH', 6);

// File upload settings (if needed in future)
define('UPLOAD_MAX_SIZE', 5242880); // 5MB

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



