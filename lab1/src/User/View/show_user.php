<?php
    use App\User\Controller\UserController;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../../public/styles/styles.css">
</head>
<body>
    <div class="user-container">
        <h1>Информация о пользователе</h1>
        
        <div class="user-info">
            <?php if ($user->getAvatarPath()): ?>
                <div class="user-avatar">
                    <img src="<?= UserController::escape($user->getAvatarPath()) ?>" alt="User Avatar">
                </div>
            <?php endif; ?>
            
            <div class="user-details">
                <p><strong>Имя:</strong> <?= UserController::escape($user->getFirstName()) ?></p>
                <p><strong>Фамилия:</strong> <?= UserController::escape($user->getLastName()) ?></p>
                <p><strong>Отчество:</strong> <?= UserController::escape($user->getMiddleName() ?? 'Not specified') ?></p>
                <p><strong>Пол:</strong> <?= UserController::escape($gender) ?></p>
                <p><strong>Дата рождения:</strong> <?= UserController::escape($birthDate) ?></p>
                <p><strong>Почта:</strong> <?= UserController::escape($user->getEmail()) ?></p>
                <p><strong>Телефон:</strong> <?= UserController::escape($user->getPhone() ?? 'Not specified') ?></p>
            </div>
        </div>
        
        <div class="user-actions">
            <a href="/users" class="btn">Вернуться к списку пользователей</a>
            <a href="/" class="btn">Вернуться на страницу регситрации</a>
            <form action="/user/<?= $user->getId() ?>/delete" method="post" class="inline-form">
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Удалить пользователя</button>
            </form>
        </div>

        <div class="edit-form">
            <h2>Изменить профиль</h2>
            <form action="/user/<?= $user->getId() ?>/update" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="first_name">Имя*:</label>
                    <input type="text" id="first_name" name="first_name" value="<?= UserController::escape($user->getFirstName()) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="last_name">Фамилия*:</label>
                    <input type="text" id="last_name" name="last_name" value="<?= UserController::escape($user->getLastName()) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="middle_name">Отчество:</label>
                    <input type="text" id="middle_name" name="middle_name" value="<?= UserController::escape($user->getMiddleName() ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label>Пол:</label>
                    <div class="radio-group">
                        <label><input type="radio" name="gender" value="male" <?= $user->getGender() === 'male' ? 'checked' : '' ?>> Male</label>
                        <label><input type="radio" name="gender" value="female" <?= $user->getGender() === 'female' ? 'checked' : '' ?>> Female</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="birth_date">Дата рождения:</label>
                    <input type="date" id="birth_date" name="birth_date" value="<?= UserController::escape($user->getBirthDate() ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Почта*:</label>
                    <input type="email" id="email" name="email" value="<?= UserController::escape($user->getEmail()) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Телефон:</label>
                    <input type="tel" id="phone" name="phone" value="<?= UserController::escape($user->getPhone() ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="avatar">Аватар</label>
                    <input type="file" id="avatar" name="avatar" accept="image/png,image/jpeg,image/gif">
                </div>
                
                <button type="submit" class="btn">Обновить профиль</button>
            </form>
        </div>
    </div>
</body>
</html>