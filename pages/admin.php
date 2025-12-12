<?php
session_start();

require_once __DIR__."/../config/database.php";
require_once __DIR__."/../admin/manage.php";
require_once __DIR__."/../includes/functions.php";

checkAdmin();

$isUpdate = isset($_GET["update"]) && $_GET["update"] == 1;
$mode_label = $isUpdate ? "UPDATE" : "INSERT";
$next_mode = $isUpdate ? 0 : 1;
$next_label = $isUpdate ? "INSERT" : "UPDATE";

$manage = new manage();

$products = $manage->query("
    SELECT p.product_id, p.name, p.price, p.stock, p.img_url, p.RAM, p.ROM, 
           c.category_name, cl.class_name
    FROM products p 
    INNER JOIN category c ON p.category_id = c.id 
    INNER JOIN class cl ON p.class_id = cl.id
", true);

$categories = $manage->query("SELECT category_name, id FROM category", true);
$class = $manage->query("SELECT class_name, id FROM class", true);

$product_desc = '';
$product_name = '';
$price = 0;
$stock = 0;
$img_url = '';
$RAM = 0;
$ROM = 0;
$category_id = 0;
$class_id = 0;
$product_id = '';

if ($isUpdate && isset($_GET['product_id']) && $_GET['product_id'] != '') {
    $product_id = (int)$_GET['product_id'];
    $product = $manage->query("SELECT * FROM products WHERE product_id = $product_id", true);

    if ($product && count($product) > 0) {
        $product = $product[0];
        $product_name = $product['name'];
        $price = $product['price'];
        $stock = $product['stock'];
        $img_url = $product['img_url'];
        $RAM = $product['RAM'];
        $ROM = $product['ROM'];
        $product_desc = $product['product_description'] ?? "";
        $category_id = $product['category_id'];
        $class_id = $product['class_id'];
    }
}

function getDeleteStatus() {
    return isset($_GET["deleteStat"]) && $_GET["deleteStat"] == "1";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Product Manager</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; padding-bottom: 50px; background: #f5f5f5; }
        header img { width: 100%; max-height: 250px; object-fit: cover; border-radius: 10px; }

        .panel { border: 1px solid #ccc; padding: 15px; border-radius: 10px; margin-bottom: 25px; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .panel h3, .panel h2 { margin-top: 0; color: #333; border-bottom: 2px solid #ff0000ff; padding-bottom: 10px; }
        
        .action-buttons { display: flex; gap: 10px; flex-wrap: wrap; }
        .action-buttons form { margin: 0; }

        .container { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-top: 25px; }
        @media (max-width: 968px) { .container { grid-template-columns: 1fr; } }

        form { margin-bottom: 15px; }
        form label { font-weight: bold; display: block; margin-top: 10px; color: #555; }
        
        input[type="text"], input[type="number"], textarea {
            width: 100%; padding: 8px; margin-top: 3px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;
        }
        
        textarea { resize: vertical; font-family: Arial, sans-serif; }

        input[type="submit"], button { 
            padding: 8px 16px; cursor: pointer; margin-top: 10px; margin-right: 5px;
            border: none; border-radius: 4px; font-weight: bold; transition: background 0.3s;
        }
        
        button[type="submit"], input[type="submit"] { background: #850808ff; color: white; }
        button[type="submit"]:hover, input[type="submit"]:hover { background: #850323ff; }
        
        button[type="reset"] { background: #6c757d; color: white; }
        button[type="reset"]:hover { background: #5a6268; }
        
        .btn-danger { background: #dc3545; color: white; }
        .btn-danger:hover { background: #c82333; }
        
        .btn-secondary { background: #6c757d; color: white; }
        .btn-secondary:hover { background: #5a6268; }
        
        .btn-warning { background: #ffc107; color: #000; }
        .btn-warning:hover { background: #e0a800; }

        table { width: 100%; border-collapse: collapse; margin-top: 15px; background: #fff; }
        table th, table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        table th { background: #c40202ff; color: white; font-weight: bold; }
        table tr:nth-child(even) { background: #f9f9f9; }
        table tr:hover { background: #f0f0f0; }

        .radio-group { display: flex; flex-direction: column; gap: 8px; margin-top: 5px; }
        .radio-group > div { display: flex; align-items: center; }
        .radio-group input[type="radio"] { margin: 0; margin-right: 8px; }
        .radio-group label { font-weight: normal; margin: 0; cursor: pointer; }

        .message { padding: 12px; border-radius: 5px; margin-bottom: 15px; font-weight: bold; }
        .success-message { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error-message { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        
        .warning-box {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 12px;
            border-radius: 5px;
            margin: 10px 0;
            color: #856404;
        }
        
        hr { border: none; border-top: 1px solid #ddd; margin: 20px 0; }
    </style>
</head>

<body>

<header>
    <img src="../assets/images/Admin Img.jpg" loading="lazy" alt="Admin Banner">
</header>

<!-- Top Navigation -->
<div class="panel">
    <div class="action-buttons">
        <form method="post" action="../auth/logout.php">
            <input type="hidden" name="log-out" value="1">
            <button type="submit" class="btn-secondary">Logout</button>
        </form>

        <form action="admin_orders.php">
            <button type="submit">Manage Orders</button>
        </form>

        <form action="admin_users.php">
            <button type="submit">Manage Users</button>
        </form>
    </div>
</div>

<!-- Status Messages -->
<?php
$message = '';
$messageType = '';

if (isset($_GET["inserted"])) {
    $message = $_GET["inserted"] == 1 ? "Product inserted successfully!" : "Failed to insert product.";
    $messageType = $_GET["inserted"] == 1 ? "success" : "error";
} elseif (isset($_GET["updated"])) {
    $message = $_GET["updated"] == 1 ? "Product updated successfully!" : "Failed to update product.";
    $messageType = $_GET["updated"] == 1 ? "success" : "error";
} elseif (isset($_GET["deleteStat"])) {
    $message = getDeleteStatus() ? "Product deleted successfully!" : "Failed to delete product.";
    $messageType = getDeleteStatus() ? "success" : "error";
} elseif (isset($_GET["reset"])) {
    $message = $_GET["reset"] == 1 ? "Table reset successful!" : "Failed to reset table.";
    $messageType = $_GET["reset"] == 1 ? "success" : "error";
}

if ($message):
?>
    <div class="panel">
        <div class="message <?= $messageType === 'success' ? 'success-message' : 'error-message' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    </div>
<?php endif; ?>

<div class="container">

    <!-- LEFT SIDE (Insert/Update Product Form) -->
    <div class="panel">

        <?php if ($isUpdate): ?>
            <h2>Load Product for Update</h2>
            <form method="get" action="<?= $_SERVER["PHP_SELF"] ?>">
                <input type="hidden" name="update" value="1">
                <label for="load_product_id">Enter Product ID:</label>
                <input type="number" id="load_product_id" name="product_id" required min="1">
                <button type="submit">Load Product</button>
            </form>
            <hr>
        <?php endif; ?>

        <h2><?= $mode_label ?> Product</h2>

        <form method="post" action="../admin/dashboard.php">
            <input type="hidden" name="is_pressed_insert" value="true">
            <input type="hidden" name="mode" value="<?= $mode_label ?>">
            
            <?php if ($isUpdate && $product_id): ?>
                <input type="hidden" name="product_id" value="<?= $product_id ?>">
            <?php endif; ?>

            <label for="product_name">Product Name:</label>
            <input type="text" id="product_name" name="Product_name" value="<?= htmlspecialchars($product_name) ?>" required>

            <label>Category:</label>
            <div class="radio-group">
                <?php if ($categories): ?>
                    <?php foreach ($categories as $col): ?>
                        <?php 
                            $id = (int)$col["id"];
                            $name = htmlspecialchars($col["category_name"]);
                            $checked = ($category_id == $id) ? 'checked' : '';
                        ?>
                        <div>
                            <input type="radio" id="cat<?= $id ?>" name="category" value="<?= $id ?>" <?= $checked ?> required>
                            <label for="cat<?= $id ?>"><?= $name ?></label>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No categories found.</p>
                <?php endif; ?>
            </div>

            <label>Class:</label>
            <div class="radio-group">
                <?php if ($class): ?>
                    <?php foreach ($class as $col): ?>
                        <?php 
                            $id = (int)$col["id"];
                            $name = htmlspecialchars($col["class_name"]);
                            $checked = ($class_id == $id) ? 'checked' : '';
                        ?>
                        <div>
                            <input type="radio" id="class<?= $id ?>" name="class" value="<?= $id ?>" <?= $checked ?> required>
                            <label for="class<?= $id ?>"><?= $name ?></label>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No classes found.</p>
                <?php endif; ?>
            </div>

            <label for="product_price">Price (₱):</label>
            <input type="number" id="product_price" name="price" value="<?= htmlspecialchars($price) ?>" step="0.01" required min="0">

            <label for="product_stock">Stock:</label>
            <input type="number" id="product_stock" name="stock" value="<?= htmlspecialchars($stock) ?>" required min="0">

            <label for="product_ram">RAM (GB):</label>
            <input type="number" id="product_ram" name="RAM" value="<?= htmlspecialchars($RAM) ?>" required min="0">

            <label for="product_rom">ROM (GB):</label>
            <input type="number" id="product_rom" name="ROM" value="<?= htmlspecialchars($ROM) ?>" required min="0">

            <label for="product_desc">Product Description:</label>
            <textarea id="product_desc" name="descp" rows="5"><?= htmlspecialchars($product_desc) ?></textarea>

            <label for="img_url">Image path:</label>
            <textarea id="img_url" name="img_url" rows="5" placeholder="../assets/PC/mac-mini.png"><?= htmlspecialchars($img_url) ?></textarea>


            <button type="submit"><?= $mode_label ?> Product</button>
            <button type="reset" class="btn-secondary">Reset Form</button>
        </form>

        <hr>

        <h3>Switch Mode</h3>
        <form method="get" action="<?= $_SERVER["PHP_SELF"] ?>">
            <input type="hidden" name="update" value="<?= $next_mode ?>">
            <p>Currently in <strong><?= $mode_label ?></strong> mode.</p>
            <button type="submit" class="btn-warning">Switch to <?= $next_label ?> Mode</button>
        </form>

    </div>

    <!-- RIGHT SIDE (Delete & Product Table) -->
    <div class="panel">

        <h2>Delete Product</h2>
        <form method="post" action="../admin/dashboard.php" onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.');">
            <input type="hidden" name="mode" value="DELETE">
            <label for="delete_product_id">Product ID:</label>
            <input type="number" id="delete_product_id" name="product_id" required min="1">
            <button type="submit" class="btn-danger">Delete Product</button>
        </form>

        <hr>

        <h2>All Products (<?= count($products) ?> total)</h2>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>RAM</th>
                        <th>ROM</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($products): ?>
                        <?php foreach ($products as $row): ?>
                            <tr>
                                <td><strong>#<?= htmlspecialchars($row['product_id']) ?></strong></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['class_name']) ?></td>
                                <td><?= htmlspecialchars($row['category_name']) ?></td>
                                <td style="font-weight: bold;">₱<?= number_format($row['price'], 2) ?></td>
                                <td style="text-align: center;"><?= htmlspecialchars($row['stock']) ?></td>
                                <td style="text-align: center;"><?= htmlspecialchars($row['RAM']) ?> GB</td>
                                <td style="text-align: center;"><?= htmlspecialchars($row['ROM']) ?> GB</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center; color: #999; padding: 20px;">No products found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <hr>

        <h2>Reset Database Table</h2>
        <div class="warning-box">
            <strong>Warning:</strong> This will permanently delete ALL products from the database. This action cannot be undone!
        </div>
        <form method="post" action="../admin/dashboard.php" onsubmit="return confirm('FINAL WARNING \n\nThis will DELETE ALL PRODUCTS from the database!\n\nAre you absolutely sure you want to continue?');">
            <input type="hidden" name="mode" value="RESET">
            <button type="submit" class="btn-danger">TRUNCATE / RESET TABLE</button>
        </form>

    </div>
</div>

</body>
</html>