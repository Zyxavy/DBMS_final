<?php

// Gin-iistart an session para magamit an session variables han user
session_start();

// Ginkakarga an database connection configuration
require_once "../config/database.php";

// Ginkakarga an klase para han Cart (pangkuha han items ngan pag-manage han cart)
require_once "../Classes/CartClass.php";

// Ginkakarga an klase para han Order (pag-create hin order, pag-add hin items, etc.)
require_once "../Classes/OrderClass.php";

// Ginkakarga an klase para han Product (pag-update han stock, pagkuha han product details)
require_once "../Classes/ProductClass.php";

// Ginkakarga an imo functions.php nga mayda mga helper functions sugad han redirectToPage(), checkSession(), etc.
require_once "functions.php";

checkSession(); // Ginsusuri kun naka-login an user antes makapadayon

$userId = $_SESSION['user_id'];

// Ginkuha an address_id tikang ha POST gamit filter para sigurado nga integer
$addressId = filter_input(INPUT_POST, 'address_id', FILTER_VALIDATE_INT);

// Ginkuha an payment method samtang gin-iwasan an XSS pinaagi ha htmlspecialchars
$paymentMethod = htmlspecialchars($_POST['method_id']);

// Ginsusuri kun kompleto an kinahanglanon nga impormasyon
if(!$addressId || !$paymentMethod) 
{
    redirectToPage("../pages/cart.php?error=missing_info"); // Kon kulang, balik ha cart
    exit();
}

// Naghihimo hin bag o nga instance hit cart
// Kuhaa an tanan nga items nga ada ha cart han user nga may user ID nga $userId.
$cart = new Cart(); 
$cartItems = $cart->getCartItems($userId);

// Kun waray sulod an cart, diri pwede magpadayon an checkout
if(!$cartItems || count($cartItems) == 0) 
{
    die("Cart is empty");
}

// Ginseselyo an kabug-osan nga presyo han tanan nga items
$total = 0;
foreach($cartItems as $item) 
{
    $total += $item['price'] * $item['quantity'];
}

// Pag create hin bag o na instance hit products ngan order
$order = new Order();
$products = new Products(); 

// Nagbubuhat hin transactional connection para masiguro nga magana hin maupay an operation
$db = new Database();
$conn = $db->transactConnect();
$conn->beginTransaction();

try {

    // Nag-create hin order ngan iginsusumat an nakuha nga orderId
    $orderId = $order->createOrder($userId, $addressId, $total, $paymentMethod);
    if (!$orderId) 
    {
        throw new Exception("Order creation failed");
    }

    // Nagdadagdag han mga item ngadto ha order_items table
    if (!$order->addOrderItems($orderId, $cartItems)) 
    {
        throw new Exception("Failed to add order items");
    }

    // Ginbabawasan an stock han kada produkto base ha quantity nga gin-order
    foreach ($cartItems as $item) 
    {
        if (!$products->reduceProductStock($item['product_id'], $item['quantity'])) {
        
            throw new Exception("Stock update failed for product " . $item['product_id']);
        }
    }

    // Ginputs-an an cart katapos mag-success an order
    if (!$cart->clearCart($userId)) 
    {
        throw new Exception("Failed to clear cart");
    }

     // Ginpapatuman an transaction kun waray problema
    $conn->commit();

    // Katapos an tanan, redirect ngadto ha products page
    redirectToPage("../pages/products.php");
    exit();

} 
catch (Exception $e) 
{
    // Kon may mali, ginro-roll back an tanan nga database operations
    $conn->rollBack();

    // Ginlolog ha error log para makita han developer an detalye
    error_log("Checkout failed: " . $e->getMessage());

    // Ibalik an user ha cart page upod hin error message
    redirectToPage("../pages/cart.php?error=checkout_failed");
    exit();
}