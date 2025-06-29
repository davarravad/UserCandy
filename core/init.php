<?php

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
