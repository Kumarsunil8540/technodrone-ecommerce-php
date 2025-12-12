<?php
if(session_status()===PHP_SESSION_NONE){
    session_start();
}
if(!isset($_SESSION['user_login']) || !$_SESSION['user_login']){
    header('location: user_login.php');
    exit;
}

include("config.php");
$user_id = $_SESSION['user_id']; // user_id session me save hona chahiye login ke time

// ADD NEW ADDRESS
if(isset($_POST['add_address'])){
    $line1 = $_POST['address_line1'];
    $line2 = $_POST['address_line2'];
    $city  = $_POST['city'];
    $state = $_POST['state'];
    $zip   = $_POST['zip_code'];
    $is_default = isset($_POST['is_default']) ? 1 : 0;

    // agar user default set karta hai to pehle saare default = 0
    if($is_default){
        $stmt = $conn->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = ?");
        $stmt->execute([$user_id]);
    }

    $stmt = $conn->prepare("INSERT INTO user_addresses 
        (user_id, address_line1, address_line2, city, state, zip_code, is_default)
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $line1, $line2, $city, $state, $zip, $is_default]);
}

// DELETE ADDRESS
if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM user_addresses WHERE id = ? AND user_id = ?");
    $stmt->execute([$delete_id, $user_id]);
}

// SET DEFAULT
if(isset($_GET['default'])){
    $default_id = $_GET['default'];
    // pehle sab default 0
    $stmt = $conn->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = ?");
    $stmt->execute([$user_id]);
    // fir select address default = 1
    $stmt = $conn->prepare("UPDATE user_addresses SET is_default = 1 WHERE id = ? AND user_id = ?");
    $stmt->execute([$default_id, $user_id]);
}

// FETCH ALL ADDRESSES
$stmt = $conn->prepare("SELECT * FROM user_addresses WHERE user_id = ? ORDER BY is_default DESC, id DESC");
$stmt->execute([$user_id]);
$addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Addresses | User Dashboard</title>
    <link rel="stylesheet" href="user_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <style>
        .address-card { border:1px solid #ccc; padding:15px; margin:10px 0; border-radius:5px; position:relative; }
        .address-card.default { border:2px solid green; }
        .address-actions { position:absolute; top:10px; right:10px; }
        .address-actions a { margin-left:10px; color:#333; text-decoration:none; }
        .address-actions a.default { color:green; font-weight:bold; }
        .add-address-form { margin-bottom:20px; border:1px solid #ccc; padding:15px; border-radius:5px; }
        .add-address-form input, .add-address-form select { width:100%; padding:8px; margin:5px 0; }
        .add-address-form label { font-weight:bold; }
        .btn { padding:8px 15px; cursor:pointer; border:none; border-radius:3px; }
        .btn-primary { background:#2980b9; color:#fff; }
        .btn-danger { background:#e74c3c; color:#fff; }
    </style>
</head>
<body>
<div class="dashboard-container">
    <aside class="sidebar">
        <div class="user-info">
            <h3><?php echo htmlspecialchars($_SESSION['user_name']); ?></h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="component_page.php" class="tab-link shop-link"><i class="fas fa-store"></i> Shop Components</a></li>
            <li><a href="user_dashbord.php" class="tab-link"><i class="fas fa-user-circle"></i> Dashboard</a></li>
            <li><a href="user_address.php" class="tab-link active"><i class="fas fa-map-marker-alt"></i> My Addresses</a></li>
            <li><a href="logout.php" class="tab-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <h2>My Addresses</h2>

        <!-- ADD NEW ADDRESS FORM -->
        <div class="add-address-form">
            <form method="POST" action="">
                <label for="address_line1">Address Line 1</label>
                <input type="text" id="address_line1" name="address_line1" required>

                <label for="address_line2">Address Line 2</label>
                <input type="text" id="address_line2" name="address_line2">

                <label for="city">City</label>
                <input type="text" id="city" name="city" required>

                <label for="state">State</label>
                <input type="text" id="state" name="state" required>

                <label for="zip_code">Zip Code</label>
                <input type="text" id="zip_code" name="zip_code" required>

                <label>
                    <input type="checkbox" name="is_default"> Set as default
                </label><br><br>

                <button type="submit" name="add_address" class="btn btn-primary">Add Address</button>
            </form>
        </div>

        <!-- LIST OF ADDRESSES -->
        <?php foreach($addresses as $addr): ?>
        <div class="address-card <?php echo $addr['is_default'] ? 'default' : ''; ?>">
            <p><?php echo htmlspecialchars($addr['address_line1']); ?>, <?php echo htmlspecialchars($addr['address_line2']); ?></p>
            <p><?php echo htmlspecialchars($addr['city']); ?>, <?php echo htmlspecialchars($addr['state']); ?> - <?php echo htmlspecialchars($addr['zip_code']); ?></p>

            <div class="address-actions">
                <?php if(!$addr['is_default']): ?>
                    <a href="?default=<?php echo $addr['id']; ?>" class="default"><i class="fas fa-check-circle"></i> Set Default</a>
                <?php else: ?>
                    <span class="default"><i class="fas fa-check-circle"></i> Default</span>
                <?php endif; ?>
                <a href="?delete=<?php echo $addr['id']; ?>" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</a>
            </div>
        </div>
        <?php endforeach; ?>
    </main>
</div>
</body>
</html>
