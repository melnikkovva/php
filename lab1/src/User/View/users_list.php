<?php use App\User\Controller\UserController; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List</title>
    <link rel="stylesheet" href="../../public/styles/styles.css">
</head>
<body>
    <div class="users-container">
        <h1>Список пользователей</h1>
        <a href="/register" class="btn">Добавить нового пользователя</a>
        
        <table class="users-table">
            <thead>
                <tr>
                    <th>Имя</th>
                    <th>Почта</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td>
                        <a href="/user/<?= $user['user_id'] ?>">
                            <?= UserController::escape($user['last_name'] . ' ' . $user['first_name']) ?>
                        </a>
                    </td>
                    <td><?= UserController::escape($user['email']) ?></td>
                    <td>
                        <form action="/user/<?= $user['user_id'] ?>/delete" method="post" class="inline-form">
                            <button type="submit" class="btn-danger">Удалить</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>