<?php
require_once __DIR__ . '/../../config/db.php';

$file_id = $_POST['file_id'] ?? null;
if (!$file_id || empty($_FILES['file'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing file_id or file"]);
    exit;
}

try {
    $tmpPath  = $_FILES['file']['tmp_name'];
    $filename = $_FILES['file']['name'];
    $filetype = mime_content_type($tmpPath);
    $filesize = filesize($tmpPath);
    $filedata = file_get_contents($tmpPath);

    $stmt = $pdo->prepare("
        UPDATE files SET filename=?, filetype=?, filesize=?, filedata=?, uploaded_at=NOW()
        WHERE id=?
    ");
    $stmt->execute([$filename, $filetype, $filesize, $filedata, $file_id]);

    echo json_encode(["status" => "success", "message" => "File updated"]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error", "details" => $e->getMessage()]);
}
?>
<!-- admin/files/replace.php -->
<!-- This file handles the replacement of files associated with items in the admin panel. using API -->