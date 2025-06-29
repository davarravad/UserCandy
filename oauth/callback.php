<?php
require_once __DIR__ . '/../core/init.php';
$provider = $_GET['provider'] ?? '';
if (!in_array($provider, ['google', 'discord', 'windows'])) {
    exit('Unknown provider');
}
if (!isset($_GET['state']) || $_GET['state'] !== ($_SESSION['oauth_state'] ?? '')) {
    exit('Invalid state');
}
// Normally you would exchange the code for an access token and retrieve user info
// Here we simply simulate successful login
$_SESSION['user_id'] = 0; // Placeholder user id for OAuth logins
unset($_SESSION['oauth_state']);
header('Location: ' . base_url('dashboard'));
