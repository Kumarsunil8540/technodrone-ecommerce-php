<?php
if(session_status()===PHP_SESSION_NONE){
    session_start();
}
include("config.php");
if(isset($_POST['email'])){
    $email=$_POST['email'];
    $password=$_POST['password'];

    $get_admin = $conn->prepare("SELECT * FROM admin where email ='$email'");
    $get_admin->execute();

    $admins = $get_admin->fetch(PDO::FETCH_ASSOC);

    if($admins){
        if($admins['password']===$password){
            header('location:admin_dashboard.php');
            $_SESSION['admin_login']=true;
            $_SESSION['admin_name']=$admins['username'];


        }
        else {
            echo "Password wrong";
            echo "<p><a href='admin_login.php'>Go back to admin login page</a></p>";
           
        }
    }else {
        echo "Email not found";
        echo "<p><a href='admin_login.php'>Go back to admin login page</a></p>";
    }    
}
