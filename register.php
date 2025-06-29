<?php
require_once __DIR__ . '/functions.php';

$provider = $_GET['provider'] ?? 'local';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $token = $_POST['g-recaptcha-response'] ?? '';

    if (!in_array($provider, ['windows', 'google', 'discord'])) {
        if (!verifyRecaptcha($token)) {
            $error = 'reCAPTCHA validation failed.';
        }
    }

    if (!isset($error)) {
        // TODO: implement actual registration logic
        $message = 'Registration successful (placeholder).';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
<h1>Register</h1>
<?php if (!empty($error)): ?>
    <p style="color:red;">
        <?= htmlspecialchars($error) ?>
    </p>
<?php elseif (!empty($message)): ?>
    <p style="color:green;">
        <?= htmlspecialchars($message) ?>
    </p>
<?php endif; ?>
<form method="post" action="?provider=<?= htmlspecialchars($provider) ?>">
    <label>Username:<br><input type="text" name="username"></label><br>
    <label>Password:<br><input type="password" name="password"></label><br>
    <?php if (!in_array($provider, ['windows', 'google', 'discord'])): ?>
        <div class="g-recaptcha" data-sitekey="<?= htmlspecialchars(RECAPTCHA_SITE_KEY) ?>"></div>
    <?php endif; ?>
    <br>
    <button type="submit">Register</button>
</form>
</body>
</html>
