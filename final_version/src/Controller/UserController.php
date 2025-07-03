<?php

namespace App\Controller;

use App\Database\UserTable;
use App\Model\User;

class UserController
{
    private UserTable $userTable;

    public function __construct(UserTable $userTable)
    {
        $this->userTable = $userTable;
    }

    public function register(array $userData): int
    {
        // Проверка обязательных полей
        foreach (USER_REQUIRED_FIELDS as $field) {
            if (empty($userData[$field])) {
                throw new \InvalidArgumentException("Field $field is required");
            }
        }

        $user = new User(
            null,
            $userData['first_name'],
            $userData['last_name'],
            $userData['middle_name'] ?? null,
            $userData['gender'],
            new \DateTime($userData['birth_date']),
            $userData['email'],
            $userData['phone'] ?? null,
            $userData['avatar_path'] ?? null
        );

        return $this->userTable->save($user);
    }

    public function show(int $userId): ?User
    {
        return $this->userTable->find($userId);
    }
}