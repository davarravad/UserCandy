<?php
require_once __DIR__ . '/../core/auth.php';

$userId = $routeParams[0] ?? null;
if (!$userId || !ctype_digit($userId)) {
    http_response_code(404);
    echo 'User not found';
    return;
}

$profileUser = get_user_by_id($userId);

if (!$profileUser) {
    http_response_code(404);
    echo 'User not found';
    return;
}
?>
<?php
$meta['title'] = 'Profile';
render_header();
?>
<h1 class="text-2xl font-bold mb-4">Profile for <?php echo htmlspecialchars($profileUser['email']); ?></h1>
<p>User ID: <?php echo $profileUser['id']; ?></p>
<?php render_footer();
return; ?>
