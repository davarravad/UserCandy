<?php
require_once __DIR__ . '/../../core/auth.php';
$user = current_user();
if (!$user) {
    http_response_code(403);
    exit;
}
$id = intval($_POST['id'] ?? 0);
$field = $_POST['field'] ?? '';
$value = $_POST['value'] ?? '';
if (!$id || !in_array($field, ['email'])) {
    http_response_code(400);
    exit;
}
$stmt = $db->prepare("UPDATE users SET $field = ? WHERE id = ?");
$stmt->execute([$value, $id]);
header('Content-Type: application/json');
echo json_encode(['success' => true]);
