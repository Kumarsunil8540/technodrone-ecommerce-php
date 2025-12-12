<?php
if(session_status()===PHP_SESSION_NONE){
  session_start();

}
if(!$_SESSION['admin_login']){
  header('location: admin_login.php');

}
include("config.php");
$user_name=$_SESSION['admin_name'];

$stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
$stmt->execute([$user_name]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC); // single row
$email = $admin['email'];
$phone = $admin['phone'];




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Admin Profile - Techno Drone</title>
    <link rel="stylesheet" href="admin_profile_update.css">
</head>
<body>
    <main class="page-wrap">
        <header class="page-header">
            <h1>Update Profile</h1>
            <p class="sub">Modify your profile information and password.</p>
        </header>

        <section class="card">
            <form id="profileForm" method="POST" action="">
                
                <label for="username">Full Name</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['admin_name']);?>" required>

                <label for="email">Email</label>
                <input type="email" id="email"  name="email" value="<?php echo htmlspecialchars($email); ?>"required>

                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone);?>" required>

                <label for="password">New Password</label>
                <input type="password" id="password" name="password" placeholder="Enter new password">

                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter password">

                <div class="row actions">
                    <button type="submit" name="update_profile" class="btn primary">Save Changes</button>
                    <a href="admin_dashboard.php" class="btn ghost">Back to Dashboard</a>
                </div>

                <div class="message">
                    <!-- PHP messages will be displayed here -->
                </div>

            </form>
        </section>
    </main>
</body>
</html>

<!-- here start update admin data in database -->

<?php 
if(isset($_POST['username'])){
    $password_new=$_POST['password'];
    $confirm_pass=$_POST['confirm_password'];
    if($password_new===$confirm_pass){
        $name_new=$_POST['username'];
        $email_new=$_POST['email'];
        $phone_new=$_POST['phone'];
        

        $stmt_new=$conn->prepare("update admin set
        username='$name_new',
        password='$password_new',
        email='$email_new',
        phone='$phone_new'
        where username='$user_name'");
        $result=$stmt_new->execute();

        if ($result) { 
            echo "<p style='color:green;'>update successfully ✅</p>";
                
        } else {
            echo "<p style='color:red;'>Something went wrong ❌</p>";
                
        }
    }else {
            echo "<p style='color:red;'>Passwords do not match ❌</p>";
            
        }


}

?>

