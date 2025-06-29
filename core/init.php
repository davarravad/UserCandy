<?php
if (defined('UC_INIT')) {
    return;
}
define('UC_INIT', true);

// Setup logging directory and file
$logDir = __DIR__ . '/../logs';
if (!is_dir($logDir)) {
    @mkdir($logDir, 0755, true);
}
$logFile = $logDir . '/error.log';

function uc_log_error($message) {
    global $logFile;
    error_log('[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL, 3, $logFile);
}

function uc_error_page($message = '') {
    $home = htmlspecialchars(base_url());
    header('Content-Type: text/html; charset=UTF-8');
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'>";
    echo "<title>UserCandy Framework - Error</title>";
    echo "<link href='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' rel='stylesheet'>";
    echo "</head><body class='p-4 font-sans'>";
    echo "<h1 class='text-2xl font-bold mb-4'>UserCandy Framework - Error</h1>";
    echo "<p>An unexpected error occurred and has been logged for the administrator.</p>";
    if ($message) {
        echo "<pre class='bg-gray-100 p-2 mt-2 overflow-x-auto'>" . htmlspecialchars($message) . "</pre>";
    }
    echo "<a href='{$home}' class='text-blue-700'>Go Home</a>";
    echo "</body></html>";
    exit;
}

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    uc_log_error("Error [$errno] $errstr in $errfile:$errline");
    if ($errno & (E_ERROR | E_USER_ERROR | E_RECOVERABLE_ERROR)) {
        http_response_code(500);
        uc_error_page($errstr);
    }
    return false; // use default PHP error handling for non-fatal errors
});

set_exception_handler(function ($exception) {
    uc_log_error('Uncaught Exception: ' . $exception->getMessage() . ' in ' . $exception->getFile() . ':' . $exception->getLine());
    http_response_code(500);
    uc_error_page($exception->getMessage());
});

register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        uc_log_error('Fatal Error: ' . $error['message'] . ' in ' . $error['file'] . ':' . $error['line']);
        http_response_code(500);
        uc_error_page($error['message']);
    }
});

$configPath = __DIR__ . '/../app/config.php';
if (!file_exists($configPath)) {
    header('HTTP/1.1 500 Internal Server Error');
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'>";
    echo "<title>Setup Required</title>";
    echo "<link href='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' rel='stylesheet'>";
    echo "</head><body class='p-4 font-sans'>";
    echo "<h1 class='text-2xl font-bold mb-4'>Configuration Missing</h1>";
    echo "<p>Copy <code>app/default-config.php</code> to <code>app/config.php</code> and update your database settings.</p>";
    echo "</body></html>";
    exit;
}

$config = require $configPath;

// Database connection
try {
    $db = new PDO(
        "mysql:host={$config['db']['host']};dbname={$config['db']['name']};charset=utf8mb4",
        $config['db']['user'],
        $config['db']['pass']
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

session_start();

function base_url($path = '') {
    global $config;
    return rtrim($config['base_url'], '/') . '/' . ltrim($path, '/');
}
