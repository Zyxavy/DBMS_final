
<?php
    if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["mode"])){
        require_once __DIR__. "/../config/database.php";
        require_once __DIR__. "/manage.php";
        require_once __DIR__. "/../includes/functions.php";
        $manage = new manage();
        if(isset($_POST["mode"])){
            $mode = $_POST["mode"];
        }
        

        $product_id = (isset($_POST["product_id"]))? $_POST["product_id"]:-1;
        $product_name = (isset($_POST["Product_name"]))?$_POST["Product_name"]:"";
        $price = (isset($_POST["price"]))?$_POST["price"]:0;
        $stock = (isset($_POST["stock"]))?$_POST["stock"]:0;
        $RAM = (isset($_POST["RAM"]))?$_POST["RAM"]:0;
        $ROM = (isset($_POST["ROM"]))?$_POST["ROM"]:0;

        switch ($mode) {

        case "INSERT":
            if($_POST["is_pressed_insert"])
            {
                if($manage->add_product($product_name,$_POST["category"],$_POST["class"]
                    ,$price,$stock,$ROM,$RAM)){
                    redirectToPage("/../pages/admin.php?inserted=1");
                }else{
                    redirectToPage("/../pages/admin.php?inserted=0");
                }
            }
            break;

        case "UPDATE":
            if($_POST["is_pressed_insert"])
            {
                if($manage->update_product($product_id,$product_name,$_POST["category"],$_POST["class"]
                    ,$price,$stock,$ROM,$RAM)){
                    redirectToPage("/../pages/admin.php?inserted=1");
                }else{
                    redirectToPage("/../pages/admin.php?inserted=0");
                }
            }
            break;
        
        case "DELETE":
            if (empty($product_id) || $product_id === "") {
                redirectToPage("/../pages/admin.php?deleteStat=0");
                break;
            }
            if($manage->delete_product($product_id)){
                redirectToPage("/../pages/admin.php?deleteStat=1");
            }
            else{
                redirectToPage("/../pages/admin.php?deleteStat=0");
            }
            break;

        case "RESET":
             try {
                $manage->query("TRUNCATE TABLE products",false);
                redirectToPage("/../pages/admin.php?reset=1");
            }catch (PDOException $e) {
                redirectToPage("/../pages/admin.php?reset=0");
            }
            break;

    }

    }
?>