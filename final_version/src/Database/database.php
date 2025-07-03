<?php

require_once __DIR__ . '/../config/db_config.php';

/**
 * @return array{dsn:string,username:string,password:string}
 */
function getConnectionParams(): array
{
    return [
        'dsn' => "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        'username' => DB_USER,
        'password' => DB_PASSWORD,
    ];
}

function connectDatabase(): PDO
{
    $params = getConnectionParams();
    $pdo = new PDO($params['dsn'], $params['username'], $params['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}