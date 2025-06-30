<?php
if (!function_exists('current_user')) {
    require_once __DIR__ . '/../core/auth.php';
}
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
        <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-2">
                <img src="<?php echo base_url('images/userCandyLogo.png'); ?>" alt="UserCandy" class="h-8" />
                <span class="text-xl font-bold"><?php echo __('site_name'); ?></span>
            </div>
            <ul class="flex space-x-4">
                <li><a class="text-blue-700" href="<?php echo base_url(); ?>"><?php echo __('home'); ?></a></li>
                <li><a class="text-blue-700" href="<?php echo base_url('about'); ?>"><?php echo __('about'); ?></a></li>
                <li><a class="text-blue-700" href="<?php echo base_url('contact'); ?>"><?php echo __('contact'); ?></a></li>
            </ul>
        </div>
        <div class="flex items-center space-x-4">
            <button id="theme-toggle" class="focus:outline-none">&#9728;</button>
            <div class="relative">
                <button id="notif-bell" class="focus:outline-none">&#128276;</button>
                <div id="notif-menu" class="hidden absolute right-0 mt-2 w-56 bg-white border rounded shadow-lg text-sm">
                    <?php foreach (get_notifications() as $nid => $n): ?>
                        <a class="block px-2 py-1 hover:bg-gray-100<?php echo empty($n['read']) ? ' font-bold' : ''; ?>" href="<?php echo htmlspecialchars($n['url']) . (strpos($n['url'], '?') !== false ? '&' : '?'); ?>notify=<?php echo $nid; ?>"><?php echo htmlspecialchars($n['title']); ?></a>
                    <?php endforeach; ?>
                    <div class="border-t px-2 py-1">
                        <a class="text-blue-700 block" href="<?php echo base_url('notifications?mark_all=1'); ?>">Mark all read</a>
                        <a class="text-blue-700 block" href="<?php echo base_url('notifications'); ?>">View all</a>
                    </div>
                </div>
            </div>
            <div class="relative">
                <?php if ($current = current_user()): ?>
                    <?php if (!empty($current['avatar'])): ?>
                        <img id="user-avatar" src="<?php echo htmlspecialchars($current['avatar']); ?>" class="w-8 h-8 rounded-full cursor-pointer" alt="avatar" />
                    <?php else: ?>
                        <span id="user-avatar" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-300 cursor-pointer">&#128100;</span>
                    <?php endif; ?>
                <?php else: ?>
                    <span id="user-avatar" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-300 cursor-pointer">&#128100;</span>
                <?php endif; ?>
                <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg text-sm p-2">
                    <?php if ($current): ?>
                        <div class="px-2 py-1 border-b">
                            <div><?php echo htmlspecialchars($current['email']); ?></div>
                        </div>
                        <a class="block px-2 py-1" href="<?php echo base_url('profile/' . $current['id']); ?>">Profile</a>
                        <a class="block px-2 py-1" href="<?php echo base_url('account'); ?>">Account</a>
                        <a class="block px-2 py-1" href="<?php echo base_url('dashboard'); ?>"><?php echo __('dashboard'); ?></a>
                        <?php if ($current['role'] === 'admin'): ?>
                            <a class="block px-2 py-1" href="<?php echo base_url('admin'); ?>">Admin</a>
                        <?php endif; ?>
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

