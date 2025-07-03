<?php

namespace App\Database;

use App\Model\User;
use PDO;

class UserTable
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(User $user): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO `user` 
            (`first_name`, `last_name`, `middle_name`, `gender`, `birth_date`, `email`, `phone`, `avatar_path`)
            VALUES (:first_name, :last_name, :middle_name, :gender, :birth_date, :email, :phone, :avatar_path)'
        );

        $stmt->execute([
            ':first_name' => $user->getFirstName(),
            ':last_name' => $user->getLastName(),
            ':middle_name' => $user->getMiddleName(),
            ':gender' => $user->getGender(),
            ':birth_date' => $user->getBirthDate()->format('Y-m-d H:i:s'),
            ':email' => $user->getEmail(),
            ':phone' => $user->getPhone(),
            ':avatar_path' => $user->getAvatarPath(),
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function find(int $userId): ?User
    {
        $stmt = $this->pdo->prepare(
            'SELECT `user_id`, `first_name`, `last_name`, `middle_name`, `gender`, `birth_date`, `email`, `phone`, `avatar_path`
            FROM `user`
            WHERE `user_id` = :user_id'
        );

        $stmt->execute([':user_id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new User(
            (int)$row['user_id'],
            $row['first_name'],
            $row['last_name'],
            $row['middle_name'],
            $row['gender'],
            new \DateTime($row['birth_date']),
            $row['email'],
            $row['phone'],
            $row['avatar_path']
        );
    }
}