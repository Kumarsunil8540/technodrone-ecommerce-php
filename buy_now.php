<?php
// Session management
if(session_status()===PHP_SESSION_NONE){
    session_start();
}

// Login check
if(!isset($_SESSION['user_login']) || !$_SESSION['user_login']){
    $redirect_url = urlencode("buy_now.php?component_id=" . ($_GET['component_id'] ?? ''));
    header('location: user_login.php?redirect=' . $redirect_url);
    exit;
}

include("config.php"); 

// Component fetch
$component_id = isset($_GET['component_id']) ? intval($_GET['component_id']) : 0;

$stmt_comp = $conn->prepare("SELECT * FROM components WHERE id = ?");
$stmt_comp->execute([$component_id]);
$component = $stmt_comp->fetch(PDO::FETCH_ASSOC);

if(!$component){
    die("Component not found!");
}

$component_name = $component['component_name'];
$base_price = floatval($component['price']);
$stock = intval($component['stock']);

// Quantity handling
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
$quantity = max(1, min($quantity, $stock)); // ensure quantity within stock
$subtotal = $base_price * $quantity;
$shipping_fee = 100.00;
$packaging_fee = 50.00;
$total = $subtotal + $shipping_fee + $packaging_fee;

// Fetch user addresses
$user_id = $_SESSION['user_id'];
$stmt_addr = $conn->prepare("SELECT * FROM user_addresses WHERE user_id = ? ORDER BY is_default DESC, id DESC");
$stmt_addr->execute([$user_id]);
$addresses = $stmt_addr->fetchAll(PDO::FETCH_ASSOC);

// Select default address
$selected_address = null;
if(count($addresses) > 0){
    foreach($addresses as $addr){
        if($addr['is_default']){
            $selected_address = $addr;
            break;
        }
    }
    if(!$selected_address){
        $selected_address = $addresses[0];
    }
}

$user_full_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'] ?? '';
$user_phone = $_SESSION['user_phone'] ?? '';

if($selected_address){
    $user_address = $selected_address['address_line1'] . 
                    (!empty($selected_address['address_line2']) ? ', '.$selected_address['address_line2'] : '') . 
                    ', '.$selected_address['city'] . ', ' . $selected_address['state'] . ' - ' . $selected_address['zip_code'];
}else{
    $user_address = "No address set yet";
}

