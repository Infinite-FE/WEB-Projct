<?php
require_once __DIR__ . '/../../config/db.php';

$category_id = $_POST['category_id'] ?? null;
$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$image_path = $_POST['image_path'] ?? '';
$file_id = $_POST['file_id'] ?? null;

// Convert empty or 'null' string to real NULL
if ($file_id === '' || $file_id === 'null') {
    $file_id = null;
}

if (!$category_id || !$title) {
    http_response_code(400);
    echo json_encode(["error" => "Category and title are required"]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO items (category_id, title, description, image_path, file_id)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$category_id, $title, $description, $image_path, $file_id]);

    echo json_encode(["status" => "success", "message" => "Item added"]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error"]);
}
?>
