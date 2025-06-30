<?php
$pathPrefix = __DIR__ . '/../../';
include $pathPrefix . 'core/init.php';
$meta['title'] = 'Home';
include $pathPrefix . 'templates/header.php';
?>
<h1 class="text-2xl font-bold mb-4">Welcome to UserCandy Framework</h1>
<p><a class="text-blue-700" href="<?php echo base_url('login'); ?>"><?php echo __('login'); ?></a> or <a class="text-blue-700" href="<?php echo base_url('register'); ?>"><?php echo __('register'); ?></a></p>
<?php include $pathPrefix . 'templates/footer.php';
return; ?>
