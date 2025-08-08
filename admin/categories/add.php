<?php
require_once __DIR__ . '/../../config/db.php';

$name = $_POST['name'] ?? '';
if (!$name) {
    http_response_code(400);
    echo json_encode(["error" => "Category name is required"]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->execute([$name]);
    echo json_encode(["status" => "success", "message" => "Category added"]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error"]);
}
?>
<!-- admin/categories/add.php -->
<!-- This file handles the addition of categories in the admin panel. using API -->