<?php
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../admin/manage.php";
require_once __DIR__ . "/../admin/dashboard.php";
require_once __DIR__ . "/../includes/functions.php";

// Handle the "back" button
if (isset($_GET["back"]) && $_GET["back"] == 1) {
    redirectToPage("admin.php");
}

// Fetch orders
$manage = new manage();
$orders = $manage->get_all_orders();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Orders</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
</head>
<body>
    <form method="get" action="<?= $_SERVER["PHP_SELF"] ?>">
        <input type="hidden" name="back" value="1">
        <input type="submit" value="Go back to manage page">
    </form>
    <h2>Orders Table</h2>
    <table>
        <tr>
            <th>Order ID</th>
            <th>User</th>
            <th>Address</th>
            <th>Order Date</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Payment</th>
            <th>Items</th>
        </tr>
        <?php
            if (!empty($orders)) {
                foreach ($orders as $order) {

                    echo "<tr>";
                    echo "<td>{$order['order_id']}</td>";
                    echo "<td>" . htmlspecialchars($order['username']) . "</td>";
                    echo "<td>" . htmlspecialchars($order['street_address']) . ", ". htmlspecialchars($order['city']). 
                    ", ". htmlspecialchars($order['province']) ."</td>";
                    echo "<td>{$order['order_date']}</td>";
                    echo "<td>P" . number_format($order['total_amount'], 2) . "</td>";
                    echo "<td>" . ucfirst($order['order_status']) . "</td>";
                    echo "<td>" . ucfirst($order['payment_status']) . "</td>";
                    echo "<td>{$order['total_items']}</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td>No orders found.</td></tr>";
            }
        ?>
    </table>

</body>
</html>