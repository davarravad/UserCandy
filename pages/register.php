<?php
require_once __DIR__ . '/../core/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    if (register_user($email, $password)) {
        header('Location: ' . base_url('login'));
        exit;
    } else {
        $error = 'Registration failed';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="<?php echo base_url('public/css/style.css'); ?>">
    <title>Register</title>
</head>
<body>
<h1>Register</h1>
<?php if (!empty($error)) echo '<p>' . $error . '</p>'; ?>
<form method="post">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Register</button>
</form>
<p><a href="<?php echo base_url('login'); ?>">Login</a></p>
</body>
</html>
