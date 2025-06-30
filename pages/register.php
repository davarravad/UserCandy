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
render_header();
?>
<h1 class="text-2xl font-bold mb-4 text-center">Register</h1>
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
    <?php if ($config['enable_recaptcha'] && !($config['enable_google_login'] || $config['enable_discord_login'] || $config['enable_windows_login'] || $config['enable_facebook_login'])): ?>
        <div class="g-recaptcha" data-sitekey="<?php echo $config['recaptcha_site_key']; ?>"></div>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php endif; ?>
    <button type="submit" class="bg-blue-500 text-white px-2 py-1 w-full">Register</button>
</form>
</div>
<p class="text-center mt-2"><a class="text-blue-700" href="<?php echo base_url('login'); ?>">Login</a></p>
<?php render_footer();
return; ?>
