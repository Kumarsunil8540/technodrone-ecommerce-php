<?php
// ... (PHP code remains the same as before)
session_start();
include("config.php"); 
try {
    $stmt = $conn->prepare("SELECT * FROM components ORDER BY created_at DESC");
    $stmt->execute();
    $components = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $components = []; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Techno Drone Components | Futuristic Tech</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="compo_page.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
</head>
<body class="dark-theme">

<?php 
// Assuming header.php contains a dark-themed header structure
include("header.php"); 
?>

<div class="hero-section">
    <div class="hero-message">
        <h1>Precision Components for Drone Builders</h1>
        <p>Explore high-performance motors, flight controllers, and accessories. Built for the future of flight.</p>
        <div class="hero-cta-group">
             <a href="#componentSection" class="primary-cta-btn">View All Products <i class="fas fa-arrow-right"></i></a>
             <button class="secondary-cta-btn">Watch Video Guide</button>
        </div>
    </div>
</div>

<div class="container main-content-padding">
    <div class="feature-banners-grid">
        <div class="feature-banner" style="background-image: url('images/banner1.jpg');">
            <h3>Controllers</h3>
            <p>Advanced FCs</p>
        </div>
        <div class="feature-banner" style="background-image: url('images/banner2.jpg');">
            <h3>Motors</h3>
            <p>High Torque Power</p>
        </div>
        <div class="feature-banner" style="background-image: url('images/banner3.jpg');">
            <h3>Propellers</h3>
            <p>Optimized Lift</p>
        </div>
    </div>
</div>

<div class="container main-content-padding">

    <div class="toolbar-top">
        <div class="search-container">
            <input type="text" id="searchInput" placeholder="Search by name, model, or keyword...">
            <button id="searchBtn"><i class="fas fa-search"></i></button>
        </div>

        <div class="sorting-container">
            <label for="sortSelect">Sort By:</label>
            <select id="sortSelect">
                <option value="newest">Newest First</option>
                <option value="price-asc">Price: Low to High</option>
                <option value="price-desc">Price: High to Low</option>
                <option value="rating">Top Rated</option>
            </select>
        </div>
    </div>

    <div class="main-grid-layout">
        
        <aside class="sidebar-filters">
            <h3 class="filter-title"><i class="fas fa-sliders-h"></i> Filters</h3>
            
            <div class="filter-group category-filter">
                <h4>Categories</h4>
                <button class="filter-btn active" data-category="all">All Components</button>
                <button class="filter-btn" data-category="motor">Motors (20)</button>
                <button class="filter-btn" data-category="propeller">Propellers (12)</button>
                <button class="filter-btn" data-category="controller">Flight Controllers (8)</button>
                <button class="filter-btn" data-category="esc">ESCs (15)</button>
            </div>
            
            <div class="filter-group">
                <h4>Price Range</h4>
                <input type="range" min="100" max="10000" value="5000" class="price-slider">
                <div class="price-range-display">₹100 - ₹10,000</div>
            </div>
            
            <div class="filter-group">
                <h4>Availability</h4>
                <label class="checkbox-label"><input type="checkbox" checked> In Stock Only</label>
                <label class="checkbox-label"><input type="checkbox"> Hot Deals</label>
            </div>
            
        </aside>

        <section class="component-section" id="componentSection">
            <?php if (!empty($components)): ?>
                <?php foreach($components as $comp): ?>
                <article class="component-card" data-category="<?= htmlspecialchars($comp['category'] ?? 'all') ?>" data-price="<?= htmlspecialchars($comp['price'] ?? 0) ?>">
                    
                    <div class="card-header">
                         <span class="badge sale-badge">SALE</span>
                         <button class="wishlist-btn" title="Add to Wishlist"><i class="far fa-heart"></i></button>
                    </div>

                    <div class="component-img-container">
                        <img src="<?= htmlspecialchars($comp['image']) ?>" alt="<?= htmlspecialchars($comp['component_name']) ?>" class="component-img-styled">
                    </div>

                    <div class="component-details">
                        <h3 class="component-name"><?= htmlspecialchars($comp['component_name']) ?></h3>
                        
                        <div class="rating-bar">
                            <div class="rating-stars">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i><i class="far fa-star"></i>
                            </div>
                            <span class="stock-info">Stock: <?= htmlspecialchars($comp['stock'] ?? 0) ?></span>
                        </div>
                        
                        <div class="price-info">
                            <p class="price">₹<?= number_format((float)($comp['price'] ?? 0), 2) ?></p>
                            <p class="old-price">₹1500.00</p>
                        </div>
                        
                        <div class="btn-group">
                            <a href="component_details.php?id=<?= $comp['id'] ?>" class="btn detail-btn">Details</a>
                            
                            <button class="btn cart-btn" ><a href="add_my_cart.php?id=<?= $comp['id'] ?>" style="text-decoration:none">
                                <i class="fas fa-shopping-bag"></i> Add to Cart</a>
                            </button>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-results-msg">No components found. Database is empty.</p>
            <?php endif; ?>
        </section>
        
    </div> </div> <?php include("footer.php"); ?>

<script src="compo_page.js"></script>
</body>
</html>
