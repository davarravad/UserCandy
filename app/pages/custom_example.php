<?php
require_once __DIR__ . '/../../core/init.php';
$meta['title'] = 'Custom Example';
include __DIR__ . '/../../templates/header.php';
?>
<h1 class="text-2xl font-bold mb-4">Custom Example Page</h1>
<p>You can modify this page in <code>app/pages/custom_example.php</code>.</p>
<?php
include __DIR__ . '/../../templates/footer.php';
return;
?>
