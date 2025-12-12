<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Techno Drone Robotics</title>
    <link rel="stylesheet" href="page_styles.css">
</head>
<body>
    <?php include("header.php"); ?>
    <?php include("head.php"); ?>

    <div class="auth_container">
        <h1>Login</h1>
        <form action="user_login_process.php" method="POST" class="auth_form">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="Enter your email" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Enter password" required>

            <button type="submit">Login</button>
        </form>

        <p class="auth_link">Don’t have an account? <a href="signup.php">Sign up here</a></p>
        <p class="auth_link"><a href="admin_login.php">Login as Admin</a></p>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>
