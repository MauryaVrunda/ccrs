<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>citizen login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h2>Citizen Login</h2>
        <form action="login.php" method="POST">
            <label for="user_id">User ID</label>
            <input type="number" id="user_id" name="user_id" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
        <div class="login-options">
            <p><a href="register.html">Sign Up</a> | <a href="cforgot_password.html">Forgot Password?</a></p>
        </div>
    </div>
</body>