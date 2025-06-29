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

if (!function_exists('uc_log_error')) {
    function uc_log_error($message) {
        global $logFile;
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        array_shift($trace); // remove this function from trace
        $stackInfo = '';
        if (!empty($trace)) {
            foreach ($trace as $t) {
                $file = $t['file'] ?? '[internal function]';
                $line = $t['line'] ?? '';
                $func = $t['function'] ?? '';
                $stackInfo .= "$file:$line $func\n";
            }
        }
        $logMessage = '[' . date('Y-m-d H:i:s') . '] ' . $message;
        if ($stackInfo) {
            $logMessage .= "\nStack trace:\n" . rtrim($stackInfo);
        }
        error_log($logMessage . PHP_EOL, 3, $logFile);
    }
}

if (!function_exists('uc_error_page')) {
    function uc_error_page($message = '') {
        $home = function_exists('base_url') ? htmlspecialchars(base_url()) : '/';
        header('Content-Type: text/html; charset=UTF-8');
        echo "<!DOCTYPE html><html><head><meta charset='UTF-8'>";
        echo "<title>UserCandy Framework - Error</title>";
        echo "<link href='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' rel='stylesheet'>";
        echo "</head><body class='p-4 font-sans'>";
        echo "<h1 class='text-2xl font-bold mb-4'>UserCandy Framework - Error</h1>";
        echo "<p>An unexpected error occurred and has been logged for the administrator to research.</p>";
        echo "<a href='{$home}' class='text-blue-700'>Go Home</a>";
        echo "</body></html>";
        exit;
    }
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

// Provide sane defaults if optional keys are missing
$config['language'] = $config['language'] ?? 'en';
$config['available_languages'] = $config['available_languages'] ?? ['en'];

// Language loading
$langCode = $_GET['lang'] ?? $_SESSION['lang'] ?? $config['language'];
if (!in_array($langCode, $config['available_languages'])) {
    $langCode = $config['language'];
}
$_SESSION['lang'] = $langCode;
$langFile = __DIR__ . '/../languages/' . $langCode . '.php';
if (file_exists($langFile)) {
    $lang = require $langFile;
} else {
    $lang = [];
}

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

require_once __DIR__ . '/notify.php';

if (!function_exists('base_url')) {
    function base_url($path = '') {
        global $config;
        return rtrim($config['base_url'], '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('__')) {
    function __($key) {
        global $lang;
        return $lang[$key] ?? $key;
    }
}
