<?php
session_start();
if(!isset($_SESSION['user_login']) || !$_SESSION['user_login']){
    header('Location: user_login.php?redirect=my_orders.php');
    exit;
}

include("config.php");
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Orders</title>
<link rel="stylesheet" href="my_orders.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
<div class="container">
<p><a href="user_dashbord.php" style="text-decoration:none"><-- Go Back User Deshboard</a></p>
<h1 class="page-title"><i class="fas fa-box"></i> My Orders</h1>


<!-- Section 1: Placed Orders -->
<section class="orders-section">
    <h2><i class="fas fa-check-circle"></i> Placed Orders</h2>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Component</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Order Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $conn->prepare("
                SELECT o.id as order_id, c.component_name, o.quantity, o.total_price, o.order_date
                FROM orders o
                JOIN components c ON o.component_id = c.id
                WHERE o.buyer_id = ?
                ORDER BY o.order_date DESC
            ");
            $stmt->execute([$user_id]);
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if($orders){
                foreach($orders as $order){
                    echo "<tr>
                        <td>{$order['order_id']}</td>
                        <td>{$order['component_name']}</td>
                        <td>{$order['quantity']}</td>
                        <td>₹".number_format($order['total_price'],2)."</td>
                        <td>{$order['order_date']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No orders yet!</td></tr>";
            }
            ?>
        </tbody>
    </table>
</section>

<!-- Section 2: Cart Items -->
<section class="cart-section">
    <h2><i class="fas fa-shopping-cart"></i> Cart Items</h2>
    <table>
        <thead>
            <tr>
                <th>Cart ID</th>
                <th>Component</th>
                <th>Quantity</th>
                <th>Added At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt_cart = $conn->prepare("
                SELECT ci.id as cart_id, c.component_name, c.id as component_id, ci.quantity, ci.added_at
                FROM cart_items ci
                JOIN components c ON ci.component_id = c.id
                WHERE ci.user_id = ?
                ORDER BY ci.added_at DESC
            ");
            $stmt_cart->execute([$user_id]);
            $cart_items = $stmt_cart->fetchAll(PDO::FETCH_ASSOC);

            if($cart_items){
                foreach($cart_items as $item){
                    echo "<tr>
                        <td>{$item['cart_id']}</td>
                        <td>{$item['component_name']}</td>
                        <td>{$item['quantity']}</td>
                        <td>{$item['added_at']}</td>
                        <td>
                            <a href='buy_now.php?component_id= {$item['component_id']}' class='btn-confirm'>Buy Now</a>
                            <a href='delete_comman.php?id={$item['cart_id']}&table=cart_items'  class='btn-remove'>Remove</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Cart is empty!</td></tr>";
            }
            ?>
        </tbody>
    </table>
</section>

</div>
</body>
</html>
