<?php
require_once __DIR__ . '/../core/auth.php';
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add']) && !empty($_POST['name'])) {
        add_role(trim($_POST['name']));
        set_flash('success', 'Role added');
        header('Location: ' . base_url('roles'));
        exit;
    }
    if (isset($_POST['update']) && isset($_POST['id']) && !empty($_POST['name'])) {
        update_role((int)$_POST['id'], trim($_POST['name']));
        set_flash('success', 'Role updated');
        header('Location: ' . base_url('roles'));
        exit;
    }
}

$roles = get_roles();
$meta['title'] = 'Roles';
render_header();
?>
<h1 class="text-2xl font-bold mb-4">Manage Roles</h1>
<?php $flash = get_flash('success'); if ($flash): ?>
<script>$(function(){ showPopup('<?php echo addslashes($flash); ?>', 'success'); });</script>
<?php endif; ?>
<table class="border mb-4">
<thead><tr><th class="border px-2">Role</th><th class="border px-2">Action</th></tr></thead>
<tbody>
<?php foreach ($roles as $r): ?>
<tr>
    <td class="border px-2">
        <?php if ($r['is_default']): ?>
            <?php echo htmlspecialchars($r['name']); ?>
        <?php else: ?>
            <form method="post" class="flex space-x-2">
                <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                <input type="text" name="name" value="<?php echo htmlspecialchars($r['name']); ?>" class="border px-1">
                <button type="submit" name="update" class="bg-blue-500 text-white px-2">Save</button>
            </form>
        <?php endif; ?>
    </td>
    <td class="border px-2"><?php echo $r['is_default'] ? 'Default' : ''; ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<h2 class="font-bold mb-2">Add Role</h2>
<form method="post" class="space-y-2">
    <input type="text" name="name" class="border p-1" required>
    <button type="submit" name="add" class="bg-blue-500 text-white px-2 py-1">Add</button>
</form>
<?php render_footer(); return; ?>
