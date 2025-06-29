<?php include __DIR__ . '/../core/init.php';
$meta['title'] = 'Home';
include __DIR__ . '/../templates/header.php';
?>
<h1 class="text-2xl font-bold mb-4">Welcome to UserCandy Framework</h1>
<p><a class="text-blue-700" href="<?php echo base_url('login'); ?>"><?php echo __('login'); ?></a> or <a class="text-blue-700" href="<?php echo base_url('register'); ?>"><?php echo __('register'); ?></a></p>
<?php include __DIR__ . '/../templates/footer.php';
return; ?>
