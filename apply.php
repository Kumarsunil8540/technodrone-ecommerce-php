<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Course</title>
    <link rel="stylesheet" href="course_page.css">
</head>
<body>
    <?php include("header.php"); ?>
    <?php include("head.php"); ?>

    <div class="course_container">
        <h1>Apply for Course</h1>
        <p>Fill the form below to apply for your selected course.</p>

        <?php
        // Get course name from URL
        $course_name = isset($_GET['course']) ? $_GET['course'] : '';
        ?>

        <form action="submit_apply.php" method="POST" class="apply_form">
            <label for="name">Full Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="phone">Phone Number:</label>
            <input type="text" name="phone" id="phone" required>

            <label for="course">Course:</label>
            <input type="text" name="course" id="course" value="<?php echo htmlspecialchars($course_name); ?>" readonly>

            <label for="message">Message / Query:</label>
            <textarea name="message" id="message" rows="4" placeholder="Any questions..."></textarea>

            <button type="submit">Submit Application</button>
        </form>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>
