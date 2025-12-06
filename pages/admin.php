<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager</title>
    <?php 
    require_once __DIR__."/../config/database.php";
    require_once __DIR__."/../admin/manage.php";?>
</head>
<body>
    <div>
        <form method="post" action="../admin/dashboard.php">
            <input type="hidden" name="is_pressed_insert" value="true">
            Product Name: <input type="text" name="Product_name"><br>
            Choose categories<br>
            <?php

                $manage = new manage();
                $categories = $manage->query("SELECT category_name, id FROM category");
                if($categories != false){
                    foreach($categories as $col){
                        echo '<input type="radio" id="cat'.$col["id"].
                        '" name= "category" value="'.$col["id"].'">';
                        echo '<label for="cat'.$col["id"].'">'.$col["category_name"].'</label><br>';
                    }
                }else{
                    echo "NO categories found<br>";
                }
            ?>
            Choose class ID <br>
            <?php
                
                $manage = new manage();
                $class = $manage->query("SELECT class_name, id FROM class");
                if($class != false){
                    foreach($class as $col){
                        echo '<input type="radio" id="class'.$col["id"].
                        '" name= "class" value="'.$col["id"].'">';
                        echo '<label for="class'.$col["id"].'">'.$col["class_name"].'</label><br>';
                    }
                }else{
                    echo "NO classes found<br>";
                }
            ?>
            Price: <input type="number" name="price"><br>
            stock: <input type="number" name="stock"><br>
            RAM: <input type="number" name="RAM"><br>
            ROM: <input type="number" name="ROM"><br>
            <button type="submit">INSERT</button>
        </form>
        <?php
           if (isset($_GET["inserted"]) && $_GET["inserted"] == 1) {
                echo "<p>Inserted</p>";
            } elseif (isset($_GET["inserted"]) && $_GET["inserted"] == 0) {
                echo "<p>Error there is a problem</p>";
            }
        ?>
    </div>
</body>
</html>