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
            if (!empty($_POST['remember'])) {
                setcookie(session_name(), session_id(), time() + 60 * 60 * 24 * 30, '/');
            }
            header('Location: ' . base_url('dashboard'));
            exit;
        } else {
            $error = 'Invalid credentials';
        }
    }
}
?>
<?php
$meta['title'] = 'Login';
include __DIR__ . '/../templates/header.php';
?>
<h1 class="text-2xl font-bold mb-4">Login</h1>
<?php if (!empty($error)) echo '<p class="text-red-500">' . $error . '</p>'; ?>
<form method="post" class="space-y-2">
    <input type="text" name="honeypot" style="display:none" autocomplete="off">
    <input type="email" name="email" placeholder="Email" required class="border p-1">
    <input type="password" name="password" placeholder="Password" required class="border p-1">
    <label class="block"><input type="checkbox" name="remember" id="remember"> Remember me</label>
    <?php if ($config['enable_recaptcha'] && !($config['enable_google_login'] || $config['enable_discord_login'] || $config['enable_windows_login'])): ?>
        <div class="g-recaptcha" data-sitekey="<?php echo $config['recaptcha_site_key']; ?>"></div>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php endif; ?>
    <button type="submit" class="bg-blue-500 text-white px-2 py-1">Login</button>
</form>
<p><a class="text-blue-700" href="<?php echo base_url('register'); ?>">Register</a></p>
<?php if ($config['enable_google_login']): ?>
<a class="text-blue-700" href="<?php echo base_url('oauth/google.php'); ?>">Login with Google</a>
<?php endif; ?>
<?php if ($config['enable_discord_login']): ?>
<a class="text-blue-700" href="<?php echo base_url('oauth/discord.php'); ?>">Login with Discord</a>
<?php endif; ?>
<?php if ($config['enable_windows_login']): ?>
<a class="text-blue-700" href="<?php echo base_url('oauth/windows.php'); ?>">Login with Windows</a>
<?php endif; ?>
<script>
document.querySelector('form').addEventListener('submit', function(e) {
    var cb = document.getElementById('remember');
    if (cb.checked) {
        if (!confirm('Stay logged in on this device?')) {
            cb.checked = false;
        }
    }
});
</script>
<?php include __DIR__ . '/../templates/footer.php';
return; ?>
