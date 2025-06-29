<?php
require_once __DIR__ . '/init.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($path, '/');

if ($path === '') {
    $page = 'home';
} else {
    $page = $path;
}

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
