<?php 
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

if(!$_SESSION['user_login']){
  header('location: user_login.php');
}
include("config.php");
if(isset($_GET['id'])){
    $component_id=$_GET['id'];
    $user_id=$_SESSION['user_id'];

    $stmt_cart=$conn->prepare("INSERT INTO cart_items(user_id,component_id,quantity) VALUES(?,?,?)");
    $result=$stmt_cart->execute([$user_id,$component_id,1]);

    if($result){
        header('Location: component_page.php');
        exit;
    }
    

}