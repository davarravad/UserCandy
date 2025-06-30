<?php
require_once __DIR__ . '/../core/init.php';
if (!$config['enable_facebook_login']) {
    exit('Facebook login not enabled');
}
$redirect_uri = base_url('oauth/callback.php?provider=facebook');
$client_id = $config['facebook_client_id'];
$scope = urlencode('email public_profile');
$state = bin2hex(random_bytes(8));
$_SESSION['oauth_state'] = $state;
$url = "https://www.facebook.com/v12.0/dialog/oauth?response_type=code&client_id={$client_id}&redirect_uri={$redirect_uri}&scope={$scope}&state={$state}";
header('Location: ' . $url);

