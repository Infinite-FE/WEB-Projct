<?php
require_once __DIR__ . '/../../config/db.php';

// Validate inputs
$category_id = $_POST['category_id'] ?? null;
$title = $_POST['title'] ?? null;
$description = $_POST['description'] ?? null;

if (!$category_id || !$title) {
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

try {
    // 1. Create the item
    $stmt = $pdo->prepare("INSERT INTO items (category_id, title, description) VALUES (?, ?, ?)");
    $stmt->execute([$category_id, $title, $description]);
    $item_id = $pdo->lastInsertId();

    // 2. If file uploaded, store in files table
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $filename = $_FILES['file']['name'];
        $filetype = $_FILES['file']['type'];
        $filesize = $_FILES['file']['size'];
        $filedata = file_get_contents($_FILES['file']['tmp_name']);

        $stmt = $pdo->prepare("INSERT INTO files (item_id, filename, filetype, filesize, filedata) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$item_id, $filename, $filetype, $filesize, $filedata]);
    }

    echo json_encode(["status" => "success", "message" => "Item created successfully", "item_id" => $item_id]);

} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
<!-- admin/items/add.php -->
<!-- This file handles the addition of new items in the admin panel. It can also handle file uploads associated with the item. using API -->