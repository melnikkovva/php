<?php
declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use App\User\Controller\UserController;

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$userController = new UserController();

switch (true) {
    case $path === '/' || $path === '/register':
        $userController->showRegistrationForm();
        break;
        
    case $path === '/register/save' && $_SERVER['REQUEST_METHOD'] === 'POST':
        $userController->register();
        break;
        
    case preg_match('#^/user/(\d+)$#', $path, $matches):
        $userController->showProfile((int)$matches[1]);
        break;
        
    default:
        http_response_code(404);
        echo '404 Not Found';
}