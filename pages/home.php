<?php require_once __DIR__ . '/../core/init.php';
$meta['title'] = 'Home';
render_header();
?>
<h1 class="text-2xl font-bold mb-4">Welcome to UserCandy Framework</h1>
<p>To get started, copy <code>app/default-config.php</code> to <code>app/config.php</code> and edit the database settings.</p>
<p>Edit files in <code>app/pages</code> to customize content.</p>
<p><a class="text-blue-700" href="<?php echo base_url('login'); ?>"><?php echo __('login'); ?></a> or <a class="text-blue-700" href="<?php echo base_url('register'); ?>"><?php echo __('register'); ?></a></p>
<?php render_footer();
return; ?>
