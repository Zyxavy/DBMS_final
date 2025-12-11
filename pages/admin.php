<?php
    session_start();
    require_once __DIR__."/../config/database.php";
    require_once __DIR__."/../admin/manage.php";
    require_once __DIR__."/../includes/functions.php";

    checkAdmin();

    $isUpdate = isset($_GET["update"]) && $_GET["update"]==1;
    $mode_label = $isUpdate ? "UPDATE" : "INSERT";
    $next_mode = $isUpdate ? 0 : 1;
    $next_label = $isUpdate ? "INSERT" : "UPDATE";

    $manage = new manage();
    $products = $manage->query("SELECT p.product_id, p.name, p.price, p.stock, p.RAM, p.ROM, c.category_name, cl.class_name
    FROM products p INNER JOIN category c ON p.category_id = c.id INNER JOIN class cl ON p.class_id = cl.id", true);

    $categories = $manage->query("SELECT category_name, id FROM category",true);
    $class = $manage->query("SELECT class_name, id FROM class",true);

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
        
    
    function getDeleteStatus(){
        return isset($_GET["deleteStat"])&&$_GET["deleteStat"]=="1";
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager</title>
    
</head>
<body>
    <div>
        <form method="post" action="../auth/logout.php">
            <input type="hidden" name="log-out" value="1">
            <button type="submit" value="Log out">Logout</button>
        </form>
        <br>
        <form action="admin_orders.php">
            <button type="submit">Go to Manage orders</button>
        </form>
    </div>
    <br>
    <div>
        <?php
        if ($isUpdate) {
            echo '<form method="get" action="' . $_SERVER["PHP_SELF"] . '">';
            echo '<input type="hidden" name="update" value="1">';
            echo 'Enter Product ID to update: <input type="number" name="product_id" required>';
            echo '<input type="submit" value="Load Product">';
            echo '</form><br>';
        }
        ?>
        <form method="post" action="../admin/dashboard.php">
            <input type="hidden" name="is_pressed_insert" value="true">
            <input type="hidden" name="mode" value="<?= $mode_label ?>">
            Product Name: <input type="text" name="Product_name" value="<?= htmlspecialchars($product_name) ?>"><br>
            Choose categories:<br>
            <?php
            if($categories != false){
                foreach($categories as $col){
                    $id = (int)$col["id"];
                    $name = htmlspecialchars($col["category_name"]);
                    $checked = ($category_id == $id) ? 'checked' : '';
                    echo '<input type="radio" id="cat'.$id.'" name="category" value="'.$id.'" '.$checked.'>';
                    echo '<label for="cat'.$id.'">'.$name.'</label><br>';
                }
            }else{
                echo "No categories found, contact support<br>";
            }
            ?>
            <br>Choose class ID:<br>
            <?php
            if($class != false){
                foreach($class as $col){
                    $id = (int)$col["id"];
                    $name = htmlspecialchars($col["class_name"]);
                    $checked = ($class_id == $id) ? 'checked' : '';
                    echo '<input type="radio" id="class'.$id.'" name="class" value="'.$id.'" '.$checked.'>';
                    echo '<label for="class'.$id.'">'.$name.'</label><br>';
                }
            }else{
                echo "No classes found, contact support<br>";
            }
            ?>
            Price: <input type="number" name="price" value="<?= htmlspecialchars($price) ?>"><br>
            Stock: <input type="number" name="stock" value="<?= htmlspecialchars($stock) ?>"><br>
            RAM: <input type="number" name="RAM" value="<?= htmlspecialchars($RAM) ?>"><br>
            ROM: <input type="number" name="ROM" value="<?= htmlspecialchars($ROM) ?>"><br>
            Product Description:<br>
            <textarea name="descp" rows="4" cols="50"><?= htmlspecialchars($product_desc) ?></textarea><br>
            <button type="submit"><?= $mode_label ?></button>
        </form>

        <br>
        <form method="get" action=<?php echo $_SERVER["PHP_SELF"]?>>
            <input type="hidden" name="update" value="<?= $next_mode ?>">
            <br> switch to: 
            <input type="submit" value="<?= $next_label?>">
        </form>
        <?php
           if (isset($_GET["inserted"])) {
                echo $_GET["inserted"] == 1 ? "<p id='updateStatTrue'>Inserted</p>" : "<p id='updateStatFalse'>Error there is a problem</p>";
            }
        ?>
    </div>
    <div>
        <form method = "post" action="../admin/dashboard.php">
            <input type="hidden" name="mode" value="DELETE">
            <br>Product ID<input type="number" name="product_id">
            <input type="submit" value="Delete">
            <?=  (isset($_GET["deleteStat"])) ?((getDeleteStatus())? "<p>Deletion success</p>":"<p id='updateStatFalse'>Unsuccessful deletion</p>"):""?>
            <table>
                <tr>
                <th>Product_ID</th><th>Product_name</th><th>class</th><th>category</th>
                <th>price</th><th>stock</th><th>RAM</th><th>ROM</th>
                </tr>
                <?php
                    if ($products) {
                        foreach ($products as $select) {
                            echo "<tr>";
                            echo "<td>" .htmlspecialchars($select['product_id']). "</td>";
                            echo "<td>" .htmlspecialchars($select['name']). "</td>";
                            echo "<td>" .htmlspecialchars($select['class_name']). "</td>";
                            echo "<td>" .htmlspecialchars($select['category_name']). "</td>";
                            echo "<td>P " .htmlspecialchars(number_format($select['price'],2)). "</td>";
                            echo "<td>" .htmlspecialchars($select['stock']). "</td>";
                            echo "<td>" .htmlspecialchars($select['RAM'])." GB</td>";
                            echo "<td>" .htmlspecialchars($select['ROM'])." GB</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td id='updateStatFalse'>No products found</td></tr>";
                    }
                ?>
            </table>
        </form>
        <br><form method="post" action="../admin/dashboard.php">
            <input type="hidden" name = "mode" value="RESET">
            <p>Warning this is an irreversable attempt. Table reset</p>
            <input type="submit" value="TRUNCATE OR RESET TABLE">
            <?php
             if(isset($_GET["reset"])){
                echo ($_GET["reset"]==1)? "<p id='updateStatTrue'>Table successfully Reset</p>":"<p id='updateStatFalse'>Reset not successful</p>";
             }
             ?>
        </form>
    </div>
</body>
</html>

<style>
    table, th, td {
    border: 1px solid black;
    }
    #updateStatFalse{
        background-color: rgba(255, 0, 0, 0.3);
    }
    #updateStatTrue{
        background-color: rgba(0, 255, 0, 0.3);
    }
</style>