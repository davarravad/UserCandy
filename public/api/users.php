<?php
require_once __DIR__ . '/../../core/auth.php';
$user = current_user();
if (!$user) {
    http_response_code(403);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}
$stmt = $db->query('SELECT id,email FROM users');
header('Content-Type: application/json');
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
