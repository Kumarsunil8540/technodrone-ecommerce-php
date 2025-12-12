<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course</title>
    <link rel="stylesheet" href="courses.css">
</head>
<body>
    <?php include("header.php"); ?>
    <?php include("head.php"); ?>
    
    <div class="list_box">
        <h1>Our Courses</h1>
        <ul>
            <?php
            include("config.php");

            // Fetch all courses
            $query = $conn->query("SELECT id, course_name FROM courses ORDER BY created_at DESC");

            if($query->rowCount() > 0){
                while($row = $query->fetch(PDO::FETCH_ASSOC)){
                    $id = $row['id'];
                    $name = htmlspecialchars($row['course_name']);
                    echo "<li><a href='course_details.php?id=$id'>$name</a></li>";
                }
            } else {
                echo "<li>No courses available yet.</li>";
            }
            ?>
        </ul>
    </div>
    
    <?php include("footer.php"); ?>
</body>
</html>
