// get/files.php
<?php
require_once __DIR__ . '/../config/db.php';

$file_id = $_GET['id'] ?? null;
if (!$file_id) {
    http_response_code(400);
    echo json_encode(["error" => "Missing file id"]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT filename, filetype, filedata FROM files WHERE id = ?");
    $stmt->execute([$file_id]);
    $file = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$file) {
        http_response_code(404);
        echo json_encode(["error" => "File not found"]);
        exit;
    }

    header("Content-Type: {$file['filetype']}");
    header("Content-Disposition: attachment; filename=\"{$file['filename']}\"");
    echo $file['filedata'];
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch file."]);
}
?>