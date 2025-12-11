<?php

// Gintitikangan an session para magamit an session variables
session_start();

// Ginkakarga an database configuration
require_once __DIR__ . "/../config/database.php";

// Ginkakarga an Cart class (pang-manage han cart)
require_once __DIR__ . "/../Classes/CartClass.php";

// Ginkakarga an imo mga helper functions
require_once __DIR__ . "/../includes/functions.php";

checkSession(); // Ginsusuri kun naka-login an user

$userId = $_SESSION['user_id']; // Ginbubutang an user ID tikang ha session

// Gina-assure nga POST la an pwede mag-run hini nga code
if ($_SERVER["REQUEST_METHOD"] === "POST")
{  

    // Ginkuha an product_id tikang ha POST
    $productId = $_POST['product_id']; 

    // Ginkuha an quantity nga ginhatag han user
    $quantity = $_POST['quantity'];
    
    // Nagbubuhat hin bag-o nga Cart object
    $cart = new Cart();
    
    // Gin-aadd an produkto ngadto ha cart han user
    $result = $cart->addToCart($userId, $productId, $quantity);
    
    // Ginsusunod kun diin dapat ighidangat an user pagkatapos mag-add
    $redirectPage = isset($_POST['redirect']) ? $_POST['redirect'] : 'products.php';

    // Kun nagmalinampuson an pag-add ha cart
    if ($result) {
        $_SESSION['success_message'] = "Product added to cart!";
        header("Location: $redirectPage"); // Ibalik an user ngadto ha napili nga page
        exit();
    } 
    // Kun pumalyar an pag-add
    else {
        $_SESSION['error_message'] = "Failed to add product.";
        header("Location: $redirectPage"); // Redirect gihapon pero upod error message
        exit();
    }
}
