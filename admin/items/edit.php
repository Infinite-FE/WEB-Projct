<?php
require_once __DIR__ . '/../../config/db.php';

$item_id = $_POST['item_id'] ?? null;
$category_id = $_POST['category_id'] ?? null;
$title = $_POST['title'] ?? null;
$description = $_POST['description'] ?? null;

if (!$item_id || !$category_id || !$title) {
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

try {
    // Update item details
    $stmt = $pdo->prepare("UPDATE items SET category_id = ?, title = ?, description = ? WHERE id = ?");
    $stmt->execute([$category_id, $title, $description, $item_id]);

    // If file uploaded, replace existing
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        // Remove old files for this item
        $pdo->prepare("DELETE FROM files WHERE item_id = ?")->execute([$item_id]);

        // Insert new file
        $filename = $_FILES['file']['name'];
        $filetype = $_FILES['file']['type'];
        $filesize = $_FILES['file']['size'];
        $filedata = file_get_contents($_FILES['file']['tmp_name']);

        $stmt = $pdo->prepare("INSERT INTO files (item_id, filename, filetype, filesize, filedata) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$item_id, $filename, $filetype, $filesize, $filedata]);
    }

    echo json_encode(["status" => "success", "message" => "Item updated successfully"]);

} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
<!-- admin/items/edit.php -->
<!-- This file handles the editing of existing items in the admin panel. It can also handle file uploads associated with the item. using API -->