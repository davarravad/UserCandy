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
            set_flash('success', 'Logged in');
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
render_header();
?>
<?php $flash = get_flash('success'); if ($flash): ?>
<script>$(function(){ showPopup('<?php echo addslashes($flash); ?>', 'success'); });</script>
<?php endif; ?>
<h1 class="text-2xl font-bold mb-4 text-center">Login</h1>
<?php if (!empty($error)) echo '<p class="text-red-500 text-center">' . $error . '</p>'; ?>
<div class="max-w-sm mx-auto p-6 bg-white dark:bg-gray-800 rounded shadow">
<form method="post" class="space-y-3">
    <input type="text" name="honeypot" style="display:none" autocomplete="off">
    <div class="flex items-center border rounded px-2 py-1">
        <span class="mr-2">&#9993;</span>
        <input type="email" name="email" placeholder="Email" required class="flex-grow focus:outline-none bg-transparent">
    </div>
    <div class="flex items-center border rounded px-2 py-1">
        <span class="mr-2">&#128274;</span>
        <input type="password" name="password" placeholder="Password" required class="flex-grow focus:outline-none bg-transparent">
    </div>
    <label class="block"><input type="checkbox" name="remember" id="remember"> Remember me</label>
    <?php if ($config['enable_recaptcha'] && !($config['enable_google_login'] || $config['enable_discord_login'] || $config['enable_windows_login'] || $config['enable_facebook_login'])): ?>
        <div class="g-recaptcha" data-sitekey="<?php echo $config['recaptcha_site_key']; ?>"></div>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php endif; ?>
    <button type="submit" class="bg-blue-500 text-white px-2 py-1 w-full">Login</button>
</form>
</div>
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
<?php if ($config['enable_facebook_login']): ?>
<a class="text-blue-700" href="<?php echo base_url('oauth/facebook.php'); ?>">Login with Facebook</a>
<?php endif; ?>
<script>
document.getElementById('remember').addEventListener('change', function(){
    if(this.checked && !confirm('Stay logged in on this device?')){
        this.checked = false;
    }
});
</script>
<?php render_footer();
return; ?>
