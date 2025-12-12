<?php
if(session_status()===PHP_SESSION_NONE){
    session_start();
}
if(!$_SESSION['admin_login']){
    header('location: admin_login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Course - Techno Drone</title>
<link rel="stylesheet" href="add_course.css">
</head>
<body>

<div class="container">
    <h1>Add New Course</h1>

    <form action="" method="POST" enctype="multipart/form-data" class="course-form">
        
        <label for="name">Course Name:</label>
        <input type="text" name="name" id="name" placeholder="Enter course name" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" placeholder="Enter course description" rows="4" required></textarea>

        <label for="price">Price (₹):</label>
        <input type="number" name="price" id="price" placeholder="Enter price" required>

        <label for="duration">Duration:</label>
        <input type="text" name="duration" id="duration" placeholder="e.g. 3 Months" required>

        <label for="instructor">Instructor:</label>
        <input type="text" name="instructor" id="instructor" placeholder="Enter instructor name">

        <label for="category">Category:</label>
        <select name="category" id="category">
            <option value="Programming">Programming</option>
            <option value="Robotics">Robotics</option>
            <option value="Electronics">Electronics</option>
            <option value="Drone Tech">Drone Tech</option>
            <option value="Other">Other</option>
        </select>

        <label for="level">Level:</label>
        <select name="level" id="level">
            <option value="Beginner">Beginner</option>
            <option value="Intermediate">Intermediate</option>
            <option value="Advanced">Advanced</option>
        </select>

        <label for="image">Upload Image:</label>
        <input type="file" name="image" id="image" accept="image/*" required>

        <button type="submit">Add Course</button>
    </form>
</div>

</body>
</html>


<?php
if(isset($_POST['name'])){
    include('config.php');

    $course_name   = $_POST['name'];
    $description   = $_POST['description'];
    $price         = $_POST['price'];
    $duration      = $_POST['duration'];
    $instructor    = $_POST['instructor'];
    $category      = $_POST['category'];
    $level         = $_POST['level'];

    // Upload folder check
    $uploadDir = "uploads/courses/";
    if(!is_dir($uploadDir)){
        mkdir($uploadDir, 0777, true);
    }

    // Image path
    $image_path = $uploadDir . time() . "_" . basename($_FILES['image']['name']);
    $file = move_uploaded_file($_FILES['image']['tmp_name'], $image_path);

    $stdt = $conn->prepare("INSERT INTO courses (course_name, description, price, duration, instructor, category, level, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $result = $stdt->execute([$course_name, $description, $price, $duration, $instructor, $category, $level, $image_path]);

    if($file && $result){
        header('Location: admin_dashboard.php');
        exit;
    } else {
        echo "Data not stored.";
        echo "<p><a href='add_course.php'>Go back to add course</a></p>";
    }
}
?>
