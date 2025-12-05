<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping cart</title>
</head>
<body>
    <div style="text-align: center;">
        <h1> THIS IS YOUR SHOPPING CART </h1>

        <ul>
            <li>LAPTOP</li>
            <li>PHONE</li>
            <li>KEYBOARD</li>
            <li>MOUSE</li>
        </ul>
        <h3>TOTAL: WOW</h3>
    </div>

    <div>
        <form action="cart.php" method="post">
            <button type="submit" name="checkout">Checkout</button>
        </form>
    </div>
</body>
</html>

<?php 
    require_once __DIR__ . "/../includes/functions.php";

    if(isset($_POST['checkout']))
    {
        if (!headers_sent()) 
        {
            redirectToPage('checkout.php');
        } 
    }
?>