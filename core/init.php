<?php

$configFile = __DIR__ . '/../app/config.php';
if (!file_exists($configFile)) {
    die('Please rename app/default-config.php to app/config.php and update your settings.');
}
$config = require $configFile;

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
