<?php
if(session_status()===PHP_SESSION_NONE){
    session_start();
}
if(!isset($_SESSION['user_login']) || !$_SESSION['user_login']){
    header('location: user_login.php');
    exit;
}

include("config.php");
$user_name = $_SESSION['user_name'];

$stmt = $conn->prepare("SELECT * FROM users WHERE name = ?");
$stmt->execute([$user_name]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$email = $user['email'];
$phone = $user['phone'];
$id    = $user['id'];
$password = $user['password']; // old password
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | <?php echo htmlspecialchars($user_name); ?></title>
    <link rel="stylesheet" href="user_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" /> 
</head>
<body>
<div class="dashboard-container">

    <aside class="sidebar">
        <div class="user-info">
            <h3><?php echo htmlspecialchars($user_name); ?></h3>
            <p><?php echo htmlspecialchars($email); ?></p>
        </div>

        <ul class="sidebar-menu">
            <li><a href="component_page.php" class="tab-link shop-link"><i class="fas fa-store"></i> Shop Components</a></li>
            <div class="menu-divider"></div>
            
            <li><a href="#" class="tab-link active" data-tab="profile"><i class="fas fa-user-circle"></i> Personal Info</a></li>
            <li><a href="user_address.php" class="tab-link" data-tab="address"><i class="fas fa-map-marker-alt"></i> My Addresses</a></li>
            <li><a href="my_orders.php" class="tab-link" data-tab="orders"><i class="fas fa-box-open"></i> My Orders</a></li>
            <li><a href="#" class="tab-link" data-tab="courses"><i class="fas fa-book-open"></i> My Courses</a></li>
            <li><a href="my_orders.php" class="tab-link" data-tab="components"><i class="fas fa-cog"></i> My Components</a></li>
            
            <li class="logout-link-item"><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <section id="profile" class="section-box tab-content active">
            <h2><i class="fas fa-user-circle"></i> Personal Information</h2>
            <form class="profile-form" method="post" action="">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user_name); ?>">
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email_new" value="<?php echo htmlspecialchars($email); ?>" >
                </div>

                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
                </div>

                <div class="form-group">
                    <label for="password">New Password:</label>
                    <input type="password" id="password" name="password" placeholder="Enter new password">
                </div>

                <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Save Changes</button>
            </form>
        </section>
    </main>
</div>
</body>
</html>

<?php
if(isset($_POST['email_new'])){
    $name_new  = $_POST['name'];
    $email_new = $_POST['email_new'];
    $phone_new = $_POST['phone'];

    // अगर user ने नया password डाला है तो वही लो, वरना पुराना password रखो
    if(!empty($_POST['password'])){
        $password_new = $_POST['password'];
    } else {
        $password_new = $password;
    }

    $stmt_new = $conn->prepare("UPDATE users SET 
        name = :name,
        email = :email,
        phone = :phone,
        password = :password
        WHERE id = :id");

    $result = $stmt_new->execute([
        ':name' => $name_new,
        ':email' => $email_new,
        ':phone' => $phone_new,
        ':password' => $password_new,
        ':id' => $id
    ]);

    if($result){
        header('location:user_login.php');
    } else {
        echo "<p style='color:red;'>Something went wrong ❌</p>";
    }
}
?>
