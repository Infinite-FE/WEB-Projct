<?php
require_once __DIR__ . '/../config/db.php';

try {
    $stmt = $pdo->query("SELECT id FROM files ORDER BY id DESC LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        echo json_encode(["file_id" => $row['id']]);
    } else {
        echo json_encode(["file_id" => null]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to get last file ID"]);
}
?>