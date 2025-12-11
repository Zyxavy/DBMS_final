<?php

    // Gin-iistart an session para magamit an session variables
    session_start();

    // Ginkakarga an database configuration
    require_once __DIR__."/../config/database.php";

    // Ginkakarga an manage class para han CRUD operations
    require_once __DIR__."/../admin/manage.php";

    // Ginkakarga an helper functions (upod checkAdmin)
    require_once __DIR__."/../includes/functions.php";

    // Ginsusuri kun admin ba an naka-login, kun diri i-redirect
    checkAdmin();

    // Pag check kun update mode ba an gamit 
    $isUpdate = isset($_GET["update"]) && $_GET["update"]==1;
    $mode_label = $isUpdate ? "UPDATE" : "INSERT";
    $next_mode = $isUpdate ? 0 : 1;
    $next_label = $isUpdate ? "INSERT" : "UPDATE";

    // Nagbubuhat hin manage object para makapagtawag hin queries
    $manage = new manage();

    // Kuhaon an tanan products upod hira category ngan class
    $products = $manage->query("SELECT p.product_id, p.name, p.price, p.stock, p.RAM, p.ROM, c.category_name, cl.class_name
    FROM products p INNER JOIN category c ON p.category_id = c.id INNER JOIN class cl ON p.class_id = cl.id", true);

    // Kuhaon an categories
    $categories = $manage->query("SELECT category_name, id FROM category",true);

    // Kuhaon an classes
    $class = $manage->query("SELECT class_name, id FROM class",true);

    // Default na mga values para ha form fields
    $product_desc = '';
    $product_name = '';
    $price = 0;
    $stock = 0;
    $RAM = 0;
    $ROM = 0;
    $category_id = 0;
    $class_id = 0;
    $product_id = '';

    // Kun update mode ngan may product_id, kuhaon an detalye han product
    if ($isUpdate && isset($_GET['product_id']) && $_GET['product_id'] != '') {

        // Kuhaon an product base ha iya ID
        $product_id = (int)$_GET['product_id'];

        // Kun may nakuha nga data, i-set an form fields
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
        
    // Function para mag-check kun successful an delete
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
<header>
    <img src="../assets/images/Admin Img.jpg" loading="lazy">
</header>
<body>

    <!-- Logout Button -->
    <div>
        <form method="post" action="../auth/logout.php">
            <input type="hidden" name="log-out" value="1">
            <button type="submit" value="Log out">Logout</button>
        </form>
        <br>
        <!-- Button para makadto ha order management -->
        <form action="admin_orders.php">
            <button type="submit">Go to Manage orders</button>
        </form>
        <form action="admin_users.php">
            <br><button type="submit">Go to Manage users</button>
        </form>
    </div>
    <br>
    <div>
        <?php

        // Kun UPDATE mode, magpapakita hin input para mag-load hin product ID
        if ($isUpdate) {
            echo '<form method="get" action="' . $_SERVER["PHP_SELF"] . '">';
            echo '<input type="hidden" name="update" value="1">';
            echo 'Enter Product ID to update: <input type="number" name="product_id" required>';
            echo '<input type="submit" value="Load Product">';
            echo '</form><br>';
        }
        ?>

        <!-- Main Form para INSERT/UPDATE -->
        <form method="post" action="../admin/dashboard.php">
            <input type="hidden" name="is_pressed_insert" value="true">
            <input type="hidden" name="mode" value="<?= $mode_label ?>">

             <!-- name han product -->
            Product Name: <input type="text" name="Product_name" value="<?= htmlspecialchars($product_name) ?>"><br>
            Choose categories:<br>

            <?php

            // Pagcheck kun may sulod an $categories
            if($categories != false){

                // Ginlilibot an kada category nga nakuha tikang ha database
                foreach($categories as $col){

                    // Ginkuha an category ID (gin-cast ha int para sigurado nga numero)
                    $id = (int)$col["id"];

                    // Ginkuha an category name ngan gin-escape para safe i-display
                    $name = htmlspecialchars($col["category_name"]);

                    // Kun an naka-save nga category_id amo ini kun dire hihimuon checked
                    $checked = ($category_id == $id) ? 'checked' : '';

                    // Radio button para han category
                    echo '<input type="radio" id="cat'.$id.'" name="category" value="'.$id.'" '.$checked.'>';

                    // Gin-display an ngaran han category
                    echo '<label for="cat'.$id.'">'.$name.'</label><br>';
                }
            }
            // Message kun waray nakuha nga category
            else {
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
        if (isset($_GET["inserted"]) && $_GET["inserted"] == 1) {
            echo "<p id='updateStatTrue'>Inserted</p>";
        } elseif (isset($_GET["updated"]) && $_GET["updated"] == 1) {
            echo "<p id='updateStatTrue'>Updated</p>";
        } elseif (isset($_GET["inserted"]) || isset($_GET["updated"])) {
            echo "<p id='updateStatFalse'>Error there is a problem</p>";
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