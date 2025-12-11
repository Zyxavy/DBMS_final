<?php 
session_start();
require_once __DIR__ . "/../includes/functions.php";

checkSession();

include('../includes/navbar.html');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manzanas | Products</title>
    <link rel="stylesheet" href="../pages/productsStyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

    <div class="hero-section">
        <div class="hero-content">
            <div class="hero-text">
                <p style="font-size: 0.8rem; letter-spacing: 1px; text-transform: uppercase; color: #ffcccc;">Featured Product</p>
                <h1 class="hero-title">M-phone 17 PRO</h1>
                <p class="hero-description">
                    The pinnacle of performance, design, and intelligence. Built with an aerospace-grade titanium frame, 
                    an edge-to-edge MicroLED ProMotion display, and powered by Manzanas' most advanced A18 Pro chip. It delivers unmatched speed, efficiency, and realism.
                </p>
                <div class="hero-actions">
                    <a href="phones.php" class="btn-primary-dark">Learn More</a>
                    <a href="phones.php" class="btn-secondary-transparent">Buy Now</a>
                </div>
            </div>
            
            <div class="hero-image">
                <img src="../assets/Phones/Iphone17Pro.png" alt="M-phone 17 PRO">
            </div>
        </div>
    </div>

    <div class="products-grid-container">
        <div class="products-grid-header">
            Products
        </div>
        
        <div class="product-cards-grid">
            
            <a href="phones.php" style="text-decoration: none;">
                <div class="product-card">
                    <h2 class="card-title">Smartphones</h2>
                    <div class="card-image-box">

                        <img src="../assets/Phones/Iphone_series.jpg" alt="Smartphones">
                    </div>
                    <div class="card-action">
                        <span class="card-link">Show more &gt;</span>
                    </div>
                </div>
            </a>


            <a href="pc.php" style="text-decoration: none;">
                <div class="product-card">
                    <h2 class="card-title">PC</h2>
                    <div class="card-image-box">
                        <img src="../assets/PC/macbook-hero.png" alt="PC">
                    </div>
                    <div class="card-action">
                        <span class="card-link">Show more &gt;</span>
                    </div>
                </div>
            </a>

        </div>
    </div>

    <div>
        <a href="profile.php">TEST HERE</a>
    </div>

    
    <?php include('../includes/footer.html') ?>

</body>
</html>