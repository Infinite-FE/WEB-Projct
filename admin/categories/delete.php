<?php
require_once __DIR__ . '/../../config/db.php';

$id = $_POST['id'] ?? '';
if (!$id) {
    http_response_code(400);
    echo json_encode(["error" => "Category ID is required"]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(["status" => "success", "message" => "Category deleted"]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error"]);
}
?>
<!-- admin/categories/delete.php -->
<!-- This file handles the deletion of categories in the admin panel. using API -->