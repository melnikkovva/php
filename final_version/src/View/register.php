<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
    <style>
        .required:after {
            content: " *";
            color: red;
        }
    </style>
</head>
<body>
    <h1>Register New User</h1>
    <form method="post" action="/register.php" enctype="multipart/form-data">
        <div>
            <label for="first_name" class="required">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>
        </div>
        <div>
            <label for="last_name" class="required">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>
        </div>
        <div>
            <label for="middle_name">Middle Name:</label>
            <input type="text" id="middle_name" name="middle_name">
        </div>
        <div>
            <label for="gender" class="required">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
        </div>
        <div>
            <label for="birth_date" class="required">Birth Date:</label>
            <input type="date" id="birth_date" name="birth_date" required>
        </div>
        <div>
            <label for="email" class="required">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone">
        </div>
        <div>
            <label for="avatar">Avatar:</label>
            <input type="file" id="avatar" name="avatar">
        </div>
        <button type="submit">Register</button>
    </form>
</body>
</html>