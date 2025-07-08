<?php
declare(strict_types=1);

namespace App\User\Controller;

use App\Connection\Database;
use App\User\Model\UserTable;
use App\User\Model\Entity\User;
use App\Service\ImageService;
use RuntimeException;

class UserController
{
    private UserTable $userTable;
    private ImageService $imageService;

    public function __construct()
    {
        $pdo = Database::connectDatabase();
        $this->userTable = new UserTable($pdo);
        $this->imageService = new ImageService();
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
            $user = $this->createUserFromRequest($_POST);
            
            if (!empty($_FILES['avatar']['tmp_name'])) {
                $avatarPath = $this->imageService->handleAvatarUpload($_FILES['avatar']);
                $user->setAvatarPath($avatarPath);
            }
            
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

    public function update(int $userId): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Method Not Allowed');
        }

        try {
            $user = $this->userTable->find($userId);
            if (!$user) {
                throw new RuntimeException("User not found");
            }

            $user->setFirstName(trim($_POST['first_name']));
            $user->setLastName(trim($_POST['last_name']));
            $user->setMiddleName(!empty($_POST['middle_name']) ? trim($_POST['middle_name']) : null);
            $user->setGender($_POST['gender'] ?? null);
            $user->setBirthDate($_POST['birth_date'] ?? null);
            $user->setEmail(trim($_POST['email']));
            $user->setPhone(!empty($_POST['phone']) ? trim($_POST['phone']) : null);

            if (!empty($_FILES['avatar']['tmp_name'])) {
                $this->imageService->deleteAvatar($user->getAvatarPath());
                $avatarPath = $this->imageService->handleAvatarUpload($_FILES['avatar']);
                $user->setAvatarPath($avatarPath);
            }

            $this->userTable->update($user);
            
            header("Location: /user/{$userId}", true, 303);
            exit();
        } catch (RuntimeException $e) {
            http_response_code(400);
            echo "Error: " . self::escape($e->getMessage());
        }
    }

    public function delete(int $userId): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Method Not Allowed');
        }

        try {
            $user = $this->userTable->find($userId);
            if ($user) {
                $this->imageService->deleteAvatar($user->getAvatarPath());
                $this->userTable->delete($userId);
            }
            
            header("Location: /users", true, 303);
            exit();
        } catch (RuntimeException $e) {
            http_response_code(400);
            echo "Error: " . self::escape($e->getMessage());
        }
    }

    public function listUsers(): void
    {
        try {
            $users = $this->userTable->getAll();
            require __DIR__ . '/../View/users_list.php';
        } catch (RuntimeException $e) {
            http_response_code(500);
            echo "Error: " . self::escape($e->getMessage());
        }
    }

    private function createUserFromRequest(array $data): User
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
            null
        );
    }

    public static function escape(?string $value): string
    {
        return $value !== null ? htmlspecialchars($value, ENT_QUOTES, 'UTF-8') : 'Not specified';
    }
}