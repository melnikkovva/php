<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="../../public/styles/styles.css">
</head>
<body>
    <div class="registration-container">
        <h1>User Registration</h1>
        
        <form action="/register/save" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="first_name">First Name*:</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>
            
            <div class="form-group">
                <label for="last_name">Last Name*:</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>
            
            <div class="form-group">
                <label for="middle_name">Middle Name:</label>
                <input type="text" id="middle_name" name="middle_name">
            </div>
            
            <div class="form-group">
                <label>Gender:</label>
                <div class="radio-group">
                    <label><input type="radio" name="gender" value="male"> Male</label>
                    <label><input type="radio" name="gender" value="female"> Female</label>
                </div>
            </div>
            
            <div class="form-group">
                <label for="birth_date">Birth Date:</label>
                <input type="date" id="birth_date" name="birth_date">
            </div>
            
            <div class="form-group">
                <label for="email">Email*:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone">
            </div>
            
            <div class="form-group">
                <label for="avatar">Avatar:</label>
                <input type="file" id="avatar" name="avatar" accept="image/png">
            </div>
            
            <button type="submit" class="submit-btn">Register</button>
        </form>
    </div>
</body>
</html>