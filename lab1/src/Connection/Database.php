<?php
namespace App\Connection;

require_once __DIR__ . '/../../config/db_config.php';

use PDO;
use PDOException;
use RuntimeException;

class Database
{
     /**
     * Устанавливает подключение к БД (Singleton)
     * @return PDO
     * @throws RuntimeException
     */
    public static function connectDatabase(): PDO
    {
        if (self::$instance === null) {
            $params = self::getConnectionParams();
            
            try {
                self::$instance = new PDO(
                    $params['dsn'],
                    $params['username'],
                    $params['password'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
            } catch (PDOException $e) {
                throw new RuntimeException("Database connection failed: " . $e->getMessage());
            }
        }
        
        return self::$instance;
    }
    private static ?PDO $instance = null;

    /**
     * Получает параметры подключения из конфига
     * @return array{dsn:string, username:string, password:string}
     */
    private static function getConnectionParams(): array
    {
        return [
            'dsn' => "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            'username' => DB_USER,
            'password' => DB_PASSWORD
        ];
    }

   

    /**
     * Закрываем возможность создания экземпляра класса
     */
    private function __construct() {}
    private function __clone() {}
    public function __wakeup() {}
}