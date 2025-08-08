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

$base = "http://localhost/index.php";

// Step 1: Add item without file
echo "\n📝 Add Item (no file):\n";
echo post("$base/admin/items/add", [
    'category_id' => 1,
    'title'       => 'Test Item No File',
    'description' => 'This is a file-less item'
]);

// Step 2: MANUAL STEP → Check DB for the new item_id
// Replace this with actual ID after checking in phpMyAdmin
$item_id = 5; // <-- REPLACE with actual ID from DB

// Step 3: Upload file for that existing item
if ($item_id) {
    echo "\n📤 Upload File for item_id=$item_id:\n";
    $file = curl_file_create(__DIR__ . '/temp.txt', 'text/plain', 'temp.txt');
    echo post("$base/admin/files/upload", [
        'item_id' => $item_id,
        'file'    => $file
    ]);
} else {
    echo "\n❌ No item_id provided for file upload.\n";
}
?>