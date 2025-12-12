<?php
    require_once __DIR__. "/../config/database.php";
    require_once __DIR__. "/manage.php";
    require_once __DIR__. "/../includes/functions.php";
    $manage = new manage();
    if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["mode"])){
 
        if(isset($_POST["mode"])){
            $mode = $_POST["mode"];
        }

        $product_id = (isset($_POST["product_id"]))? $_POST["product_id"]:-1;
        $product_name = (isset($_POST["Product_name"]))?$_POST["Product_name"]:"";
        $price = (isset($_POST["price"]))?$_POST["price"]:0;
        $stock = (isset($_POST["stock"]))?$_POST["stock"]:0;
        $RAM = (isset($_POST["RAM"]))?$_POST["RAM"]:0;
        $ROM = (isset($_POST["ROM"]))?$_POST["ROM"]:0;
        $descp =(isset($_POST["descp"]))? $_POST["descp"]:"";
        switch ($mode) {

        case "INSERT":
            if($_POST["is_pressed_insert"])
            {
                if($manage->add_product($product_name,$_POST["category"],$_POST["class"]
                    ,$price,$stock,$ROM,$RAM,$descp)){
                    redirectToPage("../pages/admin.php?inserted=1");
                }else{
                    redirectToPage("../pages/admin.php?inserted=0");
                }
            }
            break;

        case "UPDATE":
            if($_POST["is_pressed_insert"])
            {
                if($manage->update_product($product_id,$product_name,$_POST["category"],$_POST["class"]
                    ,$price,$stock,$ROM,$RAM,$descp)){
                    redirectToPage("../pages/admin.php?updated=1");
                }else{
                    redirectToPage("../pages/admin.php?updated=0");
                }
            }
            break;
        
        case "DELETE":
            if (empty($product_id) || $product_id === "") {
                redirectToPage("../pages/admin.php?deleteStat=0");
                break;
            }
            if($manage->delete_product($product_id)){
                redirectToPage("../pages/admin.php?deleteStat=1");
            }
            else{
                redirectToPage("../pages/admin.php?deleteStat=0");
            }
            break;

        case "RESET":
             try {
                $manage->query("SET FOREIGN_KEY_CHECKS = 0;TRUNCATE TABLE products;SET FOREIGN_KEY_CHECKS = 1;",false);
                redirectToPage("../pages/admin.php?reset=1");
            }catch (PDOException $e) {
                redirectToPage("../pages/admin.php?reset=0");
            }
            break;

    }
    }
    if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["update_order"])){
        $mode = $_POST["update_order"];
        switch($mode){
            case "UPDATE":
                $order_id = $_POST["order_id"];
                $new_status = $_POST["new_order_status"];
                $new_payment_method = $_POST["new_payment_method"];
                $new_payment_status = $_POST["new_payment_status"];
                $updated = $manage->update_order($order_id, $new_status, $new_payment_method, $new_payment_status);
                if ($updated) {
                    redirectToPage("../pages/admin_orders.php?updated=1&order_id=$order_id");
                } else {
                    redirectToPage("../pages/admin_orders.php?updated=0&order_id=$order_id");
                }
                
                break;
        }
    }
    if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["user_mode"])){
        $mode = $_POST["user_mode"];

        switch($mode){
            case "UPDATE":
                $new_status = $_POST["status"];
                $user_id = $_POST["user_id"];
                $manage->update_user_status($user_id,$new_status);
                redirectToPage("../pages/admin_users.php");
                break;
        }
    }