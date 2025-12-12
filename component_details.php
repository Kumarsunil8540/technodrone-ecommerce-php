<?php
session_start();
include("config.php");

if(!isset($_GET['id'])){
    header("Location: compo_page.php");
    exit;
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM components WHERE id = ?");
$stmt->execute([$id]);
$component = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$component){
    echo "<h2>Component not found!</h2>";
    exit;
}
// Fetch all components except current one for related section
$stmt_all = $conn->prepare("SELECT * FROM components WHERE id != ? ORDER BY created_at DESC LIMIT 6");
$stmt_all->execute([$id]);
$related_components = $stmt_all->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($component['component_name']) ?> - Details</title>
<link rel="stylesheet" href="component_details.css">
</head>
<body>

<?php include("header.php"); ?>

<div class="details-container">
    <div class="image-box">
        <img src="<?= htmlspecialchars($component['image']) ?>" alt="<?= htmlspecialchars($component['component_name']) ?>">
    </div>
    <div class="info-box">
        <h1><?= htmlspecialchars($component['component_name']) ?></h1>
        <p><strong>Price:</strong> ₹<?= htmlspecialchars($component['price']) ?></p>
        <p><strong>Stock:</strong> <?= htmlspecialchars($component['stock']) ?></p>
        <p><strong>Description:</strong></p>
        <p><?= nl2br(htmlspecialchars($component['description'])) ?></p>

        <a href="buy_now.php?component_id=<?= $component['id'] ?>" class="buy-btn">Buy Now</a>

    </div>
</div>
<!-- RELATED COMPONENTS -->
<div class="related-section">
    <h2>Related Components</h2>
    <div class="related-container">
        <?php foreach($related_components as $comp): ?>
        <div class="component-box">
            <div class="component-img" style="background-image: url('<?= htmlspecialchars($comp['image']) ?>');"></div>
            <h3><?= htmlspecialchars($comp['component_name']) ?></h3>
            <p>Price: ₹<?= htmlspecialchars($comp['price']) ?></p>
            <a href="component_details.php?id=<?= $comp['id'] ?>" class="apply-btn">View Details</a>
        </div>
        <?php endforeach; ?>
    </div>
</div>


<?php include("footer.php"); ?>
</body>
</html>
