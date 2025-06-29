<?php
require_once __DIR__ . '/../core/auth.php';
$user = current_user();
if (!$user) {
    header('Location: ' . base_url('login'));
    exit;
}
?>
<?php
$meta['title'] = 'Dashboard';
include __DIR__ . '/../templates/header.php';
?>
<h1 class="text-2xl font-bold mb-4">Dashboard</h1>
<p>Welcome <?php echo htmlspecialchars($user['email']); ?>!</p>
<p><a class="text-blue-700" href="<?php echo base_url('logout'); ?>">Logout</a></p>
<?php include __DIR__ . '/../templates/footer.php';
return; ?>
