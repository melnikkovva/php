<?php

require_once __DIR__ . '/../vendor/autoload.php';

$pdo = connectDatabase();
$userTable = new App\Database\UserTable($pdo);
$controller = new App\Controller\UserController($userTable);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    require __DIR__ . '/../src/View/register.php';
    exit;
}

try {
    // Проверка обязательных полей
    $requiredFields = USER_REQUIRED_FIELDS;
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            throw new \InvalidArgumentException("Field $field is required");
        }
    }

    $userData = [
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'middle_name' => $_POST['middle_name'] ?? null,
        'gender' => $_POST['gender'],
        'birth_date' => $_POST['birth_date'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'] ?? null,
        'avatar_path' => null,
    ];

    // Handle file upload
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $fileName = uniqid() . '_' . basename($_FILES['avatar']['name']);
        $targetPath = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetPath)) {
            $userData['avatar_path'] = '/uploads/' . $fileName;
        }
    }

    $userId = $controller->register($userData);
    $redirectUrl = "/show_user.php?user_id=$userId";
    header('Location: ' . $redirectUrl, true, 303);
    exit;
} catch (\InvalidArgumentException $e) {
    http_response_code(400);
    echo "Validation error: " . htmlspecialchars($e->getMessage());
} catch (Exception $e) {
    http_response_code(500);
    echo "Error: " . htmlspecialchars($e->getMessage());
}