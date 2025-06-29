<?php
if (!isset($meta['title'])) { $meta['title'] = 'UserCandy'; }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($meta['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('css/style.css'); ?>">
    <script>const baseUrl = "<?php echo rtrim(base_url(), '/'); ?>/";</script>
</head>
<body class="p-4">
