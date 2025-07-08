<?php
namespace App\User\Controller;

use App\Connection\Database;
use App\User\Model\UserTable;
use App\User\Model\Entity\User;
use RuntimeException;

class UserController
{
    private UserTable $userTable;
    private const ALLOWED_MIME_TYPES = ['image/png', 'image/jpeg', 'image/gif'];
    private const UPLOAD_DIR = __DIR__ . '/../../../uploads/';

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
            $user = $this->createUserFromRequest($_POST);
            $userId = $this->userTable->save($user);
            
            // Handle avatar upload after we have the user ID
            if (!empty($_FILES['avatar']['tmp_name'])) {
                $avatarPath = $this->handleAvatarUpload($userId);
                $this->userTable->updateAvatarPath($userId, $avatarPath);
            }
            
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

    private function handleAvatarUpload(int $userId): string
    {
        $file = $_FILES['avatar'];
        
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException("File upload error: " . $file['error']);
        }

        // Check MIME type
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $file['tmp_name']);
        finfo_close($fileInfo);

        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES, true)) {
            throw new RuntimeException("Invalid file type. Only PNG, JPEG, and GIF are allowed.");
        }

        // Create upload directory if it doesn't exist
        if (!is_dir(self::UPLOAD_DIR)) {
            mkdir(self::UPLOAD_DIR, 0755, true);
        }

        // Determine file extension based on MIME type
        $extension = match ($mimeType) {
            'image/png' => 'png',
            'image/jpeg' => 'jpg',
            'image/gif' => 'gif',
            default => throw new RuntimeException("Unsupported image type")
        };

        $filename = "avatar{$userId}.{$extension}";
        $targetPath = self::UPLOAD_DIR . $filename;

        // Remove old avatar if exists
        $this->removeOldAvatar($userId);

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new RuntimeException("Failed to save uploaded file");
        }

        return "/uploads/" . $filename;
    }

    private function removeOldAvatar(int $userId): void
    {
        $pattern = self::UPLOAD_DIR . "avatar{$userId}.*";
        $files = glob($pattern);
        
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
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
            null // Avatar path will be set after user creation
        );
    }

    public static function escape(?string $value): string
    {
        return $value !== null ? htmlspecialchars($value, ENT_QUOTES, 'UTF-8') : 'Not specified';
    }
}