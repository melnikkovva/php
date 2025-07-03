<?php
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/user.php';

/**
 * @return array{dsn:string, username:string, password:string}
 */
function getConnectionParams(): array
{
    return [
        'dsn' => "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        'username' => DB_USER,
        'password' => DB_PASSWORD
    ];
}

/**
 * @return PDO
 * @throws RuntimeException
 */
function connectDatabase(): PDO
{
    $params = getConnectionParams();
    
    try {
        $pdo = new PDO(
            $params['dsn'],
            $params['username'],
            $params['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        throw new RuntimeException("Database connection failed: " . $e->getMessage());
    }
}

/**
 * @param PDO $pdo
 * @param User $user
 * @return int ID нового пользователя
 * @throws RuntimeException
 */
function saveUserToDatabase(PDO $pdo, User $user): int
{
    $sql = "INSERT INTO `user` (`first_name`, `last_name`, `middle_name`, `gender`, `birth_date`, `email`, `phone`, `avatar_path`)
            VALUES (:first_name, :last_name, :middle_name, :gender, :birth_date, :email, :phone, :avatar_path)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':first_name' => $user->getFirstName(),
            ':last_name' => $user->getLastName(),
            ':middle_name' => $user->getMiddleName(),
            ':gender' => $user->getGender(),
            ':birth_date' => $user->getBirthDate(),
            ':email' => $user->getEmail(),
            ':phone' => $user->getPhone(),
            ':avatar_path' => $user->getAvatarPath(),
        ]);
        
        return (int)$pdo->lastInsertId();
    } catch (PDOException $e) {
        throw new RuntimeException("Failed to save user to database: " . $e->getMessage());
    }
}

/**
 * @param PDO $pdo
 * @param int $userId
 * @return User|null
 * @throws RuntimeException
 */
function findUserInDatabase(PDO $pdo, int $userId): ?User
{
    $sql = "SELECT `user_id`, `first_name`, `last_name`, `middle_name`, `gender`, `birth_date`, `email`, `phone`, `avatar_path`
            FROM `user`
            WHERE `user_id` = :user_id";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $userData = $stmt->fetch();
        
        if (!$userData) {
            return null;
        }
        
        return new User(
            $userData['user_id'],
            $userData['first_name'],
            $userData['last_name'],
            $userData['middle_name'],
            $userData['gender'],
            $userData['birth_date'],
            $userData['email'],
            $userData['phone'],
            $userData['avatar_path']
        );
    } catch (PDOException $e) {
        throw new RuntimeException("Failed to find user in database: " . $e->getMessage());
    }
}