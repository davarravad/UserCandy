<?php
if (!isset($_SESSION['uc_notifications'])) {
    $_SESSION['uc_notifications'] = [];
}
if (!isset($_SESSION['uc_flash'])) {
    $_SESSION['uc_flash'] = [];
}

function add_notification($title, $url) {
    $id = uniqid('n', true);
    $_SESSION['uc_notifications'][$id] = [
        'title' => $title,
        'url' => $url,
        'read' => false,
    ];
    return $id;
}

function get_notifications($onlyUnread = false) {
    $notifs = $_SESSION['uc_notifications'] ?? [];
    if ($onlyUnread) {
        $notifs = array_filter($notifs, function($n){ return empty($n['read']); });
    }
    return $notifs;
}

function mark_notification_read($id) {
    if (isset($_SESSION['uc_notifications'][$id])) {
        $_SESSION['uc_notifications'][$id]['read'] = true;
    }
}

function set_flash($type, $message) {
    $_SESSION['uc_flash'][$type] = $message;
}

function get_flash($type) {
    if (isset($_SESSION['uc_flash'][$type])) {
        $msg = $_SESSION['uc_flash'][$type];
        unset($_SESSION['uc_flash'][$type]);
        return $msg;
    }
    return null;
}

// Mark notification read if notify parameter provided
if (isset($_GET['notify'])) {
    mark_notification_read($_GET['notify']);
}
