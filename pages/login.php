<?php
require_once __DIR__ . '/../core/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!request_from_same_site()) {
        $error = 'Invalid request origin';
    } elseif (!empty($_POST['honeypot'])) {
        $error = 'Bot detected';
    } elseif (!verify_recaptcha($_POST['g-recaptcha-response'] ?? '')) {
        $error = 'Captcha failed';
    } else {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $user = get_user_by_email($email);
        if ($user && password_verify($password, $user['password'])) {
            login_user($user);
            header('Location: ' . base_url('dashboard'));
            exit;
        } else {
            $error = 'Invalid credentials';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="<?php echo base_url('css/style.css'); ?>">
    <title>Login</title>
</head>
<body>
<h1>Login</h1>
<?php if (!empty($error)) echo '<p>' . $error . '</p>'; ?>
<form method="post">
    <input type="text" name="honeypot" style="display:none" autocomplete="off">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <?php if ($config['enable_recaptcha'] && !($config['enable_google_login'] || $config['enable_discord_login'] || $config['enable_windows_login'])): ?>
        <div class="g-recaptcha" data-sitekey="<?php echo $config['recaptcha_site_key']; ?>"></div>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php endif; ?>
    <button type="submit">Login</button>
</form>
<p><a href="<?php echo base_url('register'); ?>">Register</a></p>
<?php if ($config['enable_google_login']): ?>
<a href="<?php echo base_url('oauth/google.php'); ?>">Login with Google</a>
<?php endif; ?>
<?php if ($config['enable_discord_login']): ?>
<a href="<?php echo base_url('oauth/discord.php'); ?>">Login with Discord</a>
<?php endif; ?>
<?php if ($config['enable_windows_login']): ?>
<a href="<?php echo base_url('oauth/windows.php'); ?>">Login with Windows</a>
<?php endif; ?>
</body>
</html>
