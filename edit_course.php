<?php
session_start();
if(!isset($_SESSION['admin_login'])){
    header("Location: admin_login.php");
    exit();
}

include("config.php");

// course fetch karna
$course = null;
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM courses WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $course = $stmt->fetch();
}

// ✅ update process
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $course_name = $_POST['course_name'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $instructor = $_POST['instructor'];
    $description = $_POST['description'];

    // Purana image path nikal lo
    $stmt = $conn->prepare("SELECT image FROM courses WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $oldData = $stmt->fetch();
    $image = $oldData['image']; // Purana image path

    $upload_success = true;
    $upload_message = "";

    // agar naya image upload hua hai
    if(isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE && $_FILES['image']['name'] !== "") {

        if($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $upload_success = false;
            $error_code = $_FILES['image']['error'];
            $upload_message = "❌ File upload error! Code: {$error_code}.";
        } else {
            $targetDir = "uploads/courses/";
            
            if(!is_dir($targetDir)){
                if(!mkdir($targetDir, 0777, true)) {
                    $upload_success = false;
                    $upload_message = "❌ Target directory '{$targetDir}' could not be created!";
                }
            }
            
            $newImagePath = $targetDir . time() . "_" . basename($_FILES['image']['name']);
            
            if($upload_success && move_uploaded_file($_FILES['image']['tmp_name'], $newImagePath)){
                // ✅ Purana image delete nahi hoga
                $image = $newImagePath; 
                $upload_message = "✅ New image uploaded successfully!";
            } else if ($upload_success) {
                $upload_success = false;
                $upload_message = "❌ Image move nahi ho paya! Path: {$newImagePath}";
            }
        }
    }

    // Update Query
    if ($upload_success) {
        $stmt_new = $conn->prepare("UPDATE courses 
            SET course_name = :course_name, 
                description = :description, 
                price = :price, 
                duration = :duration, 
                instructor = :instructor,
                image = :image
            WHERE id = :id");

        $result = $stmt_new->execute([
            ':course_name' => $course_name,
            ':description' => $description,
            ':price' => $price,
            ':duration' => $duration,
            ':instructor' => $instructor,
            ':image' => $image,
            ':id' => $id
        ]);

        if ($result) { 
            echo "<p style='color:green; text-align:center;'>✅ Course updated successfully!</p>";
            // update ke baad fresh data fetch
            $stmt = $conn->prepare("SELECT * FROM courses WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $course = $stmt->fetch();
        } else {
            echo "<p style='color:red; text-align:center;'>❌ Database update error!</p>";
        }
    } else {
        echo "<p style='color:red; text-align:center;'>{$upload_message}</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Course</title>
  <link rel="stylesheet" href="edit_course.css">
</head>
<body>
  <div class="edit-container">
    <h2>Edit Course</h2>
    <?php if($course): ?>
    <form method="POST" action="" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo $course['id']; ?>">

      <label>Course Name</label>
      <input type="text" name="course_name" value="<?php echo htmlspecialchars($course['course_name']); ?>" required>

      <label>Price (₹)</label>
      <input type="number" name="price" value="<?php echo htmlspecialchars($course['price']); ?>" required>

      <label>Duration</label>
      <input type="text" name="duration" value="<?php echo htmlspecialchars($course['duration']); ?>" required>

      <label>Instructor</label>
      <input type="text" name="instructor" value="<?php echo htmlspecialchars($course['instructor']); ?>" required>

      <label>Description</label>
      <textarea name="description" required><?php echo htmlspecialchars($course['description']); ?></textarea>

      <label>Update Image (optional)</label>
      <input type="file" name="image">
      <?php if(!empty($course['image'])): ?>
        <p>Current Image: 
            <img src="<?php echo htmlspecialchars($course['image']); ?>" width="80">
        </p>
      <?php endif; ?>

      <button type="submit" name="update">Update Course</button>
    </form>
    <?php else: ?>
      <p style="color:red;">Course not found!</p>
    <?php endif; ?>
    <a href="admin_dashboard.php#courses" class="back-btn">⬅ Back to Courses</a>
  </div>
</body>
</html>
