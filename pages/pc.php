<?php
session_start();
include('../includes/navbar.html');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manzanas Computers - Pro Performance</title>
    <link rel="stylesheet" href="../pages/pcStyles.css">
</head>
<body class="landing-page">

<!-- change to <img> it mga <i> tags -->

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-title">
            <h1>Supercharged.</h1>
            <p class="hero-subtitle">The new M3 family. Scary fast.</p>
        </div>
        
        <div class="hero-content">
            <div class="hero-center-image">
                <div class="hero-badge">New Release</div>
                <!-- Laptop Hero Image -->
                <img src="../assets/PC/macbook-hero.png" alt="Manzanas Laptop Hero">
            </div>
        </div>
    </section>

    <div class="category-header">
        <h2>Laptops</h2>
        <p>Power that moves with you.</p>
    </div>

    <section class="comparison-section">
        <div class="cards-container">
            <!-- Laptop 1: Air -->
            <div class="product-card">
                <div>
                    <div class="card-image-placeholder">
                        <img src="../assets/PC/macbook-air.png" alt="Manzanas Laptop air" width="250px" height="250px">
                    </div>
                    <h3 class="product-title">M-Book Air</h3>
                    <div class="product-price">From $999</div>
                    
                    <div class="specs-list">
                        <div class="spec-item">
                            <i class="fa-solid fa-microchip"></i> M2 Chip
                        </div>
                        <div class="spec-item">
                            <i class="fa-solid fa-battery-full"></i> 18hr Battery Life
                        </div>
                        <div class="spec-item">
                            <i class="fa-solid fa-weight-hanging"></i> 1.24 kg Weight
                        </div>
                        <div class="spec-item">
                            <i class="fa-solid fa-display"></i> 13.6" Liquid Retina
                        </div>
                    </div>
                </div>
                <button class="btn-primary" style="width:100%; margin-top: 20px;">Buy</button>
            </div>

            <!-- Laptop 2: Pro 14 -->
            <div class="product-card">
                <div>
                    <div class="card-image-placeholder">
                        <img src="../assets/PC/macbook-pro14.png" alt="Manzanas Laptop pro 14" width="230px" height="220px">
                    </div>
                    <h3 class="product-title">M-Book Pro 14</h3>
                    <div class="product-price">From $1599</div>
                    
                    <div class="specs-list">
                        <div class="spec-item">
                            <i class="fa-solid fa-microchip"></i> M3 Pro Chip
                        </div>
                        <div class="spec-item">
                            <i class="fa-solid fa-battery-full"></i> 22hr Battery Life
                        </div>
                        <div class="spec-item">
                            <i class="fa-solid fa-memory"></i> Up to 36GB Unified Memory
                        </div>
                        <div class="spec-item">
                            <i class="fa-solid fa-display"></i> 14.2" XDR Display
                        </div>
                    </div>
                </div>
                <button class="btn-primary" style="width:100%; margin-top: 20px;">Buy</button>
            </div>

            <!-- Laptop 3: Pro 16 -->
            <div class="product-card">
                <div>
                    <div class="card-image-placeholder">
                        <img src="../assets/PC/macbook-pro16.png" alt="Manzanas Laptop pro16" width="250px" height="250px">
                    </div>
                    <h3 class="product-title">M-Book Pro 16</h3>
                    <div class="product-price">From $2499</div>
                    
                    <div class="specs-list">
                        <div class="spec-item">
                            <i class="fa-solid fa-microchip"></i> M3 Max Chip
                        </div>
                        <div class="spec-item">
                            <i class="fa-solid fa-battery-full"></i> 22hr Battery Life
                        </div>
                        <div class="spec-item">
                            <i class="fa-solid fa-memory"></i> Up to 128GB Unified Memory
                        </div>
                        <div class="spec-item">
                            <i class="fa-solid fa-video"></i> 8K Video Editing
                        </div>
                    </div>
                </div>
                <button class="btn-primary" style="width:100%; margin-top: 20px;">Buy</button>
            </div>
        </div>
    </section>

    <div class="category-header section-darker" style="padding-top:40px;">
        <h2>Desktops</h2>
        <p>Muscle for your biggest ideas.</p>
    </div>

    <section class="comparison-section section-darker">
        <div class="cards-container">
            <!-- Desktop 1: Mini -->
            <div class="product-card">
                <div>
                    <div class="card-image-placeholder">
                        <img src="../assets/PC/mac-mini.png" alt="Manzanas mac-mini" width="250px" height="180px">
                    </div>
                    <h3 class="product-title">M-Station Mini</h3>
                    <div class="product-price">From $599</div>
                    
                    <div class="specs-list">
                        <div class="spec-item">
                            <i class="fa-solid fa-microchip"></i> M2 or M2 Pro Chip
                        </div>
                        <div class="spec-item">
                            <i class="fa-solid fa-expand"></i> Compact Design
                        </div>
                        <div class="spec-item">
                            <i class="fa-solid fa-wifi"></i> Wi-Fi 6E
                        </div>
                        <div class="spec-item">
                            <i class="fa-solid fa-bolt"></i> Thunderbolt 4
                        </div>
                    </div>
                </div>
                <button class="btn-primary" style="width:100%; margin-top: 20px;">Buy</button>
            </div>

            <!-- Desktop 2: Studio -->
            <div class="product-card">
                <div>
                    <div class="card-image-placeholder">
                        <img src="../assets/PC/mac-studio.png" alt="Manzanas mac-studio" width="280px" height="230px">
                    </div>
                    <h3 class="product-title">M-Station Studio</h3>
                    <div class="product-price">From $1999</div>
                    
                    <div class="specs-list">
                        <div class="spec-item">
                            <i class="fa-solid fa-microchip"></i> M2 Max or Ultra
                        </div>
                        <div class="spec-item">
                            <i class="fa-solid fa-wind"></i> Whisper Quiet
                        </div>
                        <div class="spec-item">
                            <i class="fa-solid fa-memory"></i> Up to 192GB Memory
                        </div>
                        <div class="spec-item">
                            <i class="fa-solid fa-layer-group"></i> 6 Thunderbolt Ports
                        </div>
                    </div>
                </div>
                <button class="btn-primary" style="width:100%; margin-top: 20px;">Buy</button>
            </div>

            <!-- Desktop 3: Pro Tower -->
            <div class="product-card">
                <div>
                    <div class="card-image-placeholder">
                        <img src="../assets/PC/mac-pro.png" alt="Manzanas mac-pro" width="240px" height="180px">
                    </div>
                    <h3 class="product-title">M-Station Pro</h3>
                    <div class="product-price">From $6999</div>
                    
                    <div class="specs-list">
                        <div class="spec-item">
                            <i class="fa-solid fa-microchip"></i> M2 Ultra Chip
                        </div>
                        <div class="spec-item">
                            <i class="fa-solid fa-puzzle-piece"></i> PCIe Expansion
                        </div>
                        <div class="spec-item">
                            <i class="fa-solid fa-memory"></i> 192GB Unified Memory
                        </div>
                        <div class="spec-item">
                            <i class="fa-solid fa-gears"></i> Rack Mount Available
                        </div>
                    </div>
                </div>
                <button class="btn-primary" style="width:100%; margin-top: 20px;">Buy</button>
            </div>
        </div>
    </section>

    <footer class="site-footer">
        <div class="footer-features">
            <div class="footer-feat-item">
                <i class="fa-solid fa-truck"></i>
                <span>FREE DELIVERY</span>
            </div>
            <div class="footer-feat-item">
                <i class="fa-solid fa-shield-halved"></i>
                <span>SECURE PAYMENT</span>
            </div>
            <div class="footer-feat-item">
                <i class="fa-solid fa-check"></i>
                <span>OFFICIAL SERVICE</span>
            </div>
            <div class="footer-feat-item">
                <i class="fa-regular fa-clock"></i>
                <span>FAST DELIVERY</span>
            </div>
        </div>

        <div class="footer-links">
            <div class="footer-column">
                <h4>Products and Services</h4>
                <ul>
                    <li><a href="#">Smartphones</a></li>
                    <li><a href="#">Laptops</a></li>
                    <li><a href="#">PC</a></li>
                    <li><a href="#">MANZANAS Services</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Store</h4>
                <ul>
                    <li><a href="#">Find a store</a></li>
                    <li><a href="#">Financing</a></li>
                    <li><a href="#">Order Status</a></li>
                    <li><a href="#">Shopping Help</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Support</h4>
                <ul>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Warranty</a></li>
                    <li><a href="#">Manuals</a></li>
                    <li><a href="#">Software Update</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>About MANZANAS</h4>
                <ul>
                    <li><a href="#">Newsroom</a></li>
                    <li><a href="#">Investors</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Ethics</a></li>
                </ul>
            </div>
        </div>

        <div class="copyright-text">
            Copyright © 2025 MANZANAS
        </div>
    </footer>

</body>
</html>