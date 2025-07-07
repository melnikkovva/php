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
        <h1>User Information</h1>
        
        <div class="user-info">
            <?php if ($user->getAvatarPath()): ?>
                <div class="user-avatar">
                    <img src="<?= UserController::escape($user->getAvatarPath()) ?>" alt="User Avatar">
                </div>
            <?php endif; ?>
            
            <div class="user-details">
                <p><strong>First Name:</strong> <?= UserController::escape($user->getFirstName()) ?></p>
                <p><strong>Last Name:</strong> <?= UserController::escape($user->getLastName()) ?></p>
                <p><strong>Middle Name:</strong> <?= UserController::escape($user->getMiddleName()) ?></p>
                <p><strong>Gender:</strong> <?= UserController::escape($gender) ?></p>
                <p><strong>Birth Date:</strong> <?= UserController::escape($birthDate) ?></p>
                <p><strong>Email:</strong> <?= UserController::escape($user->getEmail()) ?></p>
                <p><strong>Phone:</strong> <?= UserController::escape($user->getPhone()) ?></p>
            </div>
        </div>
        
        <div class="user-actions">
            <a href="/" class="back-btn">Back to Home</a>
        </div>
    </div>
</body>
</html>