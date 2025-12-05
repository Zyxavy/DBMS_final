<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
</head>
<body>
    This is what u will see when after u login as a customer
    <button>Okay i am customer</button>
    <br>
    <button  onclick="window.location.href='http://localhost:8000'">back</button>
    <br>
    
    <form action="products.php" method="post">
        <button type="submit" name="back">Or this button</button>
        <br>
        <hr>
        <button type="submit" name="cart">Cart</button>
    </form>
</body>
</html>

<?php
    //Button does not seem to work on my computer
    require_once __DIR__ . "/../includes/functions.php";

    if(isset($_POST['back']))
    {
        if (!headers_sent()) 
        {
            redirectToPage('../index.php');
        } 
    }
    elseif(isset($_POST['cart']))
    {
        if (!headers_sent()) 
        {
            redirectToPage('cart.php');
        } 
    }
?>