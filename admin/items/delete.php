<?php
require_once __DIR__ . '/../../config/db.php';

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
}

$item_id = $_POST['id'] ?? null;

if (!$item_id) {
    http_response_code(400);
    echo json_encode(["error" => "Missing item id"]);
    exit;
}

try {
    $pdo->beginTransaction();

    // 1. Delete associated files
    $stmt = $pdo->prepare("DELETE FROM files WHERE item_id = ?");
    $stmt->execute([$item_id]);

    // 2. Delete the item
    $stmt = $pdo->prepare("DELETE FROM items WHERE id = ?");
    $stmt->execute([$item_id]);

    $pdo->commit();

    echo json_encode([
        "status" => "success",
        "message" => "Item and associated files deleted successfully"
    ]);
} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(["error" => "Failed to delete item", "details" => $e->getMessage()]);
}
?>
<!-- admin/items/delete.php -->
<!-- This file handles the deletion of items and their associated files in the admin panel using API -->