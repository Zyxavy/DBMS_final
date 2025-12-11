<?php
session_start();

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../Classes/ProductClass.php";
require_once __DIR__ . "/../includes/functions.php";

if(!isset($_SESSION['user_id']))
{
    session_destroy();
    redirectToPage("../index.php"); 
    exit();
}

include('../includes/navbar.html');

$products = []; 

try {
    $categoryId = 1;
    $productsInstance = new Products();
    
    $fetchedProducts = $productsInstance->getProductsbyCategory($categoryId, 'name', 'ASC');

    if ($fetchedProducts && is_array($fetchedProducts)) {
        foreach ($fetchedProducts as $dbProduct) {
            $description = $dbProduct['product_description'] ?? 'View details for specifications.';
            
            $specsArray = [];
            if (!empty($description)) {
                $specsArray = explode('<br>', $description);
            }
            
            $products[] = [
                'product_id' => $dbProduct['product_id'],
                'name' => $dbProduct['name'],
                'stock' => $dbProduct['stock'] ?? 1,
                'price' => $dbProduct['price'] ?? 0,
                'img_url' => $dbProduct['img_url'] ?? '../assets/images/500x500Mobile-placeholder.png',
                'product_description' => $description,
                'specs_array' => $specsArray,
                'ROM' => $dbProduct['ROM'] ?? 'N/A',
                'RAM' => $dbProduct['RAM'] ?? 'N/A',
                'class_name' => $dbProduct['class_name'] ?? ''
            ];
        }
    }

} catch (Exception $e) {
    error_log("DB Error in phones.php: " . $e->getMessage());
    $_SESSION['error_message'] = "Could not connect to the database or fetch product list.";
    $products = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manzanas M-phone 17 Series</title>
    <link rel="stylesheet" href="../pages/phoneStyles.css">
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
        }
        
        .price-tag {
            font-size: 20px;
            font-weight: bold;
            color: #0071e3;
            margin-bottom: 5px;
        }
        
        .storage-info {
            color: #666;
            margin-bottom: 5px;
        }
    </style>
</head>
<body class="landing-page">
    <?php
        if(isset($_SESSION['success_message'])) 
        {
            echo "<div style='color: green; padding: 10px; border: 1px solid green; background-color: #e6ffe6; margin: 10px auto; max-width: 800px; border-radius: 5px;'>" . htmlspecialchars($_SESSION['success_message']) . "</div>";
            unset($_SESSION['success_message']);
        }
        if(isset($_SESSION['error_message'])) 
        {
            echo "<div style='color: red; padding: 10px; border: 1px solid red; background-color: #ffe6e6; margin: 10px auto; max-width: 800px; border-radius: 5px;'>" . htmlspecialchars($_SESSION['error_message']) . "</div>";
            unset($_SESSION['error_message']);
        }
    ?>

    <section class="hero-section">
        <div class="hero-title">
            <h1>M-phone 17 series</h1>
        </div>
        
        <div class="hero-content">
            <div class="hero-features">
                <h2>Power Redefined</h2>
                <div class="feature-item">Titanium frame 2.0</div>
                <div class="feature-item">Available in 8, 12 GB RAM</div>
                <div class="feature-item">Available in 256 GB, 512 GB, 1TB storage</div>
                <div class="feature-item">M15 Bionic</div>
            </div>

            <div class="hero-phones">
                <div class="phone-stack">
                    <img src="../assets/Phones/Iphone_series.jpg" alt="M-phone 17 Stack" class="phone-stack-img">
                </div>
            </div>

            <div class="hero-pricing">
                <?php if (!empty($products)): ?>
                    <?php foreach(array_slice($products, 0, 3) as $product): ?>
                        <div class="price-item">
                            <div class="price-tag">₱<?= number_format($product['price'], 0) ?></div>
                            <div class="price-label"><?= htmlspecialchars($product['name']) ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="price-item">
                        <div class="price-tag">$799</div>
                        <div class="price-label">Basic Model</div>
                    </div>
                    <div class="price-item">
                        <div class="price-tag">$999</div>
                        <div class="price-label">Mid Level</div>
                    </div>
                    <div class="price-item">
                        <div class="price-tag">$1499</div>
                        <div class="price-label">Flagship</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="comparison-section">
        <div class="comp-header">
            <h2>Which phone is best for you?</h2>
        </div>

        <div class="logo-center">
            <i class="fa-brands fa-apple"></i> MANZANAS
        </div>

        <div class="cards-container">
            <?php 
                if (count($products) > 0) {
                    foreach($products as $product): ?>
                        <div class="product-card">
                            <div class="card-image-placeholder">
                                <img src="<?= htmlspecialchars($product['img_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            </div>
                            <h3 class="product-title"><?= htmlspecialchars($product['name']) ?></h3>
                            
                            <div class="specs-list">
                                <?php if (!empty($product['specs_array'])): ?>
                                    <?php foreach($product['specs_array'] as $spec): ?>
                                        <div class="spec-item">
                                            <?= htmlspecialchars(trim($spec)) ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="spec-item">
                                        <?= nl2br(htmlspecialchars($product['product_description'])) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="product-info">
                                <div class="price-tag">₱<?= number_format($product['price'], 2) ?></div>
                                <div class="storage-info">
                                    <?= $product['ROM'] ?>GB ROM | <?= $product['RAM'] ?>GB RAM
                                </div>
                                <div class="stock-info">
                                    Stock: <?= $product['stock'] ?>
                                </div>
                            </div>

                            <form action="addToCart.php" method="post" class="buy-form">
                                <input type="hidden" name="redirect" value="<?php echo basename($_SERVER['PHP_SELF']); ?>">
                                <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                <input type="number" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>" class="quantity-input" title="Quantity">
                                <button type="submit" name="add_to_cart" class="btn-primary">Add to Cart</button>
                            </form>
                        </div>
                    <?php endforeach; 
                } else {
                    echo '<div style="width: 100%; text-align: center; padding: 20px; font-size: 1.2em;">No phones are currently available. Please check back later!</div>';
                }
            ?>
        </div>
    </section>

    <section class="disclaimer-section">
        <div class="disclaimer-content">
            <p>*Accessibility of some features may vary across regions. Product pictures, videos and display contents on the foregoing pages are provided for reference only. Actual product features and specifications (including but not limited to appearance, color, and size), as well as actual display contents (including but not limited to backgrounds, UI, and icons) may vary.</p>
            <p>**All data in the foregoing pages are theoretical values obtained by MANZANAS internal laboratories through tests carried out under particular conditions. For more information, refer to the details of the aforementioned products. Actual data may vary owing to differences in individual products, software versions, application conditions, and environmental factors. All data is subject to actual usage.</p>
            <p>***Due to real-time changes involving product batches, production and supply factors, in order to provide accurate product information, specifications, and features, MANZANAS may make real-time adjustments to text descriptions and images in the foregoing pages, so that they match the product performance, specifications, indexes, and components of the actual product. Product information is subject to such changes and adjustments without notice.</p>
        </div>
    </section>

    <?php include('../includes/footer.html'); ?>
</body>
</html>