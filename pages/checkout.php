<?php

// Gin-iistart an session para magamit an session variables
session_start();

// Ginkakarga an database config
require_once __DIR__ . "/../config/database.php";

// Ginkakarga an Cart class
require_once __DIR__ . "/../Classes/CartClass.php";

// Ginkakarga an Address class
require_once __DIR__ . "/../Classes/AddressClass.php";

// Ginkakarga an helper functions
require_once __DIR__ . "/../includes/functions.php";

// Gintitsek kun naka-login an user, kun diri i-redirect ha login page
checkSession();


// Paghimo hin bag o na instance hit cart
$cart = new Cart();

// Ginkuha an items han cart para han current user
$cartItems = $cart->getCartItems($_SESSION['user_id']);

// Ginhihimo an bag o Address instance
$userAddresses = new Address();

// Ginkuha an tanan addresses han user
$addresses = $userAddresses->getUserAddresses($_SESSION['user_id']);

// Gincacalculate an grand total han tanan items
$grandtotal = 0;
if($cartItems && count($cartItems) > 0) {
    foreach($cartItems as $item) {
        $grandtotal += $item['price'] * $item['quantity'];
    }
}
include('../includes/navbar.html');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manzanas Checkout</title>
    <link rel="stylesheet" href="../pages/checkoutStyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> <!-- placeholder laanay ini since waray paman kita icons   -->
</head>
<body>
    
    <div class="checkout-container">
        
        <div class="checkout-header">
            <h2>Checkout</h2>
        </div>

        <?php

            // Kun may success message ha session 
            if(isset($_SESSION['success_message'])) 
            {
                // Ipakita an success message ha user
                echo "<div style='color: var(--color-success); padding: 10px; border: 1px solid var(--color-success); background-color: #1c2b1d; margin-bottom: 20px; border-radius: 5px; text-align: center;'>" . htmlspecialchars($_SESSION['success_message']) . "</div>";
                // Burahon an message tikang ha session pagkatapos ipakita
                unset($_SESSION['success_message']);
            }

            // Kun may error message ha session
            if(isset($_SESSION['error_message'])) 
            {
                // Ipakita an error message ha use
                echo "<div style='color: var(--color-error); padding: 10px; border: 1px solid var(--color-error); background-color: #2b1c1c; margin-bottom: 20px; border-radius: 5px; text-align: center;'>" . htmlspecialchars($_SESSION['error_message']) . "</div>";
                // Burahon an message tikang ha session pagkatapos ipakita
                unset($_SESSION['error_message']);
            }
        ?>

        <?php 
        // Kun mayda items an cart
        if($cartItems && count($cartItems) > 0) { ?>

            <div class="checkout-layout">
                
                <div class="checkout-details">
                    
                    <div class="checkout-section shipping-section">
                        <h3>1. Shipping Information</h3>

                        <!-- Form para pagpili han delivery address -->
                        <?php if($addresses && count($addresses) > 0) { ?>
                            <form action="../includes/processCheckout.php" method="post" id="checkout-form">
                                <div class="form-group">
                                    <label for="address">Select Delivery Address</label>
                                    <select name="address_id" id="address" required>
                                        <option value="">-- Select Address --</option>
                                        <?php foreach($addresses as $address) { ?>
                                            <option value="<?= $address['address_id'] ?>" <?= $address['is_default'] ? 'selected' : '' ?>>
                                                <?= ucfirst($address['address_type']) ?>: 
                                                <?= $address['street_address'] ?>, 
                                                <?= $address['city'] ?>, 
                                                <?= $address['province'] ?> 
                                                <?= $address['postal_code'] ?>
                                                <?= $address['is_default'] ? " (Default)" : "" ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </form>
                        <?php } else { ?>

                            <!-- Kun waray pa addresses -->
                            <div style="color: var(--color-error);">
                                <i class="fa-solid fa-triangle-exclamation"></i> No addresses found. Please add an address to proceed.
                            </div>
                        <?php } ?>
                    </div>

                    <!-- Payment Method Selection -->
                    <div class="checkout-section payment-section">
                        <h3>2. Payment Method</h3>
                        <div class="form-group">
                            <label for="method">Select Payment Method</label>
                            <select name="method_id" id="method" form="checkout-form" required>
                                <option value="cash_on_delivery">Cash on Delivery</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="debit_card">Debit Card</option>
                                <option value="paymaya">Paymaya</option>
                                <option value="gcash">Gcash</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="order-summary">
                    <div class="checkout-section">
                        <h3>Order Summary</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th style="text-align: right;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($cartItems as $item) {
                                    $subtotal = $item['price'] * $item['quantity']; ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['name']) ?></td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td style="text-align: right;">$<?= number_format($subtotal, 2) ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td colspan="2"><strong>Total:</strong></td>
                                    <td class="total-amount" style="text-align: right;">$<?= number_format($grandtotal, 2) ?></td>
                                </tr>
                            </tfoot>
                        </table>
                        
                        <!-- Place Order Button -->
                        <button type="submit" form="checkout-form" class="btn-primary">
                            <i class="fa-solid fa-credit-card"></i> Place Order
                        </button>
                    </div>
                </div>
            </div>
        
        <?php } else { ?>
            <div class="empty-cart-message">
                <i class="fa-solid fa-bag-shopping"></i> Your cart is empty. Please add items to proceed to checkout.
                <div style="margin-top: 20px;">
                    <a href="phones.php" class="btn-primary" style="display: inline-block; width: auto; padding: 10px 20px;">
                        Continue Shopping
                    </a>
                </div>
            </div>
        <?php } ?>
    </div>

<?= include('../includes/footer.html') ?>

<script> 
    document.getElementById("checkout-form").addEventListener("submit", function(e) {
    if (!confirm("Place your order?")) {
        e.preventDefault();
    }
});
</script>

</body>
</html>