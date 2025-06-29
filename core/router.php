<?php
require_once __DIR__ . '/init.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($path, '/');
$segments = $path === '' ? [] : explode('/', $path);
$page = $segments[0] ?? 'home';
$GLOBALS['routeParams'] = array_slice($segments, 1);

$pageFile = __DIR__ . '/../pages/' . $page . '.php';
$customFile = __DIR__ . '/../app/pages/' . $page . '.php';

if (file_exists($customFile)) {
    include $customFile;
} elseif (file_exists($pageFile)) {
    include $pageFile;
} else {
    http_response_code(404);
    echo 'Page not found';
}
