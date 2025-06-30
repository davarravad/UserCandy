<?php
require_once __DIR__ . '/../core/auth.php';
require_role('admin');
$meta['title'] = 'Admin';
include __DIR__ . '/../templates/header.php';
?>
<h1 class="text-2xl font-bold mb-4">Admin Area</h1>
<p>Only admins can see this page.</p>
<?php
include __DIR__ . '/../templates/footer.php';
return;
?>
