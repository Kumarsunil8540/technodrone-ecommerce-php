<?php
if(session_start()===PHP_SESSION_NONE){
    session_start();
}

include("config.php");
if(isset($_POST['email'])){
    $email= $_POST['email'];
    $password=$_POST['password'];
    
    $get_users = $conn->prepare("SELECT * FROM users where email='$email' ");
    $get_users->execute();

    $users = $get_users->fetch(PDO::FETCH_ASSOC);

    if($users){
        if($users['password']===$password){
            header('location:user_dashbord.php');
            $_SESSION['user_login']=true;
            $_SESSION['user_name']=$users['name'];
            $_SESSION['user_email']=$users['email'];
            $_SESSION['user_id']=$users['id'];
        } 
        else{
            echo "Password wrong";
            echo "<p><a href='user_login.php'>Go back to login page</a></p>";

        }
    }else {
        echo "Email not found";
        echo "<p><a href='user_login.php'>Go back to login page</a></p>";
    }
}