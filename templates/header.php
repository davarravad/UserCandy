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
    <nav class="mb-4 flex items-center justify-between">
        <div class="text-xl font-bold">
            <?php echo __('site_name'); ?>
        </div>
        <ul class="flex space-x-4">
            <li><a class="text-blue-700" href="<?php echo base_url(); ?>"><?php echo __('home'); ?></a></li>
            <li><a class="text-blue-700" href="#"><?php echo __('about'); ?></a></li>
            <li><a class="text-blue-700" href="#"><?php echo __('contact'); ?></a></li>
        </ul>
        <div class="flex items-center space-x-4">
            <button id="theme-toggle" class="focus:outline-none">&#9728;</button>
            <div class="relative">
                <button id="notif-bell" class="focus:outline-none">&#128276;</button>
                <div id="notif-menu" class="hidden absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg text-sm">
                    <?php foreach (get_notifications(true) as $nid => $n): ?>
                        <a class="block px-2 py-1 hover:bg-gray-100" href="<?php echo htmlspecialchars($n['url']) . (strpos($n['url'], '?') !== false ? '&' : '?'); ?>notify=<?php echo $nid; ?>"><?php echo htmlspecialchars($n['title']); ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="relative">
                <button id="user-avatar" class="w-8 h-8 rounded-full bg-gray-300"></button>
                <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg text-sm p-2">
                    <?php if ($current = current_user()): ?>
                        <div class="px-2 py-1 border-b">
                            <div><?php echo htmlspecialchars($current['email']); ?></div>
                        </div>
                        <a class="block px-2 py-1" href="<?php echo base_url('dashboard'); ?>"><?php echo __('dashboard'); ?></a>
                        <a class="block px-2 py-1" href="<?php echo base_url('logout'); ?>"><?php echo __('logout'); ?></a>
                    <?php else: ?>
                        <a class="block px-2 py-1" href="<?php echo base_url('login'); ?>"><?php echo __('login'); ?></a>
                        <a class="block px-2 py-1" href="<?php echo base_url('register'); ?>"><?php echo __('register'); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <div id="popup-container" class="fixed top-4 right-4 space-y-2 z-50"></div>

