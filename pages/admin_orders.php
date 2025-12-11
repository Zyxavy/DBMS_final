<?php
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../admin/manage.php";
require_once __DIR__ . "/../includes/functions.php";


if (isset($_GET["back"]) && $_GET["back"] == 1) {
    redirectToPage("admin.php");
}

$manage = new manage();
$orders = $manage->get_all_orders();
$dashboard = $_SERVER['PHP_SELF'];
$getsearch = [];
$getorder =null;
?>
<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["order_mode"])) {
            $order = $_POST["order_mode"];
            switch ($order) {
                case "searchOrd_orderid":
                    $id = autocheckPOST("order_id");
                    if ($id) $getsearch = $manage->selective_ordersearch($id, false);
                    break;

                case "searchOrd_userid":
                    $user = autocheckPOST("user_id");
                    if ($user) $getsearch = $manage->selective_ordersearch($user, true);
                    break;

                case "searchOrd_status":
                    $status = autocheckPOST("order_status");
                    if ($status !== false) $getsearch = $manage->selective_statussearch($status);
                    break;
            }
        }
        if (isset($_POST["order_id"]) && !isset($_POST["DELETE"])) {
            $row = $manage->get_order($_POST['order_id']);
            $getorder = $row ? $row[0] : null;
        }

        if (isset($_POST["DELETE"])) {
            $manage->delete_order($_POST["order_id"]);
            redirectToPage("orders.php?deleted=1");
        }
    }
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
    <div>
        <form method="get" action="<?= $_SERVER["PHP_SELF"] ?>">
            <input type="hidden" name="back" value="1">
            <input type="submit" value="Go back to manage page">
        </form>
        <h3>Search order</h3>
        <form action="<?= $dashboard?>" method="post">
            <input type="hidden" name="order_mode" value="searchOrd_orderid">
            <br>Search by order ID <input type="number" name="order_id">
            <button type="submit">Search Order</button>
            <br>
        </form>
        <form action="<?= $dashboard ?>" method="post">
            <input type="hidden" name="order_mode" value="searchOrd_userid">
            <br>Search by user ID <input type="number" name="user_id">
            <button type="submit">Search Order</button>
            <br>
        </form>
        <form action="<?= $dashboard ?>" method="post">
            <input type="hidden" name="order_mode" value="searchOrd_status">
            <br>Search by order status<br>

            <input type="radio" id="status_pending" name="order_status" value="pending">
            <label for="status_pending">Pending</label>

            <input type="radio" id="status_delivery" name="order_status" value="delivery">
            <label for="status_delivery">Delivery</label>

            <input type="radio" id="status_cancelled" name="order_status" value="cancelled">
            <label for="status_cancelled">Cancelled</label>

            <input type="radio" id="status_returned" name="order_status" value="returned">
            <label for="status_returned">Returned</label>

            <input type="radio" id="status_success" name="order_status" value="success">
            <label for="status_success">Success</label>
            <button type="reset">Reset</button>
            <button type="submit">Search Order</button>
            <br>
        </form>
    </div>
    <br><br>
    <?php if (isset($_GET["deleted"])): ?>
        <p id="updateStatTrue">Selected order deleted</p><br>
    <?php endif; ?>
    <?php if($getorder): ?>
        <form action="<?= $dashboard ?>" method="post">
            <input type="hidden" name="DELETE" value="1">
            <input type="hidden" name="order_id" value="<?= $getorder['order_id'] ?>">
            Delete the selected order? 
            <input type="submit" value="Delete">
        </form>
        <div>
            <form action="../admin/dashboard.php" method="post">
                <label for="order_id">Order ID</label><br>
                <input type="number" id="order_id" value="<?= $getorder["order_id"]?>" disabled>
                <br><label for="user_id">User ID</label><br><br>
                <input type="number" id="user_id" value="<?= $getorder["user_id"]?>" disabled>
                <br><label for="paymentMeth">Payment Method</label><br>
                <input type="text" id="paymentMeth"name= "new_payment_method" value="<?= htmlspecialchars($getorder["payment_method"])?>">
                <br><label for="paymentStat">Payment Status</label><br>
                <input type="number" id="paymentStat"name= "new_payment_status" value="<?= htmlspecialchars($getorder["payment_status"])?>">
                <br><label for="orderStat">Order Status</label><br>
                <input type="number" id="orderStat"name= "new_order_status" value="<?= htmlspecialchars($getorder["order_status"])?>">
                
                <input type="submit" value="Submit">
                <input type="reset" value="Reset">
            </form>
        </div>
    <?php endif; ?>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
        <br>
        <table>
            
            <tr>
                <th></th>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Order status</th>
                <th>Payment method</th>
                <th>Payment status</th>
            </tr>
                <?php
                    if($getsearch){
                        foreach($getsearch as $column){
                            echo "<tr>";
                            echo "<td><input type='radio' name='order_id' value='".htmlspecialchars($column['order_id'])."'></td>";
                            echo "<td>{$column['order_id']}</td>";
                            echo "<td>{$column['user_id']}</td>";
                            echo "<td>".htmlspecialchars($column['order_status'])."</td>";
                            echo "<td>".htmlspecialchars($column['payment_method'])."</td>";
                            echo "<td>".htmlspecialchars($column['payment_status'])."</td>";
                            echo "</tr>";
                        }
                    }else{
                        echo "<tr><td id='updateStatFalse' colspan='6'>Blank search or not found</td></tr>";
                    }
                ?>
            
        </table>
        <br><br>
        <input type="submit" value="Select">
    </form>

    <br>
    <br>
    <br>
    <h2>Display All Orders Table</h2>
    <div>
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
                    echo "<tr><td id='updateStatFalse' colspan='8'>No orders found.</td></tr>";
                }
            
            ?>
        </table>
    </div>

</body>
</html>

<style>
    table, th, td {
    border: 1px solid black;
    }
    #updateStatFalse{
        background-color: rgba(255, 0, 0, 0.3);
    }
    #updateStatTrue{
        background-color: rgba(0, 255, 0, 0.3);
    }
</style>