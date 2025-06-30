<?php
$pathPrefix = __DIR__ . '/../../';
require_once $pathPrefix . 'core/init.php';
$meta['title'] = 'About';
include $pathPrefix . 'templates/header.php';
?>
<h1 class="text-2xl font-bold mb-4">About</h1>
<p>This page can be customized by editing <code>app/pages/about.php</code>.</p>
<?php
include $pathPrefix . 'templates/footer.php';
return;
?>
