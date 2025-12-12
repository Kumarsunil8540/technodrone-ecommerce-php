<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Techno Drone Robotics</title>
    <link rel="stylesheet" href="page_styles.css">
</head>
<body>
    <?php include("header.php"); ?>
    <?php include("head.php"); ?>

    <div class="auth_container">
        <h1>Create Account</h1>
        <form action="signup_process.php" method="POST" class="auth_form">
            <label for="name">Full Name</label>
            <input type="text" name="name" id="name" placeholder="Enter your full name" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="Enter your email" required>

            <label for="phone">Phone Number</label>
            <input type="text" name="phone" id="phone" placeholder="Enter your phone number" required >

            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Enter password" required>

            <button type="submit">Sign Up</button>
        </form>

        <p class="auth_link">Already have an account? <a href="user_login.php">Login here</a></p>
        <p class="auth_link"><a href="admin_login.php">Login as Admin</a></p>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>
