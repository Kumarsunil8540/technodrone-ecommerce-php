<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['admin_login'])) { header('Location: admin_login.php'); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Add Admin - Techno Drone</title>
  <link rel="stylesheet" href="add_admin.css" />
</head>
<body>
  <main class="page-wrap">
    <header class="page-header">
      <h1>Add New Admin</h1>
      <p class="sub">Create an administrator account to manage the site.</p>
    </header>

    <section class="card">
      <!-- set action to your processing file, e.g. action="save_admin.php" -->
      <form id="addAdminForm" class="admin-form" action="" method="POST" enctype="multipart/form-data" novalidate>
         <div class="col">
            <label for="password">Admin Password</label>
            <input id="admin_password" name="admin_password" type="password" placeholder="Enter a strong password" minlength="6" required />
          </div>
        <div class="row">
          <label for="username">Full Name</label>
          <input id="username" name="username" type="text" placeholder="e.g. Sunil Kumar" required />
        </div>

        <div class="row two-col">
          <div class="col">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" placeholder="admin@example.com" required />
          </div>
          <div class="col">
            <label for="phone">Phone</label>
            <input id="phone" name="phone" type="tel" placeholder="+91 9xxxxxxxxx" required/>
          </div>
        </div>

        <div class="row two-col">
          <div class="col">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" placeholder="Enter a strong password" minlength="6" required />
          </div>
          <div class="col">
            <label for="confirm_password">Confirm Password</label>
            <input id="confirm_password" name="confirm_password" type="password" placeholder="Re-enter password" minlength="6" required />
          </div>
        </div>

        <div class="row actions">
          <button type="submit" class="btn primary">Create Admin</button>
          <a href="admin_dashboard.php" class="btn ghost">Back to Dashboard</a>
        </div>
      </form>
    </section>

    <footer class="page-footer">
      <small>Make sure passwords are strong. You can customize roles/permissions later.</small>
    </footer>
  </main>
</body>
</html>



<?php
include("config.php");

// Handle form submission
if (isset($_POST['admin_password'])) {
    $super_admin = "898989"; // super admin fixed password (string rakho)
    $admin_pass = $_POST['admin_password'];

    if ($super_admin === $admin_pass) {
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password === $confirm_password) {
            $name = $_POST['username'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];

            // Prepare insert query
            $stmt = $conn->prepare("INSERT INTO admin (username, password, email, phone, super_admin) VALUES (?, ?, ?, ?, ?)");
            $result = $stmt->execute([$name, $password, $email, $phone, $_SESSION['admin_name']]);

            if ($result) {
                echo "<p style='color:green;'>Admin added successfully ✅</p>";
                
            } else {
                echo "<p style='color:red;'>Something went wrong ❌</p>";
                
            }
        } else {
            echo "<p style='color:red;'>Passwords do not match ❌</p>";
            
        }
    } else {
        echo "<p style='color:red;'>Wrong super admin password ❌</p>";


    }
}
?>