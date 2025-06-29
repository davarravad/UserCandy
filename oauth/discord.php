<?php
require_once __DIR__ . '/../core/init.php';
if (!$config['enable_discord_login']) {
    exit('Discord login not enabled');
}
$redirect_uri = base_url('oauth/callback.php?provider=discord');
$client_id = $config['discord_client_id'];
$scope = urlencode('identify email');
$state = bin2hex(random_bytes(8));
$_SESSION['oauth_state'] = $state;
$url = "https://discord.com/api/oauth2/authorize?response_type=code&client_id={$client_id}&redirect_uri={$redirect_uri}&scope={$scope}&state={$state}";
header('Location: ' . $url);
