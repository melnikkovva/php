<?php
/** @var \App\Model\User $user */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
</head>
<body>
    <h1>User Profile</h1>
    <div>
        <p>ID: <?= htmlspecialchars((string)$user->getUserId()) ?></p>
        <p>First Name: <?= htmlspecialchars($user->getFirstName()) ?></p>
        <p>Last Name: <?= htmlspecialchars($user->getLastName()) ?></p>
        <p>Middle Name: <?= htmlspecialchars($user->getMiddleName() ?? '') ?></p>
        <p>Gender: <?= htmlspecialchars($user->getGender()) ?></p>
        <p>Birth Date: <?= htmlspecialchars($user->getBirthDate()->format('Y-m-d')) ?></p>
        <p>Email: <?= htmlspecialchars($user->getEmail()) ?></p>
        <p>Phone: <?= htmlspecialchars($user->getPhone() ?? '') ?></p>
        <?php if ($user->getAvatarPath()): ?>
            <img src="<?= htmlspecialchars($user->getAvatarPath()) ?>" alt="User Avatar">
        <?php endif; ?>
    </div>
</body>
</html>