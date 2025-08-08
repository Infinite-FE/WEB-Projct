<?php

function post($url, $data) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
    curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

function get($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

$base = "http://localhost/index.php";

// 📨 Register
echo "\n📨 Register:\n";
echo post("$base/auth/register", [
    'email' => 'testuser@example.com',
    'password' => 'test1234'
]);
echo "<br><br>";
// 🔐 Login
echo "\n🔐 Login:\n";
echo post("$base/auth/login", [
    'email' => 'testuser@example.com',
    'password' => 'test1234'
]);
echo "<br><br>";
// 📁 Add Category
echo "\n📁 Add Category:\n";
echo post("$base/admin/categories/add", [
    'name' => 'Test Category'
]);
echo "<br><br>";
// 📤 Upload File
echo "\n📤 Upload File:\n";
$file = curl_file_create(__DIR__ . '/temp.txt', 'application/txt', 'temp.txt');
echo post("$base/admin/files/upload", [
    'file' => $file
]);
echo "<br><br>";
// 🆕 Get Last File ID
echo "\n📥 Get Last File ID:\n";
$fileIdResponse = get("$base/get/last-file-id");
$fileData = json_decode($fileIdResponse, true);
$file_id = $fileData['file_id'] ?? null;

// 📝 Add Item
if ($file_id) {
    echo "\n📝 Add Item:\n";
    echo post("$base/admin/items/add", [
        'category_id' => 1,
        'title' => 'Test Item',
        'description' => 'This is a test item',
        'image_path' => 'images/AI.jpg',
        'file_id' => $file_id // Use 'null' to test conversion
    ]);
} else {
    echo "\n❌ Skipped Add Item: No file_id found\n";
}
echo "<br><br>";
// 📦 Get Categories
echo "\n📦 Get Categories:\n";
echo get("$base/get/categories");
echo "<br><br>";
// 📦 Get Items (for category_id 1)
echo "\n📦 Get Items:\n";
echo get("$base/get/items?category_id=1");
echo "<br><br>";
// 📦 Get Files
echo "\n📦 Get Files:\n";
echo get("$base/get/files?id=$file_id");
echo "<br><br>";
?>
