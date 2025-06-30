<?php
require_once __DIR__ . '/init.php';

function register_user($email, $password, $role = 'member') {
    global $db;
    $stmt = $db->prepare('INSERT INTO users (email, password, role) VALUES (?, ?, ?)');
    return $stmt->execute([$email, password_hash($password, PASSWORD_DEFAULT), $role]);
}

function get_user_by_email($email) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function get_user_by_id($id) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$id]);
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

function require_role($roles) {
    $user = current_user();
    if (!$user) {
        header('Location: ' . base_url('login'));
        exit;
    }
    $roles = (array)$roles;
    if (!in_array($user['role'] ?? 'guest', $roles)) {
        http_response_code(403);
        echo 'Forbidden';
        exit;
    }
    return $user;
}

function request_from_same_site() {
    $host = $_SERVER['HTTP_HOST'] ?? '';
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        $originHost = parse_url($_SERVER['HTTP_ORIGIN'], PHP_URL_HOST);
        if ($originHost && $originHost !== $host) {
            return false;
        }
    }
    if (isset($_SERVER['HTTP_REFERER'])) {
        $refererHost = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
        if ($refererHost && $refererHost !== $host) {
            return false;
        }
    }
    return true;
}

function verify_recaptcha($response) {
    global $config;
    if (!$config['enable_recaptcha']) {
        return true;
    }
    if (empty($response) || empty($config['recaptcha_secret_key'])) {
        return false;
    }
    $data = [
        'secret' => $config['recaptcha_secret_key'],
        'response' => $response,
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? ''
    ];
    $options = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query($data)
        ]
    ];
    $context = stream_context_create($options);
    $verify = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
    if ($verify === false) {
        return false;
    }
    $result = json_decode($verify, true);
    return !empty($result['success']);
}
