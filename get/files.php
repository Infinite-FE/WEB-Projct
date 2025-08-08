<?php
    require_once __DIR__ . '/../config/db.php';

    $item_id = $_GET['item_id'] ?? null;
    if (!$item_id) {
        http_response_code(400);
        echo json_encode(["error" => "Missing item_id"]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT id, filename, filetype, filesize, uploaded_at FROM files WHERE item_id = ?");
        $stmt->execute([$item_id]);
        $files = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(["status" => "success", "files" => $files]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to fetch files"]);
    }
?>
<!-- get/files.php -->
<!-- This file retrieves files associated with a specific item in the admin panel. using API -->