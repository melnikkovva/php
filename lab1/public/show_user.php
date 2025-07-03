<?php
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/user.php';

header('Content-Type: text/html; charset=utf-8');

try {
    if (!isset($_GET['user_id'])) {
        throw new InvalidArgumentException("Не указан ID пользователя");
    }
    
    $userId = (int)$_GET['user_id'];
    if ($userId <= 0) {
        throw new InvalidArgumentException("Некорректный ID пользователя");
    }
    
    $pdo = connectDatabase();
    $user = findUserInDatabase($pdo, $userId);
    
    if (!$user) {
        throw new RuntimeException("Пользователь не найден");
    }
    
    // Функция для безопасного вывода данных
    function safeOutput(?string $value): string
    {
        return $value !== null ? htmlspecialchars($value, ENT_QUOTES, 'UTF-8') : 'Не указано';
    }
    
    // Форматирование даты рождения
    $birthDate = $user->getBirthDate() ? date('d.m.Y', strtotime($user->getBirthDate())) : 'Не указана';
    
} catch (InvalidArgumentException $e) {
    http_response_code(400);
    die("Ошибка в данных: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
} catch (RuntimeException $e) {
    http_response_code(404);
    die("Ошибка: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
} catch (PDOException $e) {
    http_response_code(500);
    die("Ошибка базы данных: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Просмотр пользователя</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="user-container">
        <h1>Информация о пользователе</h1>
        
        <div class="user-info">
            <?php if ($user->getAvatarPath()): ?>
                <div class="user-avatar">
                    <img src="<?= safeOutput($user->getAvatarPath()) ?>" alt="Аватар пользователя">
                </div>
            <?php endif; ?>
            
            <div class="user-details">
                <p><strong>Имя:</strong> <?= safeOutput($user->getFirstName()) ?></p>
                <p><strong>Фамилия:</strong> <?= safeOutput($user->getLastName()) ?></p>
                <p><strong>Отчество:</strong> <?= safeOutput($user->getMiddleName()) ?></p>
                <p><strong>Пол:</strong> <?= safeOutput($user->getGender() === 'male' ? 'Мужской' : ($user->getGender() === 'female' ? 'Женский' : 'Не указан')) ?></p>
                <p><strong>Дата рождения:</strong> <?= safeOutput($birthDate) ?></p>
                <p><strong>Email:</strong> <?= safeOutput($user->getEmail()) ?></p>
                <p><strong>Телефон:</strong> <?= safeOutput($user->getPhone()) ?></p>
            </div>
        </div>
        
        <div class="user-actions">
            <a href="register.php" class="back-btn">Вернуться на главную</a>
        </div>
    </div>
</body>
</html>