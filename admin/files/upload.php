<?php
require_once __DIR__ . '/../../config/db.php';

$item_id = $_POST['item_id'] ?? null;

if (!$item_id || empty($_FILES['file'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing item_id or file"]);
    exit;
}

try {
    $tmpPath  = $_FILES['file']['tmp_name'];
    $filename = $_FILES['file']['name'];
    $filetype = mime_content_type($tmpPath);
    $filesize = filesize($tmpPath);
    $filedata = file_get_contents($tmpPath);

    $stmt = $pdo->prepare("
        INSERT INTO files (item_id, filename, filetype, filesize, filedata, uploaded_at)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    $stmt->execute([$item_id, $filename, $filetype, $filesize, $filedata]);

    echo json_encode([
        "status" => "success",
        "message" => "File uploaded",
        "file_id" => $pdo->lastInsertId()
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error", "details" => $e->getMessage()]);
}
?>
<!-- admin/files/upload.php -->
<!-- This file handles the upload of files associated with items in the admin panel. using API -->