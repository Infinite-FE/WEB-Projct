<?php
header("Content-Type: application/json");
session_start();

// Extract and normalize the request path
$method = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];
$path = str_replace('/index.php', '', $requestUri);
$path = strtok($path, '?'); // remove query params
$path = rtrim($path, '/');

// Define all routes
$routes = [

    // 🔓 Public GET APIs
    'GET:/get/categories'         => 'get/categories.php',
    'GET:/get/items'              => 'get/items.php',
    'GET:/get/files'              => 'get/files.php',
    // 'GET:/get/file'               => 'get/file.php',
    // 'GET:/get/last-file-id'       => 'get/last-file-id.php',


    // 🔐 Auth APIs
    'POST:/auth/login'            => 'auth/login.php',
    'POST:/auth/logout'           => 'auth/logout.php',
    'POST:/auth/register'         => 'auth/register.php',
    'POST:/auth/reset'            => 'auth/reset.php',

    // 🛠️ Admin Category APIs
    'POST:/admin/categories/add'     => 'admin/categories/add.php',
    'POST:/admin/categories/delete'  => 'admin/categories/delete.php',

    // 🛠️ Admin Item APIs
    'POST:/admin/items/add'          => 'admin/items/add.php',
    'POST:/admin/items/edit'         => 'admin/items/edit.php',
    'POST:/admin/items/delete'       => 'admin/items/delete.php',

    // 📁 File APIs
    'POST:/admin/files/upload'       => 'admin/files/upload.php',
    'POST:/admin/files/delete'       => 'admin/files/delete.php',
    'POST:/admin/files/replace'      => 'admin/files/replace.php'
];

// Match and dispatch
$routeKey = $method . ':' . $path;

if (isset($routes[$routeKey])) {
    $fileToRequire = $routes[$routeKey];

    // Session protection for admin APIs (except auth)
    if (
        str_starts_with($fileToRequire, 'admin/') &&
        !in_array($fileToRequire, [
            'auth/login.php', 'auth/logout.php', 'auth/register.php', 'auth/reset.php'
        ])
    ) {
        require_once __DIR__ . '/config/sessionCheck.php';
    }

    require_once __DIR__ . '/' . $fileToRequire;
} else {
    http_response_code(404);
    echo json_encode(["error" => "API route not found", "path" => $path]);
}
?>
<!-- index.php -->
<!-- This is the main entry point for the API. It routes requests to the appropriate handlers based on the request method and path. -->