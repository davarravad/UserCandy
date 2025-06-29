<?php
require_once __DIR__ . '/../core/init.php';
if (!$config['enable_windows_login']) {
    exit('Windows login not enabled');
}
$redirect_uri = base_url('oauth/callback.php?provider=windows');
$client_id = $config['windows_client_id'];
$scope = urlencode('openid email profile');
$state = bin2hex(random_bytes(8));
$_SESSION['oauth_state'] = $state;
$url = "https://login.microsoftonline.com/consumers/oauth2/v2.0/authorize?response_type=code&client_id={$client_id}&redirect_uri={$redirect_uri}&scope={$scope}&state={$state}";
header('Location: ' . $url);
