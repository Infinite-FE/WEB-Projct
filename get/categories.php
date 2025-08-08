// get/categories.php
<?php
require_once __DIR__ . '/../config/db.php';

try {
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY created_at DESC");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["status" => "success", "categories" => $categories]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch categories."]);
}
?>