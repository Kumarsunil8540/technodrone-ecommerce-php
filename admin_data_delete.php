<?php
if(session_status()===PHP_SESSION_NONE){
  session_start();

}
if(!$_SESSION['admin_login']){
  header('location: admin_login.php');

}?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Admin</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; display:flex; justify-content:center; align-items:center; height:100vh;">

<form action="" method="post" style="background-color: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.2); width: 300px; text-align:center;">
    <h2 style="margin-bottom:20px; color:#333;">Delete Admin</h2>

    <label for="password" style="display:block; text-align:left; margin-bottom:5px; font-weight:bold;">Enter Super Admin Password</label>
    <input type="password" name="password" placeholder="Enter password" required 
           style="width:100%; padding:10px; margin-bottom:20px; border-radius:5px; border:1px solid #ccc;">

    <button type="submit" style="width:100%; padding:10px; border:none; background-color:#e74c3c; color:white; font-weight:bold; border-radius:5px; cursor:pointer;">
        Delete
    </button>
</form>

</body>
</html>



<?php
include("config.php");

if(isset($_POST['password'])){
    $super_admin_pass = "898989";
    $password = $_POST['password'];

    if($super_admin_pass === $password){
        if(isset($_GET['id'])){
            $admin_id = $_GET['id'];

            $stmt = $conn->prepare("DELETE FROM admin WHERE id=:id");
            $stmt->bindParam(':id', $admin_id, PDO::PARAM_INT);
            $result = $stmt->execute();

            if($result){
                header("Location: admin_dashboard.php");
                exit();
            } else {
                echo "<p style='color:red;'>Failed to delete admin.</p>";
            }
        } else {
            echo "<p style='color:red;'>Admin ID missing.</p>";
        }
    } else {
        echo "<p style='color:red;'>Wrong super admin password!</p>";
    }
}
?>

