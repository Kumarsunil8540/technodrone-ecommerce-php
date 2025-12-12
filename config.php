<?php
$hostname = "sql308.cpanelfree.com";
$username = "cpfr_39993966";
$password = "zvgm9tyb";  // agar password blank hai
$dbname = "cpfr_39993966_techno_drone";

try {
    $conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully"; // debugging ke liye
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
