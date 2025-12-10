<?php 
    session_start();
    require_once __DIR__ . "/../config/database.php";
    require_once __DIR__ . "/../Classes/CartClass.php";
    require_once __DIR__ . "/../includes/functions.php";

    if(!isset($_SESSION['user_id']))
    {
        redirectToPage("../index.php");
        session_destroy();
        exit();
    }

    $_SESSION['success_message'] = "Cart:";

    if(isset($_POST['checkout']))
    {
        if (!headers_sent()) 
        {
            redirectToPage('checkout.php');
        } 
    }
    elseif(isset($_POST['back']))
    {
        if(!headers_sent()) 
        {
            redirectToPage('products.php');
        } 
    }

    $cart = new Cart();

    if(isset($_POST['update']))
    {
        if($cart->updateQuantity($_POST['cart_id'], $_POST['new_quantity']))
        {
            $_SESSION['success_message'] = "Quantity updated!";
        }
    }
    elseif(isset($_POST['remove']))
    {
        if($cart->removeItem($_POST['cart_id_rm']))
        {
            $_SESSION['success_message'] = "Item Deleted!";
        }
    }

    $cartItems = $cart->getCartItems($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping cart</title>
</head>
<body>
    <div style="text-align: center;">
        <form action="cart.php" method="post">
            <button name="back">Back</button>
        </form>
        <h1> THIS IS YOUR SHOPPING CART </h1>

        <div>
            <table border="5" cellpadding="20">
                <tr>
                    <th>Product name</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>RAM</th>
                    <th>ROM</th>
                    <th>Subtotal</th>
                    <th>Update</th>
                    <th>Remove</th>
                </tr>

                <?php 

                if($_SESSION['success_message'])
                {
                    echo $_SESSION['success_message'];
                }

                    $grandtotal = 0;
                    if($cartItems && count($cartItems) > 0)
                    { 
                        foreach($cartItems as $item)
                        { 
                            $subtotal = $item['price'] * $item['quantity'];
                            $grandtotal += $subtotal;
                            ?>
                            <tr>
                                <td><?= $item['name'] ?></td>
                                <td><?= $item['price'] ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td><?= $item['RAM'] ?></td>
                                <td><?= $item['ROM'] ?></td>
                                <td><?= $subtotal ?></td>
                                <td>
                                    <form action="cart.php" method="post">
                                        <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                                        <input type="number" name="new_quantity" value="<?= $item['quantity'] ?>" min="1">
                                        <button type="submit" name="update">Update</button>
                                    </form>
                                </td>
                                <td>
                                    <form action="cart.php" method="post">
                                        <input type="hidden" name="cart_id_rm" value="<?= $item['cart_id'] ?>">
                                        <button type="submit" name="remove">Remove</button>
                                    </form>
                                </td>
                            </tr>

                        <?php }  
                    }
                    else
                    {
                        echo "<tr><td colspan='7'>Cart is Empty :(</td></tr>";
                    }
                    
                ?>

                <tr>
                    <td>Total:<?= $grandtotal ?></td>
                </tr>
                
            </table>
        </div>

    <div>
        <form action="cart.php" method="post">
            <button type="submit" name="checkout">Checkout</button>
        </form>
    </div>
</body>
</html>