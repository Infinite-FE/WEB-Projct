<!-- auth/reset.php -->
<?php
require_once __DIR__ . '/../config/db.php';

$email = $_POST['email'] ?? '';
$new_password = $_POST['new_password'] ?? '';

if (!$email || !$new_password) {
    http_response_code(400);
    echo json_encode(["error" => "Email and new password required"]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    if (!$stmt->fetch()) {
        http_response_code(404);
        echo json_encode(["error" => "User not found"]);
        exit;
    }

    $hash = password_hash($new_password, PASSWORD_DEFAULT);
    $update = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
    $update->execute([$hash, $email]);

    echo json_encode(["status" => "success", "message" => "Password updated"]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Password reset failed"]);
}
?>