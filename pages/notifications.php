<?php
require_once __DIR__ . '/../core/auth.php';
$user = require_login();
if (isset($_GET['mark_all'])) {
    foreach (get_notifications() as $nid => $_) {
        mark_notification_read($nid);
    }
    header('Location: ' . base_url('notifications'));
    exit;
}
$meta['title'] = 'Notifications';
render_header();
?>
<h1 class="text-2xl font-bold mb-4">Notifications</h1>
<?php $notifs = get_notifications(); if (!$notifs): ?>
<p>No notifications.</p>
<?php else: ?>
<ul class="space-y-1">
<?php foreach ($notifs as $nid => $n): ?>
    <li>
        <a class="text-blue-700" href="<?php echo htmlspecialchars($n['url']) . (strpos($n['url'], '?') !== false ? '&' : '?'); ?>notify=<?php echo $nid; ?>">
            <?php echo htmlspecialchars($n['title']); ?><?php if (empty($n['read'])) echo ' (new)'; ?>
        </a>
    </li>
<?php endforeach; ?>
</ul>
<a class="text-blue-700" href="?mark_all=1">Mark all read</a>
<?php endif; ?>
<?php
render_footer();
return;
?>
