<?php
require_once __DIR__ . '/../core/init.php';
if (!$config['enable_google_login']) {
    exit('Google login not enabled');
}
$redirect_uri = base_url('oauth/callback.php?provider=google');
$client_id = $config['google_client_id'];
$scope = urlencode('email profile');
$state = bin2hex(random_bytes(8));
$_SESSION['oauth_state'] = $state;
$url = "https://accounts.google.com/o/oauth2/v2/auth?response_type=code&client_id={$client_id}&redirect_uri={$redirect_uri}&scope={$scope}&state={$state}";
header('Location: ' . $url);
