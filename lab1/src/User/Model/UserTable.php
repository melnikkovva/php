<?php
declare(strict_types=1);
namespace App\User\Model;

use App\User\Model\Entity\User;
use PDO;
use PDOException;
use RuntimeException;

class UserTable
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(User $user): int
    {
        $sql = "INSERT INTO `user` (`first_name`, `last_name`, `middle_name`, `gender`, `birth_date`, `email`, `phone`, `avatar_path`)
                VALUES (:first_name, :last_name, :middle_name, :gender, :birth_date, :email, :phone, :avatar_path)";

        try {
            $stmt = $this->pdo->prepare($sql);
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
            
            return (int)$this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new RuntimeException("Failed to save user to database: " . $e->getMessage());
        }
    }

    public function find(int $userId): ?User
    {
        $sql = "SELECT `user_id`, `first_name`, `last_name`, `middle_name`, `gender`, `birth_date`, `email`, `phone`, `avatar_path`
                FROM `user`
                WHERE `user_id` = :user_id";

        try {
            $stmt = $this->pdo->prepare($sql);
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
}