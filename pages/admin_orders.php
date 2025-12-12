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

    if (isset($_POST["select_order"]) && isset($_POST["order_id"])) {
        $row = $manage->get_order($_POST['order_id']);
        $getorder = $row ? $row[0] : null;
    }

    if (isset($_POST["DELETE"])) {
        $manage->delete_order($_POST["order_id"]);
        redirectToPage($dashboard);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Admin Orders</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        header img { width: 100%; max-height: 250px; object-fit: cover; border-radius: 10px; }
        .panel { border: 1px solid #ccc; padding: 15px; border-radius: 10px; margin-bottom: 25px; background: #fafafa; }
        form { margin-bottom: 15px; }
        form label { font-weight: bold; display: block; margin-top: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table th, table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        table th { background: #f3f3f3; }
        input[type="submit"], button { padding: 6px 12px; cursor: pointer; margin-top: 5px; }
        #updateStatFalse { background-color: rgba(255,0,0,0.3); padding: 5px; }
        #updateStatTrue { background-color: rgba(0,255,0,0.3); padding: 5px; }
        .radio-group { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 5px; }
    </style>
</head>

<body>

<header>
    <img src="../assets/images/Admin Img.jpg" loading="lazy" alt="Admin Banner">
</header>

<div class="panel">
    <form method="get" action="<?= $_SERVER["PHP_SELF"] ?>">
        <input type="hidden" name="back" value="1">
        <input type="submit" value="Go back to Manage Page">
    </form>

    <h3>Search Orders</h3>

    <form action="<?= $dashboard ?>" method="post">
        <input type="hidden" name="order_mode" value="searchOrd_orderid">
        <label>Search by Order ID:</label>
        <input type="number" name="order_id">
        <button type="submit">Search Order</button>
    </form>

    <form action="<?= $dashboard ?>" method="post">
        <input type="hidden" name="order_mode" value="searchOrd_userid">
        <label>Search by User ID:</label>
        <input type="number" name="user_id">
        <button type="submit">Search Order</button>
    </form>

    <form action="<?= $dashboard ?>" method="post">
        <input type="hidden" name="order_mode" value="searchOrd_status">
        <label>Search by Order Status:</label>
        <div class="radio-group">
            <?php 
            $statuses = ["pending","processing","shipped","delivered","cancelled"];
            foreach($statuses as $status): ?>
                <input type="radio" id="status_<?= $status ?>" name="order_status" value="<?= $status ?>">
                <label for="status_<?= $status ?>"><?= ucfirst($status) ?></label>
            <?php endforeach; ?>
        </div>
        <button type="reset">Reset</button>
        <button type="submit">Search Order</button>
    </form>
</div>

<?php if($getorder): ?>
    <div class="panel">
        <h3>Selected Order: #<?= $getorder['order_id'] ?></h3>

        <form action="<?= $dashboard ?>" method="post">
            <input type="hidden" name="DELETE" value="1">
            <input type="hidden" name="order_id" value="<?= $getorder['order_id'] ?>">
            <button type="submit">Delete this Order</button>
        </form>

        <form action="../admin/dashboard.php" method="post">
            <input type="hidden" name="update_order" value="UPDATE">
            <input type="hidden" name="order_id" value="<?= $getorder["order_id"] ?>">

            <label>User ID:</label>
            <input type="number" value="<?= $getorder["user_id"] ?>" disabled>

            <label>Payment Method:</label>
            <input type="text" name="new_payment_method" value="<?= htmlspecialchars($getorder["payment_method"]) ?>">

            <label>Payment Status:</label>
            <input type="text" name="new_payment_status" value="<?= htmlspecialchars($getorder["payment_status"]) ?>">

            <label>Order Status:</label>
            <div class="radio-group">
                <?php 
                foreach ($statuses as $status) {
                    $checked = ($getorder["order_status"] === $status) ? 'checked' : '';
                    echo '<input type="radio" id="stat_'.$status.'" name="new_order_status" value="'.$status.'" '.$checked.'>';
                    echo '<label for="stat_'.$status.'">'.ucfirst($status).'</label>';
                }
                ?>
            </div>
            <button type="submit">Submit</button>
            <button type="reset">Reset Changes</button>
        </form>
    </div>
<?php endif; ?>

<div class="panel">
    <h3>Search Results / Select Order</h3>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
        <table>
            <tr>
                <th>Select</th>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Order Status</th>
                <th>Payment Method</th>
                <th>Payment Status</th>
            </tr>
            <?php if($getsearch): ?>
                <?php foreach($getsearch as $column): ?>
                    <tr>
                        <td><input type="radio" name="order_id" value="<?= htmlspecialchars($column['order_id']) ?>"></td>
                        <td><?= $column['order_id'] ?></td>
                        <td><?= $column['user_id'] ?></td>
                        <td><?= htmlspecialchars($column['order_status']) ?></td>
                        <td><?= htmlspecialchars($column['payment_method']) ?></td>
                        <td><?= htmlspecialchars($column['payment_status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td id="updateStatFalse" colspan="6">Blank search or not found</td></tr>
            <?php endif; ?>
        </table>
        <button type="submit" name="select_order">Select</button>
    </form>
</div>

<div class="panel">
    <h3>All Orders</h3>
    <table>
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
        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $order['order_id'] ?></td>
                    <td><?= htmlspecialchars($order['username']) ?></td>
                    <td><?= htmlspecialchars($order['street_address']).', '.htmlspecialchars($order['city']).', '.htmlspecialchars($order['province']) ?></td>
                    <td><?= $order['order_date'] ?></td>
                    <td>P<?= number_format($order['total_amount'], 2) ?></td>
                    <td><?= ucfirst($order['order_status']) ?></td>
                    <td><?= ucfirst($order['payment_status']) ?></td>
                    <td><?= $order['total_items'] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td id="updateStatFalse" colspan="8">No orders found.</td></tr>
        <?php endif; ?>
    </table>
</div>

</body>
</html>
