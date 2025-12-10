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

$addressId = $_POST['address_id'] ?? null;
$paymentMethod = $_POST['method_id'] ?? null;

if(!$addressId || !$paymentMethod) 
{
    die("Missing checkout info");
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
