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
    SELECT p.product_id, p.name, p.price, p.stock, p.RAM, p.ROM, 
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
    <title>Admin Manager</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; padding-bottom: 50px; }
        header img { width: 100%; max-height: 250px; object-fit: cover; border-radius: 10px; }

        .top-actions { margin: 20px 0; display: flex; gap: 15px; }
        .top-actions form button { padding: 8px 15px; cursor: pointer; }

        .container { display: flex; gap: 25px; margin-top: 25px; }
        .card { border: 1px solid #ccc; padding: 20px; border-radius: 10px; width: 100%; background: #fafafa; }

        .form-section { margin-bottom: 20px; }
        .form-section label { font-weight: bold; display: block; margin-top: 10px; }

        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table th, table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        table th { background: #f3f3f3; }

        #updateStatTrue { background: rgba(0,255,0,0.3); padding: 5px; }
        #updateStatFalse { background: rgba(255,0,0,0.3); padding: 5px; }
    </style>
</head>

<body>

<header>
    <img src="../assets/images/Admin Img.jpg" loading="lazy">
</header>

<!-- Top Buttons -->
<div class="top-actions">
    <form method="post" action="../auth/logout.php">
        <input type="hidden" name="log-out" value="1">
        <button type="submit">Logout</button>
    </form>

    <form action="admin_orders.php">
        <button type="submit">Manage Orders</button>
    </form>

    <form action="admin_users.php">
        <button type="submit">Manage Users</button>
    </form>
</div>

<div class="container">

    <!-- LEFT SIDE (Insert/Update Product Form) -->
    <div class="card">

        <?php if ($isUpdate): ?>
            <form method="get" action="<?= $_SERVER["PHP_SELF"] ?>">
                <input type="hidden" name="update" value="1">
                <label>Enter Product ID to Update:</label>
                <input type="number" name="product_id" required>
                <input type="submit" value="Load Product">
            </form>
            <hr>
        <?php endif; ?>

        <h2><?= $mode_label ?> PRODUCT</h2>

        <form method="post" action="../admin/dashboard.php">
            <input type="hidden" name="is_pressed_insert" value="true">
            <input type="hidden" name="mode" value="<?= $mode_label ?>">

            <!-- Product Name -->
            <label>Product Name:</label>
            <input type="text" name="Product_name" value="<?= htmlspecialchars($product_name) ?>">

            <!-- Category -->
            <label>Choose Category:</label>
            <?php if ($categories): ?>
                <?php foreach ($categories as $col): ?>
                    <?php 
                        $id = (int)$col["id"];
                        $name = htmlspecialchars($col["category_name"]);
                        $checked = ($category_id == $id) ? 'checked' : '';
                    ?>
                    <div>
                        <input type="radio" id="cat<?= $id ?>" name="category" value="<?= $id ?>" <?= $checked ?>>
                        <label for="cat<?= $id ?>"><?= $name ?></label>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No categories found.</p>
            <?php endif; ?>

            <!-- Class -->
            <label>Choose Class:</label>
            <?php if ($class): ?>
                <?php foreach ($class as $col): ?>
                    <?php 
                        $id = (int)$col["id"];
                        $name = htmlspecialchars($col["class_name"]);
                        $checked = ($class_id == $id) ? 'checked' : '';
                    ?>
                    <div>
                        <input type="radio" id="class<?= $id ?>" name="class" value="<?= $id ?>" <?= $checked ?>>
                        <label for="class<?= $id ?>"><?= $name ?></label>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No classes found.</p>
            <?php endif; ?>

            <!-- Inputs -->
            <label>Price:</label>
            <input type="number" name="price" value="<?= htmlspecialchars($price) ?>">

            <label>Stock:</label>
            <input type="number" name="stock" value="<?= htmlspecialchars($stock) ?>">

            <label>RAM:</label>
            <input type="number" name="RAM" value="<?= htmlspecialchars($RAM) ?>">

            <label>ROM:</label>
            <input type="number" name="ROM" value="<?= htmlspecialchars($ROM) ?>">

            <label>Product Description:</label>
            <textarea name="descp" rows="4"><?= htmlspecialchars($product_desc) ?></textarea>

            <br><br>
            <button type="submit"><?= $mode_label ?></button>
        </form>

        <br><hr>

        <!-- Switch Mode -->
        <form method="get" action="<?= $_SERVER["PHP_SELF"] ?>">
            <input type="hidden" name="update" value="<?= $next_mode ?>">
            <p>Switch to:</p>
            <input type="submit" value="<?= $next_label ?>">
        </form>

        <?php
        if (isset($_GET["inserted"]) && $_GET["inserted"] == 1) {
            echo "<p id='updateStatTrue'>Inserted</p>";
        } elseif (isset($_GET["updated"]) && $_GET["updated"] == 1) {
            echo "<p id='updateStatTrue'>Updated</p>";
        } elseif (isset($_GET["inserted"]) || isset($_GET["updated"])) {
            echo "<p id='updateStatFalse'>Error: Problem occurred.</p>";
        }
        ?>

    </div>

    <!-- RIGHT SIDE (Delete & Product Table) -->
    <div class="card">

        <h2>DELETE PRODUCT</h2>
        <form method="post" action="../admin/dashboard.php">
            <input type="hidden" name="mode" value="DELETE">
            <label>Product ID:</label>
            <input type="number" name="product_id">
            <input type="submit" value="Delete">
        </form>

        <?= (isset($_GET["deleteStat"])) 
            ? (getDeleteStatus() ? "<p id='updateStatTrue'>Deletion success</p>" 
                                 : "<p id='updateStatFalse'>Deletion failed</p>")
            : ""
        ?>

        <h2>PRODUCT LIST</h2>
        <table>
            <tr>
                <th>ID</th><th>Name</th><th>Class</th><th>Category</th>
                <th>Price</th><th>Stock</th><th>RAM</th><th>ROM</th>
            </tr>
            <?php if ($products): ?>
                <?php foreach ($products as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['product_id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['class_name']) ?></td>
                        <td><?= htmlspecialchars($row['category_name']) ?></td>
                        <td>P <?= number_format($row['price'], 2) ?></td>
                        <td><?= htmlspecialchars($row['stock']) ?></td>
                        <td><?= htmlspecialchars($row['RAM']) ?> GB</td>
                        <td><?= htmlspecialchars($row['ROM']) ?> GB</td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8">No products found</td></tr>
            <?php endif; ?>
        </table>

        <hr>

        <h2>RESET TABLE</h2>
        <form method="post" action="../admin/dashboard.php">
            <input type="hidden" name="mode" value="RESET">
            <p>Warning: This action cannot be undone.</p>
            <input type="submit" value="TRUNCATE / RESET TABLE">
        </form>

        <?php
        if (isset($_GET["reset"])) {
            echo ($_GET["reset"] == 1)
                ? "<p id='updateStatTrue'>Table Reset Successful</p>"
                : "<p id='updateStatFalse'>Reset Failed</p>";
        }
        ?>

    </div>
</div>

</body>
</html>
