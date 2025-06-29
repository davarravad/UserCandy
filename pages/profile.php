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
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="<?php echo base_url('css/style.css'); ?>">
    <title>Profile</title>
</head>
<body>
<h1>Profile for <?php echo htmlspecialchars($profileUser['email']); ?></h1>
<p>User ID: <?php echo $profileUser['id']; ?></p>
</body>
</html>
