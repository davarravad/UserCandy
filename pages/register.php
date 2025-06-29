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
            set_flash('success', 'Registration successful');
            header('Location: ' . base_url('login'));
            exit;
        } else {
            $error = 'Registration failed';
        }
    }
}
?>
<?php
$meta['title'] = 'Register';
include __DIR__ . '/../templates/header.php';
?>
<h1 class="text-2xl font-bold mb-4">Register</h1>
<?php if (!empty($error)) echo '<p class="text-red-500">' . $error . '</p>'; ?>
<form method="post" class="space-y-2">
    <input type="text" name="honeypot" style="display:none" autocomplete="off">
    <input type="email" name="email" placeholder="Email" required class="border p-1">
    <input type="password" name="password" placeholder="Password" required class="border p-1">
    <?php if ($config['enable_recaptcha'] && !($config['enable_google_login'] || $config['enable_discord_login'] || $config['enable_windows_login'])): ?>
        <div class="g-recaptcha" data-sitekey="<?php echo $config['recaptcha_site_key']; ?>"></div>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php endif; ?>
    <button type="submit" class="bg-blue-500 text-white px-2 py-1">Register</button>
</form>
<p><a class="text-blue-700" href="<?php echo base_url('login'); ?>">Login</a></p>
<?php include __DIR__ . '/../templates/footer.php';
return; ?>
