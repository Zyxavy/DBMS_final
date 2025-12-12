<?php
session_start();

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../admin/manage.php";
require_once __DIR__ . "/../includes/functions.php";

checkAdmin();

if (isset($_GET["back"]) && $_GET["back"] == 1) {
    redirectToPage("admin.php");
    exit();
}

$manage = new manage();
$orders = $manage->get_all_orders();
$dashboard = $_SERVER['PHP_SELF'];
$getsearch = [];
$getorder = null;
$message = '';
$messageType = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["order_mode"])) {
        $order = $_POST["order_mode"];
        switch ($order) {
            case "searchOrd_orderid":
                $id = autocheckPOST("order_id");
                if ($id) {
                    $getsearch = $manage->selective_ordersearch($id, false);
                    if (empty($getsearch)) {
                        $message = "No order found with ID: $id";
                        $messageType = 'error';
                    }
                }
                break;
            case "searchOrd_userid":
                $user = autocheckPOST("user_id");
                if ($user) {
                    $getsearch = $manage->selective_ordersearch($user, true);
                    if (empty($getsearch)) {
                        $message = "No orders found for User ID: $user";
                        $messageType = 'error';
                    }
                }
                break;
            case "searchOrd_status":
                $status = autocheckPOST("order_status");
                if ($status !== false) {
                    $getsearch = $manage->selective_statussearch($status);
                    if (empty($getsearch)) {
                        $message = "No orders found with status: $status";
                        $messageType = 'error';
                    }
                }
                break;
        }
    }

    if (isset($_POST["select_order"]) && isset($_POST["order_id"])) {
        $row = $manage->get_order($_POST['order_id']);
        $getorder = $row ? $row[0] : null;
        if (!$getorder) {
            $message = "Failed to load order details.";
            $messageType = 'error';
        }
    }

    if (isset($_POST["update_order"]) && $_POST["update_order"] === "UPDATE") {
        $order_id = (int)($_POST["order_id"] ?? 0);
        $new_status = sanitizeInput($_POST["new_order_status"] ?? '');
        $new_payment_method = sanitizeInput($_POST["new_payment_method"] ?? '');
        $new_payment_status = sanitizeInput($_POST["new_payment_status"] ?? '');
        
        if ($order_id > 0 && !empty($new_status)) {
            $result = $manage->update_order($order_id, $new_status, $new_payment_method, $new_payment_status);
            if ($result) {
                $message = "Order #$order_id updated successfully!";
                $messageType = 'success';
                // Refresh order details
                $row = $manage->get_order($order_id);
                $getorder = $row ? $row[0] : null;
            } else {
                $message = "Failed to update order.";
                $messageType = 'error';
            }
        }
    }

    if (isset($_POST["DELETE"])) {
        $order_id = (int)($_POST["order_id"] ?? 0);
        if ($order_id > 0) {
            $result = $manage->delete_order($order_id);
            if ($result) {
                $message = "Order #$order_id deleted successfully!";
                $messageType = 'success';
                $getorder = null;
                // Refresh orders list
                $orders = $manage->get_all_orders();
            } else {
                $message = "Failed to delete order.";
                $messageType = 'error';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Admin Orders Management</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        header img { width: 100%; max-height: 250px; object-fit: cover; border-radius: 10px; }
        .panel { border: 1px solid #ccc; padding: 15px; border-radius: 10px; margin-bottom: 25px; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .panel h3 { margin-top: 0; color: #333; border-bottom: 2px solid #e70a0aff; padding-bottom: 10px; }
        form { margin-bottom: 15px; }
        form label { font-weight: bold; display: block; margin-top: 8px; color: #555; }
        input[type="text"], input[type="email"], input[type="password"], input[type="number"], textarea {
            width: 100%; padding: 8px; margin-top: 3px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;
        }
        input[type="text"]:disabled, input[type="number"]:disabled {
            background: #f0f0f0; cursor: not-allowed;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; background: #fff; }
        table th, table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        table th { background: #c20808ff; color: white; font-weight: bold; }
        table tr:nth-child(even) { background: #f9f9f9; }
        table tr:hover { background: #f0f0f0; }
        input[type="submit"], button { 
            padding: 8px 16px; cursor: pointer; margin-top: 10px; margin-right: 5px;
            border: none; border-radius: 4px; font-weight: bold; transition: background 0.3s;
        }
        button[type="submit"], input[type="submit"] { background: #ff0000ff; color: white; }
        button[type="submit"]:hover, input[type="submit"]:hover { background: #b30000ff; }
        button[type="reset"] { background: #6c757d; color: white; }
        button[type="reset"]:hover { background: #5a6268; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-danger:hover { background: #c82333; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-secondary:hover { background: #5a6268; }
        .radio-group { display: flex; gap: 15px; flex-wrap: wrap; margin-top: 5px; }
        .radio-group input[type="radio"] { margin: 0; }
        .radio-group label { display: inline; font-weight: normal; margin-left: 5px; cursor: pointer; }
        .message { padding: 12px; border-radius: 5px; margin-bottom: 15px; font-weight: bold; }
        .success-message { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error-message { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        @media (max-width: 968px) { .grid-3 { grid-template-columns: 1fr; } }
        @media (max-width: 768px) { .grid-2 { grid-template-columns: 1fr; } }
        .action-buttons { display: flex; gap: 10px; flex-wrap: wrap; }
        .action-buttons form { margin: 0; }
        .search-container { display: flex; gap: 10px; align-items: flex-end; flex-wrap: wrap; }
        .search-container > div { flex: 1; min-width: 200px; }
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 0.9em;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-processing { background: #cfe2ff; color: #084298; }
        .status-shipped { background: #d1ecf1; color: #0c5460; }
        .status-delivered { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
    </style>
</head>

<body>

<header>
    <img src="../assets/images/Admin Img.jpg" loading="lazy" alt="Admin Banner">
</header>

<div class="panel">
    <div class="action-buttons">
        <form method="get" action="<?= $_SERVER["PHP_SELF"] ?>">
            <input type="hidden" name="back" value="1">
            <button type="submit" class="btn-secondary">← Back to Manage Page</button>
        </form>
    </div>
</div>

<?php if ($message): ?>
    <div class="panel">
        <div class="message <?= $messageType === 'success' ? 'success-message' : 'error-message' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    </div>
<?php endif; ?>

<div class="panel">
    <h3>Search Orders</h3>
    
    <div class="grid-3">
        <div>
            <form action="<?= $dashboard ?>" method="post">
                <input type="hidden" name="order_mode" value="searchOrd_orderid">
                <label for="search_order_id">Search by Order ID:</label>
                <input type="number" id="search_order_id" name="order_id" min="1" required>
                <button type="submit">Search</button>
            </form>
        </div>

        <div>
            <form action="<?= $dashboard ?>" method="post">
                <input type="hidden" name="order_mode" value="searchOrd_userid">
                <label for="search_user_id">Search by User ID:</label>
                <input type="number" id="search_user_id" name="user_id" min="1" required>
                <button type="submit">Search</button>
            </form>
        </div>

        <div>
            <form action="<?= $dashboard ?>" method="post">
                <input type="hidden" name="order_mode" value="searchOrd_status">
                <label>Search by Status:</label>
                <div class="radio-group">
                    <?php 
                    $statuses = ["pending","processing","shipped","delivered","cancelled"];
                    foreach($statuses as $status): ?>
                        <div>
                            <input type="radio" id="status_<?= $status ?>" name="order_status" value="<?= $status ?>" required>
                            <label for="status_<?= $status ?>"><?= ucfirst($status) ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="reset" class="btn-secondary">Reset</button>
                <button type="submit">Search</button>
            </form>
        </div>
    </div>

    <?php if (!empty($getsearch)): ?>
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="get" style="margin-top: 15px;">
            <button type="submit" class="btn-secondary">Clear Search / Show All Orders</button>
        </form>
    <?php endif; ?>
</div>

<?php if($getorder): ?>
    <div class="panel">
        <h3>Edit Order #<?= $getorder['order_id'] ?></h3>

        <div class="grid-2">
            <div>
                <form action="<?= $dashboard ?>" method="post">
                    <input type="hidden" name="update_order" value="UPDATE">
                    <input type="hidden" name="order_id" value="<?= $getorder["order_id"] ?>">

                    <label for="edit_user_id">User ID:</label>
                    <input type="number" id="edit_user_id" value="<?= $getorder["user_id"] ?>" disabled>

                    <label for="edit_payment_method">Payment Method:</label>
                        <select name="new_payment_method" id="edit_payment_method" required>
                            <?php
                            $methods = [
                                'cash_on_delivery' => 'Cash On Delivery',
                                'credit_card' => 'Credit Card',
                                'debit_card' => 'Debit Card',
                                'gcash' => 'Gcash',
                                'paymaya' => 'Paymaya'
                            ];
                            foreach ($methods as $value => $label) {
                                $selected = ($getorder['payment_method'] === $value) ? 'selected' : '';
                                echo "<option value=\"" . htmlspecialchars($value) . "\" $selected>$label</option>";
                            }
                            ?>
                        </select>

                    <label for="edit_payment_status">Payment Status:</label>
                    <select name="new_payment_status" id="edit_payment_status" required>
                        <?php
                        $pay_status = ['unpaid', 'paid', 'refunded'];
                        foreach ($pay_status as $status_payment) {
                            $selected = ($getorder['payment_status'] === $status_payment) ? 'selected' : '';
                            echo "<option value=\"" . htmlspecialchars($status_payment) . "\" $selected>" . ucfirst($status_payment) . "</option>";
                        }
                        ?>
                    </select>

                    <label>Order Status:</label>
                    <div class="radio-group">
                        <?php 
                        foreach ($statuses as $status) {
                            $checked = ($getorder["order_status"] === $status) ? 'checked' : '';
                            echo '<div>';
                            echo '<input type="radio" id="edit_stat_'.$status.'" name="new_order_status" value="'.$status.'" '.$checked.' required>';
                            echo '<label for="edit_stat_'.$status.'">'.ucfirst($status).'</label>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                    <button type="submit">Update Order</button>
                    <button type="reset" class="btn-secondary">Reset Changes</button>
                </form>
            </div>

            <div>
                <form action="<?= $dashboard ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this order? This action cannot be undone.');">
                    <input type="hidden" name="DELETE" value="1">
                    <input type="hidden" name="order_id" value="<?= $getorder['order_id'] ?>">
                    <label style="color: #dc3545;"> Delete Order</label>
                    <p>This will permanently delete Order #<?= $getorder['order_id'] ?>. This action cannot be undone.</p>
                    <button type="submit" class="btn-danger">Delete Order</button>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (!empty($getsearch)): ?>
<div class="panel">
    <h3>Search Results (<?= count($getsearch) ?> found)</h3>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
        <table>
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Order Status</th>
                    <th>Payment Method</th>
                    <th>Payment Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($getsearch as $column): ?>
                    <tr>
                        <td><input type="radio" name="order_id" value="<?= htmlspecialchars($column['order_id']) ?>" required></td>
                        <td><?= $column['order_id'] ?></td>
                        <td><?= $column['user_id'] ?></td>
                        <td>
                            <span class="status-badge status-<?= $column['order_status'] ?>">
                                <?= ucfirst(htmlspecialchars($column['order_status'])) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($column['payment_method']) ?></td>
                        <td><?= htmlspecialchars($column['payment_status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" name="select_order">Select & Edit Order</button>
    </form>
</div>
<?php endif; ?>

<div class="panel">
    <h3>All Orders (<?= count($orders) ?> total)</h3>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Username</th>
                <th>Address</th>
                <th>Order Date</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Items</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><strong>#<?= $order['order_id'] ?></strong></td>
                        <td><?= htmlspecialchars($order['username']) ?></td>
                        <td><?= htmlspecialchars($order['street_address']).', '.htmlspecialchars($order['city']).', '.htmlspecialchars($order['province']) ?></td>
                        <td><?= date('M d, Y', strtotime($order['order_date'])) ?></td>
                        <td style="font-weight: bold;">₱<?= number_format($order['total_amount'], 2) ?></td>
                        <td>
                            <span class="status-badge status-<?= $order['order_status'] ?>">
                                <?= ucfirst($order['order_status']) ?>
                            </span>
                        </td>
                        <td><?= ucfirst($order['payment_status']) ?></td>
                        <td style="text-align: center;"><?= $order['total_items'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" style="text-align: center; color: #999; padding: 20px;">No orders found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>