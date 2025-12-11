<?php
session_start();
require_once "../config/database.php";
require_once "../Classes/CartClass.php";
require_once "../Classes/OrderClass.php";
require_once "../Classes/ProductClass.php";
require_once "functions.php";

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
$products = new Products();

$db = new Database();
$conn = $db->transactConnect();
$conn->beginTransaction();

try {

    $orderId = $order->createOrder($userId, $addressId, $total, $paymentMethod);
    if (!$orderId) 
    {
        throw new Exception("Order creation failed");
    }

    if (!$order->addOrderItems($orderId, $cartItems)) 
    {
        throw new Exception("Failed to add order items");
    }

    foreach ($cartItems as $item) 
    {
        if (!$products->reduceProductStock($item['product_id'], $item['quantity'])) {
        
            throw new Exception("Stock update failed for product " . $item['product_id']);
        }
    }

    if (!$cart->clearCart($userId)) 
    {
        throw new Exception("Failed to clear cart");
    }

    $conn->commit();

    redirectToPage("../pages/products.php");
    exit();

} 
catch (Exception $e) 
{
    $conn->rollBack();
    error_log("Checkout failed: " . $e->getMessage());

    redirectToPage("../pages/cart.php?error=checkout_failed");
    exit();
}