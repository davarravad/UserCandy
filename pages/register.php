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
        if (register_user($email, $password)) {
            header('Location: ' . base_url('login'));
            exit;
        } else {
            $error = 'Registration failed';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="<?php echo base_url('css/style.css'); ?>">
    <title>Register</title>
</head>
<body>
<h1>Register</h1>
<?php if (!empty($error)) echo '<p>' . $error . '</p>'; ?>
<form method="post">
    <input type="text" name="honeypot" style="display:none" autocomplete="off">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <?php if ($config['enable_recaptcha'] && !($config['enable_google_login'] || $config['enable_discord_login'] || $config['enable_windows_login'])): ?>
        <div class="g-recaptcha" data-sitekey="<?php echo $config['recaptcha_site_key']; ?>"></div>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php endif; ?>
    <button type="submit">Register</button>
</form>
<p><a href="<?php echo base_url('login'); ?>">Login</a></p>
</body>
</html>
