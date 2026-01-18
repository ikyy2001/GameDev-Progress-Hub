<?php
/**
 * Entry Point / Router
 * Progress Tracker Game Dev
 */

// Load configuration
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

// Autoloader sederhana
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../src/utils/',
        __DIR__ . '/../src/models/',
        __DIR__ . '/../src/controllers/',
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            break;
        }
    }
});

// Simple Router
$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

// Remove query string
$uri = parse_url($request_uri, PHP_URL_PATH);

// Remove base path if exists (handle subdirectory)
$script_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if ($script_dir !== '/' && $script_dir !== '\\') {
    if (strpos($uri, $script_dir) === 0) {
        $uri = substr($uri, strlen($script_dir));
    }
}

// Also handle index.php if present
$uri = str_replace('/index.php', '', $uri);

// Remove leading/trailing slashes
$uri = trim($uri, '/');

// If empty URI, default to dashboard
if (empty($uri)) {
    $uri = 'dashboard';
}

// Debug (uncomment jika perlu)
// error_log("Request URI: $request_uri");
// error_log("Parsed URI: $uri");

// Route definition
$routes = [
    // Authentication
    'GET:/login' => 'AuthController::loginForm',
    'POST:/login' => 'AuthController::login',
    'GET:/register' => 'AuthController::registerForm',
    'POST:/register' => 'AuthController::register',
    'GET:/logout' => 'AuthController::logout',
    
    // Dashboard
    'GET:/' => 'DashboardController::index',
    'GET:/dashboard' => 'DashboardController::index',
    
    // Root redirect
    'GET:/index.php' => 'DashboardController::index',
    
    // Projects
    'GET:/projects' => 'ProjectController::index',
    'GET:/projects/create' => 'ProjectController::createForm',
    'POST:/projects/create' => 'ProjectController::create',
    'GET:/projects/{id}' => 'ProjectController::show',
    'GET:/projects/{id}/edit' => 'ProjectController::editForm',
    'POST:/projects/{id}/edit' => 'ProjectController::update',
    'POST:/projects/{id}/delete' => 'ProjectController::delete',
    
    // Tasks
    'POST:/tasks/create' => 'TaskController::create',
    'POST:/tasks/{id}/update-status' => 'TaskController::updateStatus',
    'POST:/tasks/{id}/update' => 'TaskController::update',
    'POST:/tasks/{id}/delete' => 'TaskController::delete',
    
    // Profile
    'GET:/profile' => 'ProfileController::index',
    'POST:/profile/update' => 'ProfileController::update',
];

// Match route
$route_key = $request_method . ':/' . $uri;
$matched = false;
$handler = null;

// Try exact match first
if (isset($routes[$route_key])) {
    $handler = $routes[$route_key];
    $matched = true;
} else {
    // Try pattern matching with parameters
    foreach ($routes as $pattern => $route_handler) {
        // Remove method prefix from pattern
        $pattern_path = preg_replace('/^[A-Z]+:\//', '', $pattern);
        $pattern_method = strtoupper(explode(':', $pattern)[0]);
        
        // Check method match
        if ($pattern_method !== $request_method) {
            continue;
        }
        
        // Normalize paths
        $pattern_parts = array_filter(explode('/', trim($pattern_path, '/')));
        $uri_parts = array_filter(explode('/', $uri));
        
        // Check if part count matches
        if (count($pattern_parts) !== count($uri_parts)) {
            continue;
        }
        
        // Reset arrays for proper indexing
        $pattern_parts = array_values($pattern_parts);
        $uri_parts = array_values($uri_parts);
        
        $params = [];
        $match = true;
        
        for ($i = 0; $i < count($pattern_parts); $i++) {
            if (preg_match('/\{(\w+)\}/', $pattern_parts[$i], $matches)) {
                // This is a parameter
                $params[$matches[1]] = $uri_parts[$i];
            } elseif ($pattern_parts[$i] !== $uri_parts[$i]) {
                // Part doesn't match
                $match = false;
                break;
            }
        }
        
        if ($match) {
            $_GET = array_merge($_GET, $params);
            $handler = $route_handler;
            $matched = true;
            break;
        }
    }
}

// Handle route
if ($matched && isset($handler)) {
    list($controller, $method) = explode('::', $handler);
    
    // Load controller
    $controller_file = __DIR__ . '/../src/controllers/' . $controller . '.php';
    if (file_exists($controller_file)) {
        require_once $controller_file;
        
        if (class_exists($controller) && method_exists($controller, $method)) {
            $controller_instance = new $controller();
            $controller_instance->$method();
        } else {
            http_response_code(500);
            echo "Controller or method not found: $controller::$method";
            // Debug: uncomment line below to see details
            // error_log("Error: Class exists: " . (class_exists($controller) ? 'Yes' : 'No') . ", Method exists: " . (method_exists($controller, $method) ? 'Yes' : 'No'));
        }
    } else {
        http_response_code(404);
        echo "Controller file not found: $controller";
        // Debug: uncomment line below to see path
        // error_log("Controller file path: $controller_file");
    }
} else {
    // 404 Not Found - Route not matched
    http_response_code(404);
    // Debug: uncomment lines below to see routing details
    // error_log("Route not matched. URI: $uri, Method: $request_method");
    // error_log("Available routes: " . implode(', ', array_keys($routes)));
    require_once __DIR__ . '/../src/views/errors/404.php';
}

