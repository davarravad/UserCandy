<?php
require_once __DIR__ . '/init.php';

function register_user($email, $password) {
    global $db;
    $stmt = $db->prepare('INSERT INTO users (email, password) VALUES (?, ?)');
    return $stmt->execute([$email, password_hash($password, PASSWORD_DEFAULT)]);
}

function get_user_by_email($email) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function login_user($user) {
    $_SESSION['user_id'] = $user['id'];
}

function current_user() {
    global $db;
    if (!isset($_SESSION['user_id'])) return null;
    $stmt = $db->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function logout_user() {
    unset($_SESSION['user_id']);
    session_destroy();
}
