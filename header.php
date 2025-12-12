<?php 
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
$display_login = "flex"; // show login/signup by default
$display_user = "none";  // hide user info by default

if(isset($_SESSION['user_login']) && $_SESSION['user_login']){
    $display_login = "none";
    $display_user = "flex";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="header.css">
</head>
<body>
    <header>
        <div class="head_div">
            <div class="logo border"></div>
            <div class="contact_no">+91 8989846072</div>
            <div class="social_media">
                <i class="fa-brands fa-facebook border"></i>
                <a href="https://www.instagram.com/himanshu_technorobotics?igsh=MTFjcDM3bjBqbWtqNg==" style="text-decoration:none"><i class="fa-brands fa-instagram border"></i></a>
                <i class="fa-brands fa-twitter border"></i>
            </div>
            <div class="manu_div" style="display:<?php echo $display_login; ?>;">
                <div class="login_btn"><a href="user_login.php" style='text-decoration:none'>Login</a></div>
                <div class="sign_up_btn"><a href="signup.php" style='text-decoration:none'>Sign Up</a></div>
            </div>

            <div class="manu_div" style="display:<?php echo $display_user; ?>;">
                <div class="login_btn">
                    <a href="user_dashbord.php" style='text-decoration:none'><?php echo $_SESSION['user_name']; ?></a>
                </div>
            </div>

        </div>
    </header>
</body>
</html>