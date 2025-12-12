<?php
include("config.php");
if(isset($_POST['name'])){
    $name=$_POST['name'];
    $email=$_POST['email'];
    $phone_no=$_POST['phone'];
    $password=$_POST['password'];

    $check_data = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check_data->execute([$email]);
    $valid = $check_data->fetch(PDO::FETCH_ASSOC);


    if($valid){
        echo "This email is already registered.";
        echo "<p><a href='signup.php'>Go back to sign-up page</a></p>";
    } else {
        // Insert new user
        $insert_data = $conn->prepare("INSERT INTO users (name, email, password, phone) VALUES (?, ?, ?, ?)");
        $result = $insert_data->execute([$name, $email, $password, $phone_no]);

        if($result){
            echo "Your registration was successful.";
            echo "<p><a href='user_login.php'>Go to login page</a></p>";
        } else {
            echo "Something went wrong. Please try again.";
            echo "<p><a href='signup.php'>Go back to sign-up page</a></p>";

        }
    }


}