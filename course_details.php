<?php
session_start();
include("config.php");

// Agar id nahi mili to redirect
if(!isset($_GET['id'])){
    header("Location: courses.php");
    exit;
}

$id = intval($_GET['id']);

// Course fetch karo
$stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$id]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$course){
    echo "<h2>Course not found!</h2>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($course['course_name']); ?> - Details</title>
    <link rel="stylesheet" href="course_details.css">
</head>
<body>
    <?php include("header.php"); ?>

    <div class="details-container">
        <!-- Image -->
        <div class="image-box">
            <img src="<?= htmlspecialchars($course['image']); ?>" alt="<?= htmlspecialchars($course['course_name']); ?>">
        </div>

        <!-- Info -->
        <div class="info-box">
            <h1><?= htmlspecialchars($course['course_name']); ?></h1>
            <p><strong>Instructor:</strong> <?= htmlspecialchars($course['instructor']); ?></p>
            <p><strong>Category:</strong> <?= htmlspecialchars($course['category']); ?></p>
            <p><strong>Level:</strong> <?= htmlspecialchars($course['level']); ?></p>
            <p><strong>Duration:</strong> <?= htmlspecialchars($course['duration']); ?></p>
            <p><strong>Price:</strong> ₹<?= htmlspecialchars($course['price']); ?></p>
            
            <p><strong>Description:</strong></p>
            <p><?= nl2br(htmlspecialchars($course['description'])); ?></p>

            <a href="apply.php?course=<?= urlencode($course['course_name']); ?>" class="enroll-btn">Enroll Now</a>
        </div>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>
