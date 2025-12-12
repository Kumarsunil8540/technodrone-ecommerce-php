<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="contact.css">


</head>
<body>
    <?php include("header.php"); ?>
    <?php include("head.php"); ?>

    <div class="page_container">
        <h1>Contact Us</h1>
        <p>If you have any questions, feel free to contact us!</p>

        <form action="" method="POST" class="apply_form">
            <label for="name">Full Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="phone">Phone Number:</label>
            <input type="text" name="phone" id="phone" required>

            <label for="message">Message:</label>
            <textarea name="message" id="message" rows="4" placeholder="Your message..." required></textarea>

            <button type="submit">Send Message</button>
        </form>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>

<?php
include("config.php");
if(isset($_POST['name'])){
    $name=$_POST['name'];
    $email=$_POST['email'];
    $phone=$_POST['phone'];
    $message=$_POST['message'];

    $sand_message=$conn->prepare("INSERT INTO `message` (`name`,`email`,`phone`, `message`) VALUES('$name','$email','$phone','$message')");
    $result_sand=$sand_message->execute();

    if($result_sand){
        header('location: index.php');
    }
    else{
        echo "something is wrong.";
        echo "<p><a href='contact.php'>GO back</a></p>";
    }
}


?>