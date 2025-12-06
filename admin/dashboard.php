<!-- Optional, diri ada ini importante-->

<?php
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        require_once __DIR__. "/../config/database.php";
        require_once __DIR__. "/manage.php";
        require_once __DIR__. "/../includes/functions.php";
        $manage = new manage();

        if($_POST["is_pressed_insert"])
        {
            $product_name = $_POST["Product_name"];
            $price = $_POST["price"];
            $stock = $_POST["stock"];
            $RAM = $_POST["RAM"];
            $ROM = $_POST["ROM"];


            if($manage->add_product($product_name,$_POST["category"],$_POST["class"]
                ,$price,$stock,$ROM,$RAM))
            {
                redirectToPage("/../pages/admin.php?inserted=1");
            }else
            {
                redirectToPage("/../pages/admin.php?inserted=0");
            }

        }
    }
?>