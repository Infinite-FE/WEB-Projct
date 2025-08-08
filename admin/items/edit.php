<?php
require_once __DIR__ . '/../../config/db.php';

$id = $_POST['id'] ?? '';
$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$image_path = $_POST['image_path'] ?? '';
$file_id = $_POST['file_id'] ?? null;

if (!$id || !$title || !$description) {
    http_response_code(400);
    echo json_encode(["error" => "Required fields missing"]);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE items SET title = ?, description = ?, image_path = ?, file_id = ? WHERE id = ?");
    $stmt->execute([$title, $description, $image_path, $file_id, $id]);
    echo json_encode(["status" => "success", "message" => "Item updated"]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error"]);
}
?>
<!-- admin/items/edit.php -->
<!-- This file handles the editing of items in the admin panel. using API -->