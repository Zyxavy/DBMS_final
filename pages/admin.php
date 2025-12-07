<?php 
    require_once __DIR__."/../config/database.php";
    require_once __DIR__."/../admin/manage.php";
?>
<?php
    $isUpdate = (isset($_GET["update"]) && $_GET["update"]==1);
    $mode_label = $isUpdate ? "UPDATE" : "INSERT";
    $next_mode = $isUpdate ? 0 : 1;
    $next_label = $isUpdate ? "INSERT" : "UPDATE";

    $manage = new manage();
    $products = $manage->query("SELECT p.product_id, p.name, p.price, p.stock, p.RAM, p.ROM, c.category_name, cl.class_name
    FROM products p INNER JOIN category c ON p.category_id = c.id INNER JOIN class cl ON p.class_id = cl.id", true);

    $categories = $manage->query("SELECT category_name, id FROM category",true);
    $class = $manage->query("SELECT class_name, id FROM class",true);

    function getDeleteStatus(){
        return isset($_GET["deleteStat"])&&$_GET["deleteStat"]=="1";
    }
?>


<!---->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager</title>
    
</head>
<body>
    <div>
        <form method="post" action="../admin/dashboard.php">
            <input type="hidden" name="is_pressed_insert" value="true">
            <input type="hidden" name="mode" value="<?= $mode_label ?>">
            Product_ID <input type="number" name="product_id" <?= ($isUpdate==1)?"":"disabled" ?>><br>
            Product Name: <input type="text" name="Product_name"><br>
            Choose categories<br>
            <?php
                if($categories != false){
                    foreach($categories as $col){
                    $id = (int)$col["id"];
                    $name= htmlspecialchars($col["category_name"]);
                    echo '<input type="radio" id="cat'.$id.'" name="category" value="'.$id.'">';
                    echo '<label for="cat'.$id.'">'.$name.'</label><br>';
                }
                }else{
                    echo "No categories found, contact support<br>";
                }
            ?>
            Choose class ID <br>
            <?php
                if($class != false){
                    foreach($class as $col){
                        $id= (int)$col["id"];
                        $name= htmlspecialchars($col["class_name"]);
                        echo '<input type="radio" id="class'.$id.'" name= "class" value="'.$id.'">';
                        echo '<label for="class'.$id.'">'.$name.'</label><br>';
                    }
                }else{
                    echo "No classes found, contact support<br>";
                }
            ?>
            Price: <input type="number" name="price"><br>
            stock: <input type="number" name="stock"><br>
            RAM: <input type="number" name="RAM"><br>
            ROM: <input type="number" name="ROM"><br>
            <button type="submit"><?php echo $mode_label;?></button>
        </form>
        <br>
        <form method="get" action=<?php echo $_SERVER["PHP_SELF"]?>>
            <input type="hidden" name="update" value="<?= $next_mode ?>">
            <br> switch to: 
            <input type="submit" value="<?= $next_label?>">
        </form>
        <?php
           if (isset($_GET["inserted"])) {
                echo $_GET["inserted"] == 1 ? "<p>Inserted</p>" : "<p>Error there is a problem</p>";
            }
        ?>
    </div>
    <div>
        <form method = "post" action="../admin/dashboard.php">
            <input type="hidden" name="mode" value="DELETE">
            <br>Product ID<input type="number" name="product_id">
            <input type="submit" value="Delete">
            <?=  (isset($_GET["deleteStat"])) ?(getDeleteStatus())? "<p>Deletion success</p>":"<p>Unsuccessful deletion</p>":""?>
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
                            echo "<td>P " .htmlspecialchars($select['price']). "</td>";
                            echo "<td>" .htmlspecialchars($select['stock']). "</td>";
                            echo "<td>" .htmlspecialchars($select['RAM'])." GB</td>";
                            echo "<td>" .htmlspecialchars($select['ROM'])." GB</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td>No products found</td></tr>";
                    }
                ?>
            </table>
        </form>
        <br><form method="post" action="../admin/dashboard.php">
            <input type="hidden" name = "mode" value="RESET">
            <p>Warning this is an irreversable attempt. Table reset</p>
            <input type="submit" value="TRUNICATE OR RESET TABLE">
            <?php
             if(isset($_GET["reset"])){
                echo ($_GET["reset"]==1)? "<p>Table successfully Reset</p>":"<p>Reset not successful</p>";
             }
             ?>
        </form>
    </div>
</body>
</html>