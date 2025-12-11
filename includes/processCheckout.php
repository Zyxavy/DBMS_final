<?php
session_start();
require_once "../config/database.php";
require_once "../Classes/CartClass.php";
require_once "../Classes/OrderClass.php";
require_once "../includes/functions.php";

if(!isset($_SESSION['user_id'])) 
{
    redirectToPage("../index.php");
    exit();
}

$userId = $_SESSION['user_id'];

$addressId = filter_input(INPUT_POST, 'address_id', FILTER_VALIDATE_INT);
$paymentMethod = htmlspecialchars($_POST['method_id']);

if(!$addressId || !$paymentMethod) 
{
    redirectToPage("../pages/cart.php?error=missing_info");
    exit();
}

$cart = new Cart();
$cartItems = $cart->getCartItems($userId);

if(!$cartItems || count($cartItems) == 0) 
{
    die("Cart is empty");
}

$total = 0;
foreach($cartItems as $item) 
{
    $total += $item['price'] * $item['quantity'];
}

$order = new Order();
$orderId = $order->createOrder($userId, $addressId, $total, $paymentMethod);

if(!$orderId)
{
    die("Error creating order");
}

$order->addOrderItems($orderId, $cartItems);
$cart->clearCart($userId);

redirectToPage("../pages/products.php");
exit();
