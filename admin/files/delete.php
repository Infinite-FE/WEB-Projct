<?php
require_once __DIR__ . '/../../config/db.php';

$file_id = $_POST['file_id'] ?? null;
if (!$file_id) {
    http_response_code(400);
    echo json_encode(["error" => "Missing file_id"]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM files WHERE id = ?");
    $stmt->execute([$file_id]);

    echo json_encode(["status" => "success", "message" => "File deleted"]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error", "details" => $e->getMessage()]);
}
?>
<!-- admin/files/delete.php -->
<!-- This file handles the deletion of files associated with items in the admin panel. using API -->