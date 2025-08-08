<!-- auth/login.php -->
<?php
require_once __DIR__ . '/../config/db.php';
session_start();

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
    http_response_code(400);
    echo json_encode(["error" => "Email and password required"]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        echo json_encode(["status" => "success", "message" => "Login successful"]);
    } else {
        http_response_code(403);
        echo json_encode(["error" => "Invalid credentials"]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Login failed"]);
}
?>
