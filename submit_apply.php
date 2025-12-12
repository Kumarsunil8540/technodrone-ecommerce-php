<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $course = $_POST['course'];
    $message = $_POST['message'];

    try {
        $stmt = $conn->prepare("INSERT INTO applications (name, email, phone, course, message) VALUES (:name, :email, :phone, :course, :message)");

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':course', $course);
        $stmt->bindParam(':message', $message);

        if ($stmt->execute()) {
            echo "<h2>Application submitted successfully!</h2>";
            echo "<a href='index.php'>Go Back to Home</a>";
        } else {
            echo "<h2>Sorry, something went wrong. Please try again.</h2>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
