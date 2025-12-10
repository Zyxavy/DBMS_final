<?php
session_start();
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../Classes/CartClass.php";
require_once __DIR__ . "/../Classes/AddressClass.php";
require_once __DIR__ . "/../includes/functions.php";

if(!isset($_SESSION['user_id']))
{
    redirectToPage("../index.php");
    session_destroy();
    exit();
}

$cart = new Cart();
$cartItems = $cart->getCartItems($_SESSION['user_id']);

$userAddresses = new Address();
$addresses = $userAddresses->getUserAddresses($_SESSION['user_id']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
</head>
<body>
    <div>
        <h2>Checkout Page</h2>
    </div>

    <div>
        <table border="5" cellpadding="20">
            <tr>
                <th>Product name</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
            
            <?php 
                $grandtotal = 0;
                if($cartItems && count($cartItems) > 0) { 
                    foreach($cartItems as $item) { 
                        $subtotal = $item['price'] * $item['quantity'];
                        $grandtotal += $subtotal; ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= number_format($subtotal, 2) ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="3" align="right"><strong>Total:</strong></td>
                        <td><strong><?= number_format($grandtotal, 2) ?></strong></td>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <td colspan="4">Your cart is empty</td>
                    </tr>
                <?php } ?>
        </table>
    </div>

    <?php if($cartItems && count($cartItems) > 0) { ?>
    <div>
        <form action="../includes/processCheckout.php" method="post">
            <label for="address">Select Address:</label>
            <select name="address_id" id="address" required>
                <option value="">-- Select Address --</option>
                <?php if($addresses && count($addresses) > 0) {
                    foreach($addresses as $address) { ?>
                       <option value="<?= $address['address_id'] ?>">
                        <?= ucfirst($address['address_type']) ?> -
                        <?= $address['street_address'] ?>, 
                        <?= $address['city'] ?>, 
                        <?= $address['province'] ?> 
                        <?= $address['postal_code'] ?>
                        <?= $address['is_default'] ? "(Default)" : "" ?>
                    </option>
                    <?php }
                } ?>
            </select>
            <br>
            <label for="method">Select Payment Method</label>
            <select name="method_id" id="method" required>
                <option value="cash_on_delivery">Cash on Delivery</option>
                <option value="credit_card">Credit Card</option>
                <option value="debit_card">Debit Card</option>
                <option value="paymaya">Paymaya</option>
                <option value="gcash">Gcash</option>
            </select>
            <br>
            <button type="submit">Place Order</button>
        </form>
    </div>
    <?php } ?>
</body>
</html>