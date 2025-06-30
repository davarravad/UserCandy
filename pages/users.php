<?php
require_once __DIR__ . '/../core/auth.php';
$user = require_role(['staff','admin']);
$meta['title'] = 'Users';
include __DIR__ . '/../templates/header.php';
?>
<h1 class="text-2xl font-bold mb-4">Users</h1>
<div id="loading" class="mb-2">Loading...</div>
<table id="user-table" class="min-w-full border border-gray-300">
  <thead>
    <tr><th class="border px-2 py-1">ID</th><th class="border px-2 py-1">Email</th></tr>
  </thead>
  <tbody></tbody>
</table>
<?php include __DIR__ . '/../templates/footer.php';
return; ?>
