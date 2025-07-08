<?php
declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use App\User\Controller\UserController;

// Обработка ошибок
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    http_response_code(500);
    echo "Error: [$errno] $errstr in $errfile on line $errline";
    exit();
});

set_exception_handler(function($e) {
    http_response_code(500);
    echo "Error: " . htmlspecialchars($e->getMessage());
    exit();
});

// Получаем путь из URL
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Создаем контроллер
$userController = new UserController();

// Маршрутизация
switch (true) {
    case $path === '/' || $path === '/register':
        $userController->showRegistrationForm();
        break;
        
    case $path === '/register/save' && $method === 'POST':
        $userController->register();
        break;
        
    case preg_match('#^/user/(\d+)$#', $path, $matches):
        $userController->showProfile((int)$matches[1]);
        break;
        
    case preg_match('#^/user/(\d+)/update$#', $path, $matches) && $method === 'POST':
        $userController->update((int)$matches[1]);
        break;
        
    case preg_match('#^/user/(\d+)/delete$#', $path, $matches) && $method === 'POST':
        $userController->delete((int)$matches[1]);
        break;
        
    case $path === '/users':
        $userController->listUsers();
        break;
        
    default:
        http_response_code(404);
        echo '404 Not Found';
        exit();
}