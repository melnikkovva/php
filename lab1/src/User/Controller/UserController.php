<?php
namespace App\User\Controller;

use App\Connection\Database;
use App\User\Model\UserTable;
use App\User\Model\Entity\User;
use RuntimeException;

class UserController
{
    private UserTable $userTable;

    public function __construct()
    {
        $pdo = Database::connectDatabase();
        $this->userTable = new UserTable($pdo);
    }

    public function showRegistrationForm(): void
    {
        require __DIR__ . '/../View/register.php';
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Method Not Allowed');
        }

        try {
            $avatarPath = $this->handleAvatarUpload();
            $user = $this->createUserFromRequest($_POST, $avatarPath);
            $userId = $this->userTable->save($user);
            
            header("Location: /user/{$userId}", true, 303);
            exit();
        } catch (RuntimeException $e) {
            http_response_code(400);
            echo "Error: " . self::escape($e->getMessage());
        }
    }

    public function showProfile(int $userId): void
    {
        try {
            $user = $this->userTable->find($userId);
            
            if (!$user) {
                throw new RuntimeException("User not found");
            }

            $viewData = [
                'user' => $user,
                'birthDate' => $user->getBirthDate() ? date('d.m.Y', strtotime($user->getBirthDate())) : 'Not specified',
                'gender' => match ($user->getGender()) {
                    'male' => 'Male',
                    'female' => 'Female',
                    default => 'Not specified'
                }
            ];

            extract($viewData);
            require __DIR__ . '/../View/show_user.php';
        } catch (RuntimeException $e) {
            http_response_code(404);
            echo "Error: " . self::escape($e->getMessage());
        }
    }

    private function handleAvatarUpload(): ?string
    {
        if (empty($_FILES['avatar']['tmp_name'])) {
            return null;
        }

        $uploadDir = __DIR__ . '/../../../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $avatarName = uniqid() . '_' . basename($_FILES['avatar']['name']);
        $targetPath = $uploadDir . $avatarName;

        if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $targetPath)) {
            throw new RuntimeException("Failed to upload avatar");
        }

        return '/uploads/' . $avatarName;
    }

    private function createUserFromRequest(array $data, ?string $avatarPath): User
    {
        $requiredFields = ['first_name', 'last_name', 'email'];
        foreach ($requiredFields as $field) {
            if (empty(trim($data[$field] ?? ''))) {
                throw new RuntimeException("Required field {$field} is missing");
            }
        }

        if (!filter_var(trim($data['email']), FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException("Invalid email format");
        }

        return new User(
            null,
            trim($data['first_name']),
            trim($data['last_name']),
            !empty($data['middle_name']) ? trim($data['middle_name']) : null,
            $data['gender'] ?? null,
            $data['birth_date'] ?? null,
            trim($data['email']),
            !empty($data['phone']) ? trim($data['phone']) : null,
            $avatarPath
        );
    }

    public static function escape(?string $value): string
    {
        return $value !== null ? htmlspecialchars($value, ENT_QUOTES, 'UTF-8') : 'Not specified';
    }
}