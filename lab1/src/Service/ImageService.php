<?php
declare(strict_types=1);

namespace App\Service;

use RuntimeException;

class ImageService
{
    private const ALLOWED_MIME_TYPES = ['image/png', 'image/jpeg', 'image/gif'];
    private const UPLOAD_DIR = __DIR__ . '/../../uploads/';

    public function handleAvatarUpload(array $file): string
    { 
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException("File upload error: " . $file['error']);
        }

        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $file['tmp_name']);
        finfo_close($fileInfo);

        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES, true)) {
            throw new RuntimeException("Invalid file type. Only PNG, JPEG, and GIF are allowed.");
        }

        if (!is_dir(self::UPLOAD_DIR)) {
            mkdir(self::UPLOAD_DIR, 0755, true);
        }

        $extension = match ($mimeType) {
            'image/png' => 'png',
            'image/jpeg' => 'jpg',
            'image/gif' => 'gif',
            default => throw new RuntimeException("Unsupported image type")
        };

        $filename = "avatar_{" . uniqid() . "}.{$extension}";
        $targetPath = self::UPLOAD_DIR . $filename;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new RuntimeException("Failed to save uploaded file");
        }

        return "/uploads/" . $filename;
    }

    public function deleteAvatar(?string $avatarPath): void
    {
        if ($avatarPath) {
            $fullPath = __DIR__ . '/../../../' . ltrim($avatarPath, '/');
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
    }
}