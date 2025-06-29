<?php
require_once __DIR__ . '/../core/auth.php';
$user = current_user();
if (!$user) {
    header('Location: ' . base_url('login'));
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="<?php echo base_url('public/css/style.css'); ?>">
    <title>Dashboard</title>
</head>
<body>
<h1>Dashboard</h1>
<p>Welcome <?php echo htmlspecialchars($user['email']); ?>!</p>
<p><a href="<?php echo base_url('logout'); ?>">Logout</a></p>
</body>
</html>
