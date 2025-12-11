<?php

    // Gin-iistart an session para magamit an session variables
    session_start();

    // Ginkakarga an database config
    require_once __DIR__ . "/../config/database.php";

    // Ginkakarga an Cart class para han cart operations
    require_once __DIR__ . "/../Classes/CartClass.php";

    // Ginkakarga an helper functions
    require_once __DIR__ . "/../includes/functions.php";

    // Pagcheck kun may naka-login, kun diri i-redirect ha login page
    checkSession();

    // Paghimo hin bag o na instance nui Cart
    $cart = new Cart(); 

    // Kun nag-click an user han checkout button ngan pagredirect ha checkout page
    if(isset($_POST['checkout']))
    {
        redirectToPage('checkout.php'); 
        exit();
    }
    // Pag click han back button ma re-direct kan products.php
    elseif(isset($_POST['back']))
    {
        redirectToPage('products.php');
        exit();
    }

    // Default na message
    $message = "Cart:";

    // Kun nag-update han quantity
    if(isset($_POST['update']))
    {
        $cartId = (int)$_POST['cart_id'];
        $newQuantity = (int)$_POST['new_quantity'];

        // Pagcheck kun valid an quantity ngan na-update an cart
        if($newQuantity > 0 && $cart->updateQuantity($cartId, $newQuantity))
        {
            $message = "Quantity updated successfully!";
        } else {
            $message = "Error: Failed to update quantity or quantity is invalid.";
        }
    }
    // Kun nag-remove han item
    elseif(isset($_POST['remove']))
    {
        $cartIdRm = $_POST['cart_id_rm'];
        
        if($cart->removeItem($cartIdRm))
        {
            $message = "Item removed successfully!";
        } else {
            $message = "Error: Failed to remove item.";
        }
    }
    include('../includes/navbar.html');
    
    // Ginkuha an tanan items han cart para han current user
    $cartItems = $cart->getCartItems($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="../pages/cartStyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> <!-- placeholder laanay ini since waray paman kita icons   -->
</head>
<body>
    <div class="cart-container">
        
        <div class="cart-header">
            <h1><i class="fa-solid fa-cart-shopping"></i> Your Shopping Cart</h1>
        </div>

        <?php if (!empty($message)) { 
            $messageClass = (strpos($message, 'Error') !== false || strpos($message, 'Failed') !== false) ? 'message-error' : 'message-success';
            $messageClass = (strpos($message, 'Error') !== false || strpos($message, 'Failed') !== false) ? 'message-box' : 'message-success';
            $messageStyle = (strpos($message, 'Error') !== false || strpos($message, 'Failed') !== false) ? 'color: var(--color-error); border: 1px solid var(--color-error); background-color: rgba(255, 77, 77, 0.1);' : '';
        ?>
            <div class="message-box <?= $messageClass ?>" style="<?= $messageStyle ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php } ?>

        <div class="cart-table-wrapper">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product name</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>RAM</th>
                        <th>ROM</th>
                        <th>Subtotal</th>
                        <th colspan="2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                        // Ginhihimo an variable para igtamp an total han tanan items
                        $grandtotal = 0;

                        // Gintitsek kun mayda items ha cart
                        if($cartItems && count($cartItems) > 0)
                        {
                             // Ginlilibot an kada item ha cart 
                            foreach($cartItems as $item)
                            {
                                // Ginkuha an presyo han item 
                                $price = (float)$item['price'];

                                // Ginkuha an quantity han item
                                $quantity = (int)$item['quantity'];

                                // Gincacalculate an subtotal para han item (presyo * quantity)
                                $subtotal = $price * $quantity;

                                // Ginadugang an subtotal ha grand total
                                $grandtotal += $subtotal;
                    ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['name']) ?></td>
                                    <td>$<?= number_format($price, 2) ?></td>
                                    <td><?= $quantity ?></td>
                                    <td><?= htmlspecialchars($item['RAM']) ?></td>
                                    <td><?= htmlspecialchars($item['ROM']) ?></td>
                                    <td>$<?= number_format($subtotal, 2) ?></td>
                                    <!-- Update Form -->
                                    <td>
                                        <form action="cart.php" method="post" class="quantity-form">
                                            <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                                            <input type="number" name="new_quantity" value="<?= $item['quantity'] ?>" min="1" class="quantity-input">
                                            <button type="submit" name="update" class="btn-update">
                                                <i class="fa-solid fa-arrows-rotate"></i> Update
                                            </button>
                                        </form>
                                    </td>
                                    <!-- Remove Form -->
                                    <td>
                                        <form action="cart.php" method="post">
                                            <input type="hidden" name="cart_id_rm" value="<?= $item['cart_id'] ?>">
                                            <button type="submit" name="remove" class="btn-remove">
                                                <i class="fa-solid fa-trash"></i> Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                    <?php 
                            } 
                        }
                        else
                        {
                            echo "<tr><td colspan='8' style='text-align: center; color: var(--color-subtle-text); padding: 50px;'>Your Shopping Cart is Empty.</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="cart-actions">
            
            <!-- Back Button -->
            <form action="cart.php" method="post">
                <button name="back" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Continue Shopping
                </button>
            </form>

            <div class="cart-total">
                Grand Total: <span style="color: var(--color-light-text);">$<?= number_format($grandtotal, 2) ?></span>
            </div>

            <!-- Checkout Button -->
            <?php if ($cartItems && count($cartItems) > 0) { ?>
                <form action="cart.php" method="post">
                  <button type="submit" name="checkout" class="btn-checkout">
                        Proceed to Checkout <i class="fa-solid fa-arrow-right"></i>
                    </button> 
                </form>
            <?php } ?>
        </div>
    </div>
    
    <?= include('../includes/footer.html') ?>

</body>
</html>