
<!-- auth/register.php -->
<?php
require_once __DIR__ . '/../config/db.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
    http_response_code(400);
    echo json_encode(["error" => "Email and password required"]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(["error" => "Email already exists"]);
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $insert = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $insert->execute([$email, $hash]);

    echo json_encode(["status" => "success", "message" => "User registered"]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Registration failed"]);
}
?>