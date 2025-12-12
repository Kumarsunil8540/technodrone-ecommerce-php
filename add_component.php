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
<title>Add Component - Techno Drone</title>
<link rel="stylesheet" href="add_component.css">
</head>
<body>

<div class="container">
    <h1>Add New Component</h1>

    <form action="" method="POST" enctype="multipart/form-data" class="component-form">
        <label for="name">Component Name:</label>
        <input type="text" name="name" id="name" placeholder="Enter component name" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" placeholder="Enter component description" rows="4" required></textarea>

        <label for="price">Price (₹):</label>
        <input type="number" name="price" id="price" placeholder="Enter price" required>

        <label for="stock">Stock Quantity:</label>
        <input type="number" name="stock" id="stock" placeholder="Enter stock quantity" required>

        <label for="image">Upload Image:</label>
        <input type="file" name="image" id="image" accept="image/*" required>

        <button type="submit">Add Component</button>
    </form>
</div>

</body>
</html>



<?php
if(isset($_POST['name'])){
    include('config.php');
    $comp_name = $_POST['name'];
    $compo_description = $_POST['description'];
    $comp_price = $_POST['price'];
    $stock = $_POST['stock'];

    $image_path = "uploads/components/" . time() . "_" . $_FILES['image']['name'];
    $file = move_uploaded_file($_FILES['image']['tmp_name'], $image_path);

    $stdt = $conn->prepare("INSERT INTO components (component_name, description, price, stock, image) VALUES (?, ?, ?, ?, ?)");
    $result = $stdt->execute([$comp_name, $compo_description, $comp_price, $stock, $image_path]);

    if($file && $result){
        header('Location: admin_dashboard.php');
        exit;
    } else {
        echo "Data not stored.";
        echo "<p><a href='add_component.php'>Go back to add component</a></p>";
    }
}
?>