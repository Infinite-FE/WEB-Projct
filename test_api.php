<?php

// function post($url, $data) {
//     $ch = curl_init($url);
//     curl_setopt($ch, CURLOPT_POST, true);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//     curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
//     curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
//     $response = curl_exec($ch);
//     curl_close($ch);
//     return $response;
// }

// function get($url) {
//     $ch = curl_init($url);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
//     $response = curl_exec($ch);
//     curl_close($ch);
//     return $response;
// }

// $base = "http://localhost/index.php";

// // 📨 Register
// echo "\n📨 Register:\n";
// echo post("$base/auth/register", [
//     'email' => 'testuser@example.com',
//     'password' => 'test1234'
// ]);
// echo "<br><br>";

// // 🔐 Login
// echo "\n🔐 Login:\n";
// echo post("$base/auth/login", [
//     'email' => 'testuser@example.com',
//     'password' => 'test1234'
// ]);
// echo "<br><br>";

// // 📁 Add Category
// echo "\n📁 Add Category:\n";
// echo post("$base/admin/categories/add", [
//     'name' => 'Test Category'
// ]);
// echo "<br><br>";

// // 📝 Add Item
// echo "\n📝 Add Item:\n";
// $itemResponse = post("$base/admin/items/add", [
//     'category_id' => 1,
//     'title' => 'Test Item',
//     'description' => 'This is a test item'
// ]);
// echo $itemResponse;
// echo "<br><br>";

// // Extract item_id from Add Item response
// $itemData = json_decode(trim($itemResponse), true);
// $item_id = isset($itemData['item_id']) ? (int)$itemData['item_id'] : null;

// // 📤 Upload File for this Item (optional)
// if ($item_id) {
//     echo "\n📤 Upload File:\n";
//     $file = curl_file_create(__DIR__ . 'temp.txt', 'application/txt', 'temp.txt');
//     echo post("$base/admin/files/upload", [
//         'item_id' => $item_id,
//         'file' => $file
//     ]);
// } else {
//     echo "\n❌ Skipped File Upload: No item_id returned from Add Item\n";
// }
// echo "<br><br>";

// // 📦 Get Categories
// echo "\n📦 Get Categories:\n";
// echo get("$base/get/categories");
// echo "<br><br>";

// // 📦 Get Items (for category_id 1)
// echo "\n📦 Get Items:\n";
// echo get("$base/get/items?category_id=1");
// echo "<br><br>";

// // 📦 Get Files (for this item_id)
// if ($item_id) {
//     echo "\n📦 Get Files:\n";
//     echo get("$base/get/files?item_id=$item_id");
// } else {
//     echo "\n❌ Skipped Get Files: No item_id found\n";
// }
// echo "<br><br>";
// ?> 

<?php

function post($url, $data, $filePath = null) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    if ($filePath) {
        $data['file'] = curl_file_create($filePath, mime_content_type($filePath), basename($filePath));
    }

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
echo "\n\n🔐 Login:\n";
echo post("$base/auth/login", [
    'email' => 'testuser@example.com',
    'password' => 'test1234'
]);
echo "<br><br>";
// 📁 Add Category
echo "\n\n📁 Add Category:\n";
echo post("$base/admin/categories/add", [
    'name' => 'Test Category'
]);
echo "<br><br>";
// 📝 Add Item WITH File
echo "\n\n📝 Add Item (with file):\n";
$itemResponse = post("$base/admin/items/add", [
    'category_id' => 1,
    'title' => 'Test Item',
    'description' => 'This is a test item with a file'
], __DIR__ . '/temp.txt');

$itemData = json_decode($itemResponse, true);
$item_id = $itemData['item_id'] ?? null;
echo "<br><br>";
// 📦 Get Items
echo "\n\n📦 Get Items:\n";
echo get("$base/get/items?category_id=1");

// ✏️ Edit Item (replace file)
if ($item_id) {
    echo "\n\n✏️ Edit Item (replace file):\n";
    echo post("$base/admin/items/edit", [
        'item_id' => $item_id,
        'category_id' => 1,
        'title' => 'Updated Test Item',
        'description' => 'Updated description'
    ], __DIR__ . '/temp2.txt');
} else {
    echo "\n\n❌ Skipped Edit Item: No item_id\n";
}
echo "<br><br>";
// 📦 Get Files for this Item
if ($item_id) {
    echo "\n\n📦 Get Files for Item:\n";
    echo get("$base/get/files?item_id=$item_id");
}
echo "<br><br>";
// 🗑 Delete Item
if ($item_id) {
    echo "\n\n🗑 Delete Item:\n";
    echo post("$base/admin/items/delete", [
        'item_id' => $item_id
    ]);
}
echo "<br><br>";
// ✅ Final Items List
echo "\n\n✅ Final Items List:\n";
echo get("$base/get/items?category_id=1");
?><!-- This script tests the API endpoints for user registration, login, category and item management, and file handling. It uses cURL to send requests and outputs the responses. -->
<!-- This script tests the API endpoints for user registration, login, category and item management, and file handling. It uses cURL to send requests and outputs the responses. -->
