<?php

$config = require __DIR__ . '/../app/config.php';

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
