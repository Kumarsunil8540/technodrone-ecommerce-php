<?php
session_start();
if(!isset($_SESSION['admin_login'])){
    header("Location: admin_login.php");
    exit();
}

include("config.php");

// component fetch karna
$component = null;
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM components WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $component = $stmt->fetch();
}

// ✅ update process
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $component_name = $_POST['component_name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];

    // Default: Purana image path/name
    $stmt = $conn->prepare("SELECT image FROM components WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $oldData = $stmt->fetch();
    $image = $oldData['image']; // Purana image path

    $upload_success = true;
    $upload_message = "";

    // agar naya image upload hua hai (No file selected error code is 4)
    if(isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE && $_FILES['image']['name'] !== "") {
        
        // File upload error check
        if($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
             $upload_success = false;
             $error_code = $_FILES['image']['error'];
             $upload_message = "❌ File upload error! Code: {$error_code}.";
        } else {
            $targetDir = "uploads/components/";
            
            // agar folder exist nahi karta hai to create kar lo
            if(!is_dir($targetDir)){
                if(!mkdir($targetDir, 0777, true)) {
                    $upload_success = false;
                    $upload_message = "❌ Target directory '{$targetDir}' could not be created!";
                }
            }
            
            // नया फ़ाइल पाथ (आपके add_component कोड की तरह पूरा पाथ)
            $newImagePath = $targetDir . time() . "_" . basename($_FILES['image']['name']);
            
            // Image ko move karna
            if($upload_success && move_uploaded_file($_FILES['image']['tmp_name'], $newImagePath)){
                
                // ✅ पुराने इमेज को डिलीट नहीं किया जाएगा।
                
                // डेटाबेस में नया पूरा पाथ स्टोर करो
                $image = $newImagePath; 
                $upload_message = "✅ New image uploaded and path updated successfully!";
            } else if ($upload_success) {
                $upload_success = false;
                $upload_message = "❌ Image ko move nahi kar paya! Folder permissions (755/777) check karen. Path: {$newImagePath}";
            }
        }
    }

    // Update Query
    if ($upload_success) {
        $stmt_new = $conn->prepare("UPDATE components 
            SET component_name = :component_name, 
                description = :description, 
                price = :price, 
                stock = :stock,
                image = :image
            WHERE id = :id");

        $result = $stmt_new->execute([
            ':component_name' => $component_name,
            ':description' => $description,
            ':price' => $price,
            ':stock' => $stock,
            ':image' => $image, // Naya path ya purana path
            ':id' => $id
        ]);

        if ($result) { 
            echo "<p style='color:green; text-align:center;'>✅ Component updated successfully!</p>";
            // Update ke baad component data ko dobara fetch karen taaki form mein naya data dikhe
            $stmt = $conn->prepare("SELECT * FROM components WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $component = $stmt->fetch();
        } else {
            echo "<p style='color:red; text-align:center;'>❌ Component update karte samay database error hua!</p>";
        }
    } else {
        // Agar image upload fail hua, to sirf error message dikhao
        echo "<p style='color:red; text-align:center;'>{$upload_message}</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Component</title>
  <link rel="stylesheet" href="edit_component.css">
</head>
<body>
  <div class="edit-container">
    <h2>Edit Component</h2>
    <?php if($component): ?>
    <form method="POST" action="" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo $component['id']; ?>">

      <label>Component Name</label>
      <input type="text" name="component_name" value="<?php echo htmlspecialchars($component['component_name']); ?>" required>

      <label>Price (₹)</label>
      <input type="number" name="price" value="<?php echo htmlspecialchars($component['price']); ?>" required>

      <label>Stock</label>
      <input type="text" name="stock" value="<?php echo htmlspecialchars($component['stock']); ?>" required>

      <label>Description</label>
      <textarea name="description" required><?php echo htmlspecialchars($component['description']); ?></textarea>

      <label>Update Image (optional)</label>
      <input type="file" name="image">

      <?php if(!empty($component['image'])): ?>
        <p>Current Image: 
            <img src="<?php echo htmlspecialchars($component['image']); ?>" width="80">
        </p>
      <?php endif; ?>

      <button type="submit" name="update">Update Component</button>
    </form>
    <?php else: ?>
      <p style="color:red;">Component not found!</p>
    <?php endif; ?>
    <a href="admin_dashboard.php#courses" class="back-btn">⬅ Back to Dashboard</a>
  </div>
</body>
</html>