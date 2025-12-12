<?php
if(session_status()===PHP_SESSION_NONE){
  session_start();

}
if(!$_SESSION['admin_login']){
  header('location: admin_login.php');
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - Techno Drone Robotics</title>
<link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>

<header class="admin-header">
  <h1>Admin Dashboard</h1>
  <div class="admin-profile">
    <span>Welcome, <?php echo $_SESSION['admin_name'];?> </span>
    <a href="logout.php" class="logout-btn">Logout</a>
  </div>
</header>

<div class="admin-container">

  <!-- Sidebar -->
    <aside class="sidebar">
      <ul>
        <li><a href="#dashboard" class="active">🏠 Dashboard</a></li>
        <li><a href="#users">👥 Users</a></li>
        <li><a href="#admin">👥Admins</a></li>
        <li><a href="#courses">📚 Courses</a></li>
        <li><a href="#components">🔧 Components</a></li>
        <li><a href="#messages">✉️ Messages</a></li>
        <li><a href="admin_profile_update.php?id=<?php echo $_SESSION['admin_name']; ?>" >⚙️ Settings</a></li>

      </ul>
    </aside>

  <!-- Main Content -->
  <main class="main-content">
    
    <!-- Dashboard Overview -->
    <section id="dashboard" class="section">
      <h2>Overview</h2>
      <div class="cards">
        <div class="card">
          <h3>Total Admin</h3>
          <p><?php include 'config.php';      require 'Counter.php';  $counter = new Counter($conn); echo $counter->getCount('admin')?></p>
        </div>
        <div class="card">
          <h3>Total Users</h3>
          <p><?php include 'config.php';       $counter = new Counter($conn); echo $counter->getCount('users')?></p>
        </div>
        <div class="card">
          <h3>Courses</h3>
          <p><?php include 'config.php';       $counter = new Counter($conn); echo $counter->getCount('courses')?></p>
        </div>
        <div class="card">
          <h3>Applications Course</h3>
          <p><?php include 'config.php';       $counter = new Counter($conn); echo $counter->getCount('applications')?></p>
        </div>
        <div class="card">
          <h3>Components</h3>
          <p><?php include 'config.php';       $counter = new Counter($conn); echo $counter->getCount('components')?></p>
        </div>
        <div class="card">
          <h3>Orders</h3>
          <p><?php include 'config.php';       $counter = new Counter($conn); echo $counter->getCount('orders')?></p>
        </div>
        <div class="card">
          <h3>Messages</h3>
          <p><?php include 'config.php';       $counter = new Counter($conn); echo $counter->getCount('message')?></p>
        </div>
      </div>
    </section>

    <!-- Users Section -->
    <section id="users" class="section">
      <h2>Manage Users</h2>
      <form method="post">
        <input type="text" name="search_user" placeholder="Search Users..." class="search-box" 
              value="<?php echo isset($_POST['search_user']) ? htmlspecialchars($_POST['search_user']) : ''; ?>">
        <button type="submit"style="width:100px; padding:10px; border:none; background-color:#e74c3c; color:white; font-weight:bold; border-radius:5px; cursor:pointer;">Search</button>
      </form>

      <table class="manage-table">
        <thead>
          <tr><th>ID</th><th>Name</th><th>Email</th><th>Number</th><th>Create Time</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php
        include("config.php")  ;
        if(isset($_POST['search_user'])&& $_POST['search_user']!=''){
          $search=$_POST['search_user'];
          $stmt=$conn->prepare("SELECT * FROM users where name like '%$search%'");
          $stmt->execute();
        }
        else{
          $stmt=$conn->prepare("SELECT *FROM users");
          $stmt->execute();
        }
        $users = $stmt->fetchAll();

        foreach($users as $user){
            echo "<tr>";
            echo "<td>";
            echo $user['id'];
            echo "</td>";
            echo "<td>";
            echo $user['name'];
            echo "</td>";
            echo "<td>";
            echo $user['email'];
            echo "</td>";
            echo "<td>";
            echo $user['phone'];
            echo "</td>";
            echo "<td>";
            echo $user['created_at'];
            echo "</td>";
            echo "<td>";
            echo "<a href='delete_comman.php?id={$user['id']}&table=users' class='delete-btn' onclick='return confirm(\"Delete?\")'>Delete</a>";
            echo "</td>";
            echo "</tr>";
            
            
        }
        ?>
        </tbody>
      </table>
    </section>


    <!-- Admin Section -->
    <section id="admin" class="section">
      <h2>Manage Admin</h2>
      <button class="add-btn"><a href="add_admin.php">Add New User</a></button>
      <table class="manage-table">
        <thead>
          <tr><th>ID</th><th>UserName</th><th>Phone No.</th><th>Email</th><th>Super Admin</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php
          include("admin_table_data.php");
        ?>
        </tbody>
      </table>
    </section>

    <!-- Courses Section -->
    <section id="courses" class="section">
      <h2>Manage Courses</h2>
      <button class="add-btn"><a href="add_courses.php">Add New Course</a></button>
      <form method="post">
        <input type="text" name="search_course" placeholder="Search Course..." class="search-box" 
              value="<?php echo isset($_POST['search_course']) ? htmlspecialchars($_POST['search_course']) : ''; ?>">
        <button type="submit"style="width:100px; padding:10px; border:none; background-color:#e74c3c; color:white; font-weight:bold; border-radius:5px; cursor:pointer;">Search</button>
      </form>
      <table class="manage-table">
        <thead>
          <tr><th>Course Name</th><th>Price</th><th>Duration </th><th>instructor</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php
        include("config.php")  ;
        
        if(isset($_POST['search_course']) && $_POST['search_course']!=''){
          $search=$_POST['search_course'];
          $stmt_c=$conn->prepare("SELECT * FROM courses WHERE course_name LIKE :search");
          $stmt_c->execute([':search' => "%$search%"]);
        }
        else{
          $stmt_c=$conn->prepare("SELECT * FROM courses");
          $stmt_c->execute();
        }
        $courses = $stmt_c->fetchAll();

        foreach($courses as $course){
          echo "<tr>";
          echo "<td>";
          echo $course['course_name'];
          echo "</td>";
          echo "<td>";
          echo $course['price'];
          echo "</td>";
          echo "<td>";
          echo $course['duration'];
          echo "</td>";
          echo "<td>";
          echo $course['instructor'];
          echo "</td>";
          echo "<td>";
          echo "<a href='edit_course.php?id={$course['id']}' class='edit-btn'>Edit</a>";
          echo "<a href='delete_comman.php?id={$course['id']}&table=courses' class='delete-btn' onclick='return confirm(\"Delete?\")'>Delete</a>";
          echo "</td>";
          echo "</tr>";
          
          
        }
        ?>
        </tbody>
      </table>
    </section>


    <!-- Manage Applications Section -->
    <section id="applications" class="section">
      <h2>Manage Application for Courses</h2>
      <form method="post">
        <input type="text" name="search_application" placeholder="Search Applications..." class="search-box"
              value="<?php echo isset($_POST['search_application']) ? htmlspecialchars($_POST['search_application']) : ''; ?>">
        <button type="submit" style="width:100px; padding:10px; border:none; background-color:#e74c3c; color:white; font-weight:bold; border-radius:5px; cursor:pointer;">Search</button>
      </form>
      <table class="manage-table">
        <thead>
          <tr><th>ID</th><th>Name</th><th>Email</th><th>Phone No.</th><th>Course</th><th>Message</th><th>Applied at</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php
          if(isset($_POST['search_application']) && $_POST['search_application']!=''){
              $search = $_POST['search_application'];
              $stmt = $conn->prepare("SELECT * FROM applications WHERE name LIKE :search OR email LIKE :search OR course LIKE :search");
              $stmt->execute([':search'=>"%$search%"]);
          } else {
              $stmt = $conn->prepare("SELECT * FROM applications");
              $stmt->execute();
          }
          $applications = $stmt->fetchAll();
          foreach($applications as $app){
              echo "<tr>";
              echo "<td>".$app['id']."</td>";
              echo "<td>".$app['name']."</td>";
              echo "<td>".$app['email']."</td>";
              echo "<td>".$app['phone']."</td>";
              echo "<td>".$app['course']."</td>";
              echo "<td>".$app['message']."</td>";
              echo "<td>".$app['applied_at']."</td>";
              echo "<td><a href='delete_comman.php?id={$app['id']}&table=applications' class='delete-btn' onclick='return confirm(\"Delete?\")'>Delete</a></td>";
              echo "</tr>";
          }
          ?>
        </tbody>
      </table>
    </section>
  

    <!-- Components Section -->
    <section id="components" class="section">
      <h2>Manage Components</h2>
      <button class="add-btn"><a href="add_component.php">Add New Component</a></button>
      <form method="post">
        <input type="text" name="search_component" placeholder="Search Components..." class="search-box"
           value="<?php echo isset($_POST['search_component']) ? htmlspecialchars($_POST['search_component']) : ''; ?>">
        <button type="submit" style="width:100px; padding:10px; border:none; background-color:#e74c3c; color:white; font-weight:bold; border-radius:5px; cursor:pointer;">Search</button>
      </form>
      <table class="manage-table">
        <thead>
          <tr><th>ID</th><th>Component Name</th><th>Stock</th><th>Price</th><th>created at</th><th>Actions</th></tr>
        </thead>
        <tbody>
         <?php
            if(isset($_POST['search_component']) && $_POST['search_component']!=''){
                $search = $_POST['search_component'];
                $stmt = $conn->prepare("SELECT * FROM components WHERE id LIKE :search");
                $stmt->execute([':search'=>"%$search%"]);
            } else {
                $stmt = $conn->prepare("SELECT * FROM components");
                $stmt->execute();
            }
            $components = $stmt->fetchAll();
            foreach($components as $comp){
                echo "<tr>";
                echo "<td>".$comp['id']."</td>";
                echo "<td>".$comp['component_name']."</td>";
                echo "<td>".$comp['stock']."</td>";
                echo "<td>".$comp['price']."</td>";
                echo "<td>".$comp['created_at']."</td>";
                echo "<td><a href='edit_component.php?id={$comp['id']}' class='edit-btn'>Edit</a> <a href='delete_comman.php?id={$comp['id']}&table=components' class='delete-btn' onclick='return confirm(\"Delete?\")'>Delete</a></td>";
                echo "</tr>";
            }
          ?>
        </tbody>
      </table>
    </section>

    <!-- Order Section -->
    <section id="order" class="section">
      <h2>User Order</h2>
      <form method="post">
        <input type="text" name="search_order" placeholder="Search Orders..." class="search-box"
              value="<?php echo isset($_POST['search_order']) ? htmlspecialchars($_POST['search_order']) : ''; ?>">
        <button type="submit" style="width:100px; padding:10px; border:none; background-color:#e74c3c; color:white; font-weight:bold; border-radius:5px; cursor:pointer;">Search</button>
      </form>
      <table class="manage-table">
        <thead>
          <tr><th>ID</th><th>Buyer_id</th><th>address_id</th><th>Component Id</th><th>Quantity</th><th>Total Price</th><th>Order Date</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php
          if(isset($_POST['search_order']) && $_POST['search_order']!=''){
              $search = $_POST['search_order'];
              $stmt = $conn->prepare("SELECT * FROM orders WHERE buyer_name LIKE :search OR email LIKE :search");
              $stmt->execute([':search'=>"%$search%"]);
          } else {
              $stmt = $conn->prepare("SELECT * FROM orders");
              $stmt->execute();
          }
          $orders = $stmt->fetchAll();
          foreach($orders as $order){
              echo "<tr>";
              echo "<td>".$order['id']."</td>";
              echo "<td>".$order['buyer_id']."</td>";
              echo "<td>".$order['address_id']."</td>";
              echo "<td>".$order['component_id']."</td>";
              echo "<td>".$order['quantity']."</td>";
              echo "<td>".$order['total_price']."</td>";
              echo "<td>".$order['order_date']."</td>";
              echo "<td><a href='delete_comman.php?id={$order['id']}&table=orders' class='delete-btn' onclick='return confirm(\"Delete?\")'>Delete</a></td>";
              echo "</tr>";
          }
          ?>
        </tbody>
      </table>
    </section>


    
    <!-- Address Section -->
<!-- Address Section -->
<section id="address" class="section">
  <h2>User Addresses</h2>
  <form method="post">
    <input type="text" name="search_address" placeholder="Search by Address ID..." class="search-box"
          value="<?php echo isset($_POST['search_address']) ? htmlspecialchars($_POST['search_address']) : ''; ?>">
    <button type="submit" style="width:100px; padding:10px; border:none; background-color:#3498db; color:white; font-weight:bold; border-radius:5px; cursor:pointer;">Search</button>
  </form>

  <table class="manage-table">
    <thead>
      <tr>
        <th>Address ID</th>
        <th>User ID</th>
        <th>Address Line 1</th>
        <th>Address Line 2</th>
        <th>City</th>
        <th>State</th>
        <th>Zip Code</th>
        <th>Phone</th>
        <th>Default?</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if(isset($_POST['search_address']) && $_POST['search_address']!=''){
          $search = intval($_POST['search_address']); // sirf number id ke liye
          $stmt = $conn->prepare("SELECT * FROM user_addresses WHERE id = :search");
          $stmt->execute([':search'=>$search]);
      } else {
          $stmt = $conn->prepare("SELECT * FROM user_addresses ORDER BY id DESC");
          $stmt->execute();
      }
      $addresses = $stmt->fetchAll();
      foreach($addresses as $address){
          echo "<tr>";
          echo "<td>".$address['id']."</td>";
          echo "<td>".$address['user_id']."</td>";
          echo "<td>".htmlspecialchars($address['address_line1'])."</td>";
          echo "<td>".htmlspecialchars($address['address_line2'])."</td>";
          echo "<td>".htmlspecialchars($address['city'])."</td>";
          echo "<td>".htmlspecialchars($address['state'])."</td>";
          echo "<td>".$address['zip_code']."</td>";
          echo "<td>".(isset($address['phone']) ? htmlspecialchars($address['phone']) : 'N/A')."</td>";
          echo "<td>".($address['is_default'] ? 'Yes' : 'No')."</td>";
          echo "<td><a href='delete_comman.php?id={$address['id']}&table=user_addresses' class='delete-btn' onclick='return confirm(\"Delete?\")'>Delete</a></td>";
          echo "</tr>";
      }
      ?>
    </tbody>
  </table>
</section>

<!-- Messages Section -->
    <section id="messages" class="section">
      <h2>User Messages</h2>
      <form method="post">
          <input type="text" name="search_message" placeholder="Search Messages..." class="search-box"
                value="<?php echo isset($_POST['search_message']) ? htmlspecialchars($_POST['search_message']) : ''; ?>">
          <button type="submit" style="width:100px; padding:10px; border:none; background-color:#e74c3c; color:white; font-weight:bold; border-radius:5px; cursor:pointer;">Search</button>
      </form>
      <table class="manage-table">
        <thead>
          <tr><th>Name</th><th>Email</th><th>Phone No.</th><th>Message</th><th>Sand Time</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php
            if(isset($_POST['search_message']) && $_POST['search_message']!=''){
                $search = $_POST['search_message'];
                $stmt = $conn->prepare("SELECT * FROM message WHERE name LIKE :search OR email LIKE :search OR message LIKE :search");
                $stmt->execute([':search'=>"%$search%"]);
            } else {
                $stmt = $conn->prepare("SELECT * FROM message");
                $stmt->execute();
            }
            $messages = $stmt->fetchAll();
            foreach($messages as $msg){
                echo "<tr>";
                echo "<td>".$msg['name']."</td>";
                echo "<td>".$msg['email']."</td>";
                echo "<td>".$msg['phone']."</td>";
                echo "<td>".$msg['message']."</td>";
                echo "<td>".$msg['applied_at']."</td>";
                echo "<td><a href='delete_comman.php?id={$msg['id']}&table=message' class='delete-btn' onclick='return confirm(\"Delete?\")'>Delete</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
      </table>
    </section>

  
    
  </main>
</div>

<!-- Modal Template -->
<div id="modal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <h2 id="modal-title">Add Component</h2>
    
    <form id="modal-form" action="add_component.php" method="POST">
      <input type="text" name="component_name" placeholder="Component Name" required>
      <textarea name="description" placeholder="Description" required></textarea>
      <input type="number" name="stock" placeholder="Stock Quantity" required>
      <input type="number" name="price" placeholder="Price (₹)" step="0.01" required>
      <button type="submit">Save Component</button>
    </form>
  </div>
</div>



</body>
</html>
