<?php
include("config.php");
session_start();

$user_id = $_SESSION['user_id'] ?? null;
$is_admin = $_SESSION['admin_login'] ?? false;

if(!isset($_GET['id']) || !isset($_GET['table'])){
    die("<p style='color:red;'>Missing ID or Table name.</p>");
}

$id = intval($_GET['id']);
$table = $_GET['table'];

$allowed_tables = ["courses","components", "applications", "orders","users","message","cart_items"];

// ----- Handle cart_items for users -----
if($table === "cart_items"){
    if(!$user_id){
        die("<p style='color:red;'>Please login first.</p>");
    }

    $stmt_check = $conn->prepare("SELECT * FROM cart_items WHERE id = :id AND user_id = :user_id");
    $stmt_check->execute([':id'=>$id, ':user_id'=>$user_id]);
    $item = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if(!$item){
        die("<p style='color:red;'>You cannot delete this item.</p>");
    }

    $stmt = $conn->prepare("DELETE FROM cart_items WHERE id = :id AND user_id = :user_id");
    $result = $stmt->execute([':id'=>$id, ':user_id'=>$user_id]);

    if($result){
        header("Location: my_orders.php?deleted=cart_item");
        exit();
    } else {
        echo "<p style='color:red;'>Failed to delete cart item.</p>";
    }

} else {
    // ----- Admin deletion -----
    if(!$is_admin){
        die("<p style='color:red;'>Unauthorized access!</p>");
    }

    if(!in_array($table, $allowed_tables)){
        die("Invalid table name!");
    }

    try {
        // Handle dependencies
        if($table === "components"){
            // पहले orders हटाओ
            $stmt = $conn->prepare("DELETE FROM orders WHERE component_id = :id");
            $stmt->execute([':id'=>$id]);
        }
        elseif($table === "users"){
            // पहले इस user के orders, addresses, cart_items, applications हटाओ
            $conn->prepare("DELETE FROM orders WHERE buyer_id = :id")->execute([':id'=>$id]);
            $conn->prepare("DELETE FROM user_addresses WHERE user_id = :id")->execute([':id'=>$id]);
            $conn->prepare("DELETE FROM cart_items WHERE user_id = :id")->execute([':id'=>$id]);
            $conn->prepare("DELETE FROM applications WHERE user_id = :id")->execute([':id'=>$id]);
        }
        elseif($table === "courses"){
            // course delete करने से पहले उससे जुड़े applications हटाओ
            $conn->prepare("DELETE FROM applications WHERE course = (SELECT course_name FROM courses WHERE id = :id)")->execute([':id'=>$id]);
        }

        // अब original delete
        $stmt = $conn->prepare("DELETE FROM $table WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $result = $stmt->execute();

        if($result){
            header("Location: admin_dashboard.php?deleted=$table");
            exit();
        } else {
            echo "<p style='color:red;'>Failed to delete record from $table.</p>";
        }

    } catch (PDOException $e) {
        echo "<p style='color:red;'>Delete failed: ".$e->getMessage()."</p>";
    }
}
?>
