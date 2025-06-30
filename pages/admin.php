<?php
require_once __DIR__ . '/../core/auth.php';
require_role('admin');
$meta['title'] = 'Admin';
render_header();
?>
<h1 class="text-2xl font-bold mb-4">Admin Area</h1>
<p>Only admins can see this page.</p>
<p><a class="text-blue-700" href="<?php echo base_url('roles'); ?>">Manage Roles</a></p>
<?php
render_footer();
return;
?>
