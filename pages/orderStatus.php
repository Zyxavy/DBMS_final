<?php
session_start();

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../Classes/OrderClass.php";
require_once __DIR__ . "/../includes/functions.php";

checkSession();

$userId = $_SESSION['user_id'];
$order = new Order();

$userOrders = $order->getUserOrders($userId);

include('../includes/navbar.html');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders</title>
    <link rel="stylesheet" href="../pages/orderStatus.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<div class="order-container">
    <h1 class="order-title">My Orders</h1>

    <?php if (empty($userOrders)): ?>
        <p class="no-orders">You have no orders yet.</p>

    <?php else: ?>

        <?php foreach ($userOrders as $o): ?>
            <div class="order-card">
                
                <div class="order-header">
                    <h2>Order #<?= htmlspecialchars($o['order_id']); ?></h2>
                    <span class="order-status <?= htmlspecialchars($o['order_status']); ?>">
                        <?= htmlspecialchars(ucfirst($o['order_status'])); ?>
                    </span>
                </div>

                <p class="order-info"><strong>Date:</strong> <?= htmlspecialchars($o['order_date']); ?></p>
                <p class="order-info"><strong>Total Amount:</strong> ₱<?= number_format($o['total_amount'], 2); ?></p>

                <p class="order-info"><strong>Payment Method:</strong> <?= htmlspecialchars($o['payment_method']); ?></p>
                <p class="order-info"><strong>Payment Status:</strong> <?= htmlspecialchars($o['payment_status']); ?></p>

                <div class="order-items-box">
                    <h3>Items</h3>
                    
                    <?php $items = $order->getOrderItems($o['order_id']); ?>

                    <?php if (!empty($items)): ?>
                        <ul class="order-item-list">
                            <?php foreach ($items as $item): ?>
                                <li class="order-item">

                                    <div class="item-image-box">
                                        <img src="<?= htmlspecialchars($item['product_image']); ?>" alt="" loading="eager">
                                    </div>

                                    <div class="item-details">
                                        <p class="item-name"><?= htmlspecialchars($item['product_name']); ?></p>
                                        <p class="item-qty">Quantity: <?= $item['quantity']; ?></p>
                                        <p class="item-subtotal">Subtotal: ₱<?= number_format($item['subtotal_price'], 2); ?></p>
                                    </div>

                                </li>
                            <?php endforeach; ?>
                        </ul>

                    <?php else: ?>
                        <p>No items found.</p>
                    <?php endif; ?>

                </div>

            </div>
        <?php endforeach; ?>

    <?php endif; ?>

</div>

<?= include('../includes/footer.html'); ?>

</body>
</html>
