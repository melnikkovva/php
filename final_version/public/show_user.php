<?php

require_once __DIR__ . '/../vendor/autoload.php';

$pdo = connectDatabase();
$userTable = new App\Database\UserTable($pdo);
$controller = new App\Controller\UserController($userTable);

$userId = (int)$_GET['user_id'];
$user = $controller->show($userId);

if (!$user) {
    http_response_code(404);
    echo "User not found";
    exit;
}

require __DIR__ . '/../src/View/show_user.php';