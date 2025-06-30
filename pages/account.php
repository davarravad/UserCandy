<?php
require_once __DIR__ . '/../core/auth.php';
$user = require_role('member');
$meta['title'] = 'Account';
render_header();
?>
<h1 class="text-2xl font-bold mb-4">Account</h1>
<p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
<p>Role: <?php echo htmlspecialchars($user['role']); ?></p>
<?php
render_footer();
return;
?>
