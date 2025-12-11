<?php
session_start();

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../Classes/ProductClass.php";
require_once __DIR__ . "/../includes/functions.php";

checkSession();

include('../includes/navbar.html');

$laptopsByClass = [
    'Entry level' => [],
    'Mid tier' => [],
    'Flag ship' => []
];
$desktopsByClass = [
    'Entry level' => [],
    'Mid tier' => [],
    'Flag ship' => []
];

try {
    $productsInstance = new Products();
    
    $categoryIdLaptops = 2; //change based on ID of laptops and desktop
    $categoryIdDesktops = 3;
    

    $fetchedLaptops = $productsInstance->getProductsbyCategory($categoryIdLaptops, 'price', 'ASC');
    $fetchedDesktops = $productsInstance->getProductsbyCategory($categoryIdDesktops, 'price', 'ASC');
 
    if ($fetchedLaptops && is_array($fetchedLaptops)) {
        foreach ($fetchedLaptops as $dbProduct) {
            $description = $dbProduct['product_description'] ?? 'View details for specifications.';
            $specsArray = !empty($description) ? explode('<br>', $description) : [];
            
            $productData = [
                'product_id' => $dbProduct['product_id'],
                'name' => $dbProduct['name'],
                'stock' => $dbProduct['stock'] ?? 1,
                'price' => $dbProduct['price'] ?? 0,
                'img_url' => $dbProduct['img_url'] ?? '../assets/images/500x500laptop-placeholder.png',
                'product_description' => $description,
                'specs_array' => $specsArray,
                'ROM' => $dbProduct['ROM'] ?? 'N/A',
                'RAM' => $dbProduct['RAM'] ?? 'N/A',
                'class_name' => $dbProduct['class_name'] ?? 'Entry level'
            ];
            
            $className = $dbProduct['class_name'] ?? 'Entry level';
            if (isset($laptopsByClass[$className])) {
                $laptopsByClass[$className][] = $productData;
            } else {
                $laptopsByClass['Entry level'][] = $productData;
            }
        }
    }
    
    if ($fetchedDesktops && is_array($fetchedDesktops)) {
        foreach ($fetchedDesktops as $dbProduct) {
            $description = $dbProduct['product_description'] ?? 'View details for specifications.';
            $specsArray = !empty($description) ? explode('<br>', $description) : [];
            
            $productData = [
                'product_id' => $dbProduct['product_id'],
                'name' => $dbProduct['name'],
                'stock' => $dbProduct['stock'] ?? 1,
                'price' => $dbProduct['price'] ?? 0,
                'img_url' => $dbProduct['img_url'] ?? '../assets/images/500x500-placeholder.png',
                'product_description' => $description,
                'specs_array' => $specsArray,
                'ROM' => $dbProduct['ROM'] ?? 'N/A',
                'RAM' => $dbProduct['RAM'] ?? 'N/A',
                'class_name' => $dbProduct['class_name'] ?? 'Entry level'
            ];
            
            $className = $dbProduct['class_name'] ?? 'Entry level';
            if (isset($desktopsByClass[$className])) {
                $desktopsByClass[$className][] = $productData;
            } else {
                $desktopsByClass['Entry level'][] = $productData;
            }
        }
    }

} catch (Exception $e) {
    error_log("DB Error in pc.php: " . $e->getMessage());
    $_SESSION['error_message'] = "Could not connect to the database or fetch product list.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manzanas Computers - Pro Performance</title>
    <link rel="stylesheet" href="../pages/pcStyles.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> <!-- placeholder laanay ini since waray paman kita icons   -->
    <style>
        .buy-form {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            width: 100%;
        }
        
        .quantity-input {
            width: 60px;
            padding: 10px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            text-align: center;
            font-size: 16px;
        }

        .buy-form button {
            flex: 1; 
        }
        
        .product-info {
            margin-top: 10px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            font-size: 14px;
            border: 1px solid #e1e5e9;
        }
        
        .price-tag {
            font-size: 20px;
            font-weight: bold;
            color: #0b3e72ff;
            margin-bottom: 5px;
        }
        
        .storage-info {
            color: #666;
            margin-bottom: 5px;
        }
        
        .product-price {
            font-size: 18px;
            font-weight: bold;
            color: #3c3cc7ff;
            margin: 10px 0;
        }
        
        .section-darker {
            background-color: #f5f5f7;
            padding: 40px 0;
            margin-top: 40px;
        }
        
        .category-header {
            text-align: center;
            padding: 40px 20px 20px;
        }
        
        .category-header h2 {
            font-size: 48px;
            font-weight: bold;
            color: #1d1d1f;
            margin-bottom: 10px;
        }
        
        .category-header p {
            font-size: 24px;
            color: #86868b;
        }
        
        .error-message {
            text-align: center;
            padding: 20px;
            background: #ffe6e6;
            border: 1px solid #ff9999;
            border-radius: 8px;
            margin: 20px auto;
            max-width: 800px;
            color: #cc0000;
        }
        
        .success-message {
            text-align: center;
            padding: 20px;
            background: #e6ffe6;
            border: 1px solid #99ff99;
            border-radius: 8px;
            margin: 20px auto;
            max-width: 800px;
            color: #006600;
        }
        
        .class-header {
            text-align: center;
            margin: 40px 0 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #be1d1dff;
        }
        
        .class-header h3 {
            font-size: 32px;
            color: #1d1d1f;
            display: inline-block;
            padding: 0 20px;
            background: white;
        }
        
        .section-darker .class-header h3 {
            background: #f5f5f7;
        }
        
        .no-products {
            text-align: center;
            padding: 40px;
            font-size: 18px;
            color: #86868b;
            grid-column: 1 / -1;
        }
    </style>
</head>
<body class="landing-page">
    <?php
        if(isset($_SESSION['success_message'])) 
        {
            echo "<div class='success-message'>" . htmlspecialchars($_SESSION['success_message']) . "</div>";
            unset($_SESSION['success_message']);
        }
        if(isset($_SESSION['error_message'])) 
        {
            echo "<div class='error-message'>" . htmlspecialchars($_SESSION['error_message']) . "</div>";
            unset($_SESSION['error_message']);
        }
    ?>

    <section class="hero-section">
        <div class="hero-title">
            <h1>Supercharged.</h1>
            <p class="hero-subtitle">The new M3 family. Scary fast.</p>
        </div>
        
        <div class="hero-content">
            <div class="hero-center-image">
                <div class="hero-badge">New Release</div>
                <img src="../assets/PC/macbook-hero.png" alt="Manzanas Laptop Hero">
            </div>
        </div>
    </section>

    <div class="category-header">
        <h2>Laptops</h2>
        <p>Power that moves with you.</p>
    </div>

    <section class="comparison-section">
        <?php 
        $hasLaptops = false;
        foreach($laptopsByClass as $className => $laptops):
            if (!empty($laptops)): 
                $hasLaptops = true;
        ?>
            <div class="class-header">
                
                <h3><?= htmlspecialchars($className) ?></h3>
            </div>
            <div class="cards-container">
                <?php foreach($laptops as $laptop): ?>
                    <div class="product-card">
                        <div>
                            <div class="card-image-placeholder">
                                <img src="<?= htmlspecialchars($laptop['img_url']) ?>" alt="<?= htmlspecialchars($laptop['name']) ?>" width="250" height="220">
                            </div>
                            <h3 class="product-title"><?= htmlspecialchars($laptop['name']) ?></h3>
                            <div class="product-price">₱<?= number_format($laptop['price'], 0) ?></div>
                            
                            <div class="specs-list">
                                <?php if (!empty($laptop['specs_array'])): ?>
                                    <?php foreach($laptop['specs_array'] as $spec): ?>
                                        <div class="spec-item">
                                            <?= htmlspecialchars(trim($spec)) ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="spec-item">
                                        <?= nl2br(htmlspecialchars($laptop['product_description'])) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="product-info">
                                <div class="storage-info">
                                    <?= $laptop['ROM'] ?>GB Storage | <?= $laptop['RAM'] ?>GB RAM
                                </div>
                                <div class="stock-info">
                                    Stock: <?= $laptop['stock'] ?>
                                </div>
                                <div class="class-info">
                                    Class: <?= htmlspecialchars($laptop['class_name']) ?>
                                </div>
                            </div>
                        </div>
                        
                        <form action="addToCart.php" method="post" class="buy-form">
                            <input type="hidden" name="product_id" value="<?= $laptop['product_id'] ?>">
                            <input type="number" name="quantity" value="1" min="1" max="<?= $laptop['stock'] ?>" class="quantity-input" title="Quantity">
                            <button type="submit" name="add_to_cart" class="btn-primary">Add to Cart</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php 
            endif;
        endforeach; 
        
        if (!$hasLaptops): ?>
            <div class="cards-container">
                <div class="no-products">
                    <p>No laptops available at the moment. Please check back later!</p>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <div class="category-header section-darker" style="padding-top:40px;">
        <h2>Desktops</h2>
        <p>Muscle for your biggest ideas.</p>
    </div>

    <section class="comparison-section section-darker">
        <?php 
        $hasDesktops = false;
        foreach($desktopsByClass as $className => $desktops):
            if (!empty($desktops)): 
                $hasDesktops = true;
        ?>
            <div class="class-header">
                <h3><?= htmlspecialchars($className) ?></h3>
            </div>
            <div class="cards-container">
                <?php foreach($desktops as $desktop): ?>
                    <div class="product-card">
                        <div>
                            <div class="card-image-placeholder">
                                <img src="<?= htmlspecialchars($desktop['img_url']) ?>" alt="<?= htmlspecialchars($desktop['name']) ?>" width="250" height="180">
                            </div>
                            <h3 class="product-title"><?= htmlspecialchars($desktop['name']) ?></h3>
                            <div class="product-price">₱<?= number_format($desktop['price'], 0) ?></div>
                            
                            <div class="specs-list">
                                <?php if (!empty($desktop['specs_array'])): ?>
                                    <?php foreach($desktop['specs_array'] as $spec): ?>
                                        <div class="spec-item">
                                            <?= htmlspecialchars(trim($spec)) ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="spec-item">
                                        <?= nl2br(htmlspecialchars($desktop['product_description'])) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="product-info">
                                <div class="storage-info">
                                    <?= $desktop['ROM'] ?>GB Storage | <?= $desktop['RAM'] ?>GB RAM
                                </div>
                                <div class="stock-info">
                                    Stock: <?= $desktop['stock'] ?>
                                </div>
                                <div class="class-info">
                                    Class: <?= htmlspecialchars($desktop['class_name']) ?>
                                </div>
                            </div>
                        </div>
                        
                        <form action="addToCart.php" method="post" class="buy-form">
                            <input type="hidden" name="redirect" value="<?php echo basename($_SERVER['PHP_SELF']); ?>">
                            <input type="hidden" name="product_id" value="<?= $desktop['product_id'] ?>">
                            <input type="number" name="quantity" value="1" min="1" max="<?= $desktop['stock'] ?>" class="quantity-input" title="Quantity">
                            <button type="submit" name="add_to_cart" class="btn-primary">Add to Cart</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php 
            endif;
        endforeach; 
        
        if (!$hasDesktops): ?>
            <div class="cards-container">
                <div class="no-products">
                    <p>No desktops available at the moment. Please check back later!</p>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <?php include('../includes/footer.html'); ?>
</body>
</html>