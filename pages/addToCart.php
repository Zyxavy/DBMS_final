<?php
session_start();
require_once __DIR__ . "/../config/database.php"; 
require_once __DIR__ . "/../Classes/CartClass.php";
require_once __DIR__ . "/../includes/functions.php";

if(!isset($_SESSION['user_id']))
{
    redirectToPage("../index.php");
    exit();
}

$userId = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST")
{  
    $productId = $_POST['product_id']; 
    $quantity = $_POST['quantity'];
    
    $cart = new Cart();
    
    $result = $cart->addToCart($userId, $productId, $quantity);
    
    if($result)
    {
        $_SESSION['success_message'] = "Product added to cart!";
        header('Location: products.php');
        exit();
    }
    else
    {
        $_SESSION['error_message'] = "Failed to add product.";
        header('Location: products.php');
        exit();
    }
}