// ----- Handle Order Submission -----
// ----- Handle Order Submission -----
$order_success = false;
if(isset($_POST['place_order'])){
    $address_id = $_POST['address_id'] ?? 0;

    if(!$selected_address){
        $error_msg = "Please set an address before placing the order.";
    } else {
        // Insert order in orders table with address_id
        $stmt_insert = $conn->prepare("INSERT INTO orders 
            (buyer_id, component_id, address_id, quantity, total_price) 
            VALUES (:buyer_id, :component_id, :address_id, :quantity, :total_price)");

        $result = $stmt_insert->execute([
            ':buyer_id'     => $user_id,   // <-- session ka user id
            ':component_id' => $component_id,
            ':address_id'   => $address_id, // <-- ab address bhi save hoga
            ':quantity'     => $quantity,
            ':total_price'  => $total
        ]);

        if($result){
            $order_success = true;

            // Optional: stock update bhi karna hai to
            $new_stock = $stock - $quantity;
            $stmt_update_stock = $conn->prepare("UPDATE components SET stock = ? WHERE id = ?");
            $stmt_update_stock->execute([$new_stock, $component_id]);
        } else {
            $error_msg = "Something went wrong! Please try again.";
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout Summary | <?= $component_name ?></title>
<link rel="stylesheet" href="buy_now.css"> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
</head>
<body>

<div class="container main-content-padding">

    <div class="back-link-container">
        <a href="component_page.php" class="btn-go-back">
            <i class="fas fa-arrow-left"></i> Back to Product
        </a>
    </div>
    
    <h1 class="page-title"><i class="fas fa-shopping-bag"></i> Checkout Summary</h1>

    <div class="checkout-grid">

        <!-- SUMMARY BOXES -->
        <div class="checkout-summary-boxes">

            <!-- DELIVERY ADDRESS -->
            <div class="summary-box address-box">
                <div class="box-header">
                    <span class="box-step">1</span>
                    <h2 class="section-heading">Delivery Address</h2>
                    <a href="user_address.php?redirect=<?= urlencode("buy_now.php?component_id=" . $component_id) ?>" class="btn-change-edit">
                        <i class="fas fa-pencil-alt"></i> CHANGE
                    </a>
                </div>
                <div class="address-details">
                    <?php if($selected_address): ?>
                        <p class="user-name"><strong><?= htmlspecialchars($user_full_name) ?></strong></p>
                        <p class="user-info"><?= htmlspecialchars($user_address) ?></p>
                        <p class="user-info">Phone: <?= htmlspecialchars($user_phone) ?></p>
                    <?php else: ?>
                        <p>No address set. <a href="user_address.php">Add Address</a></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ORDER DETAILS -->
            <div class="summary-box item-box">
                <div class="box-header">
                    <span class="box-step">2</span>
                    <h2 class="section-heading">Your Order</h2>
                </div>

                <div class="order-item-detail">
                    <p class="item-name"><?= htmlspecialchars($component_name) ?></p>
                    <p class="item-price-unit">Price: ₹<?= number_format($base_price, 2) ?></p>
                    <p class="item-stock">Available Stock: <?= $stock ?></p>
                </div>

                <form method="POST" class="quantity-form">
                    <div class="form-group quantity-group">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" min="1" max="<?= $stock ?>" value="<?= $quantity ?>" onchange="this.form.submit()">
                        <input type="hidden" name="component_id" value="<?= $component_id ?>">
                        <span class="recalc-info">Update to recalculate</span>
                    </div>
                </form>
            </div>

            <!-- CALL TO CONFIRM -->
            <div class="summary-box payment-box">
                <div class="box-header">
                    <span class="box-step">3</span>
                    <h2 class="section-heading">Order Confirmation</h2>
                </div>

                <?php if($order_success): ?>
                    <p class="success-msg">✅ Your order has been placed! Our team will call you shortly to confirm the order and payment details.</p>
                <?php else: ?>
                    <?php if(isset($error_msg)) echo "<p class='error-msg'>⚠️ ".htmlspecialchars($error_msg)."</p>"; ?>
                    <form method="POST">
                        <input type="hidden" name="component_id" value="<?= $component_id ?>">
                        <input type="hidden" name="quantity" value="<?= $quantity ?>">
                        <input type="hidden" name="address_id" value="<?= $selected_address['id'] ?? 0 ?>">
                        <button type="submit" name="place_order" class="place-order-btn">
                            ✅ Confirm Order (₹<?= number_format($total, 2) ?>)
                        </button>
                    </form>
                <?php endif; ?>
            </div>

        </div>

        <!-- PRICE SUMMARY -->
        <div class="price-summary-sticky">
            <h2 class="sticky-heading">Price Details</h2>
            <div class="price-breakdown">
                <div class="price-line">
                    <span>Price (<?= $quantity ?> item)</span>
                    <span>₹<?= number_format($subtotal, 2) ?></span>
                </div>
                <div class="price-line">
                    <span>Delivery Charges</span>
                    <span>₹<?= number_format($shipping_fee, 2) ?></span>
                </div>
                <div class="price-line">
                    <span>Packaging Fee</span>
                    <span>₹<?= number_format($packaging_fee, 2) ?></span>
                </div>
                <div class="total-line-sticky">
                    <span class="total-label">Amount Payable</span>
                    <span class="total-price">₹<?= number_format($total, 2) ?></span>
                </div>
            </div>
        </div>

    </div>
</div>
</body>
</html>
