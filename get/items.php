<!-- // get/items.php -->
<?php
require_once __DIR__ . '/../config/db.php';

$category_id = $_GET['category_id'] ?? null;
if (!$category_id) {
    http_response_code(400);
    echo json_encode(["error" => "Missing category_id"]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM items WHERE category_id = ? ORDER BY created_at DESC");
    $stmt->execute([$category_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["status" => "success", "items" => $items]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch items."]);
}
?>