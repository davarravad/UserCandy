<?php
require_once __DIR__ . '/../core/auth.php';
$user = require_login();
add_notification('Visit your profile', base_url('profile/' . $user['id']));
?>
<?php
$meta['title'] = 'Dashboard';
render_header();
?>
<?php $flash = get_flash('success'); if ($flash): ?>
<script>$(function(){ showPopup('<?php echo addslashes($flash); ?>','success'); });</script>
<?php endif; ?>
?>
<h1 class="text-2xl font-bold mb-4">Dashboard</h1>
<p>Welcome <?php echo htmlspecialchars($user['email']); ?>!</p>
<p><a class="text-blue-700" href="<?php echo base_url('logout'); ?>">Logout</a></p>
<?php render_footer();
return; ?>
