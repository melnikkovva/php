<?php
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/user.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    include 'index.html';
    exit;
}

header('Content-Type: text/html; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Разрешены только POST-запросы');
}

try {
    $user = new User(
        null, // ID будет null для нового пользователя
        trim($_POST['first_name'] ?? ''),
        trim($_POST['last_name'] ?? ''),
        !empty($_POST['middle_name']) ? trim($_POST['middle_name']) : null,
        $_POST['gender'] ?? '',
        $_POST['birth_date'] ?? null,
        trim($_POST['email'] ?? ''),
        !empty($_POST['phone']) ? trim($_POST['phone']) : null,
        null
    );

    foreach (USER_REQUIRED_FIELDS as $field) {
        $getter = 'get' . str_replace('_', '', ucwords($field, '_'));
        if (empty($user->$getter())) {
            throw new InvalidArgumentException("Обязательное поле не заполнено: " . htmlspecialchars($field));
        }
    }

    if (!filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException("Некорректный email");
    }

    if (!empty($_FILES['avatar']['tmp_name'])) {
        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $avatarName = uniqid() . '_' . basename($_FILES['avatar']['name']);
        $targetPath = $uploadDir . $avatarName;
        
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetPath)) {
            $user = new User(
                null,
                $user->getFirstName(),
                $user->getLastName(),
                $user->getMiddleName(),
                $user->getGender(),
                $user->getBirthDate(),
                $user->getEmail(),
                $user->getPhone(),
                '/uploads/' . $avatarName
            );
        } else {
            throw new RuntimeException("Ошибка загрузки аватара");
        }
    }

    $pdo = connectDatabase();
    $userId = saveUserToDatabase($pdo, $user);

    $redirectUrl = "show_user.php?user_id=$userId";
    header('Location: ' . $redirectUrl, true, 303);
    die();

} catch (InvalidArgumentException $e) {
    http_response_code(400);
    echo "Ошибка в данных: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
} catch (RuntimeException $e) {
    http_response_code(500);
    echo "Ошибка: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
} catch (PDOException $e) {
    http_response_code(500);
    echo "Ошибка базы данных: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}