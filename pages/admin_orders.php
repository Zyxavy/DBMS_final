<?php

// Gin-iistart an session para gamitin an session variables
session_start();

// Ginkakarga an database configuration
require_once __DIR__ . "/../config/database.php";

// Ginkakarga an manage.php para han admin features (pagkuha orders, delete, update, etc.)
require_once __DIR__ . "/../admin/manage.php";

// Ginkakarga an imo helper functions sugad san redirectToPage(), checkAdmin(), etc.
require_once __DIR__ . "/../includes/functions.php";

// Ginsusuri kun ADMIN la an pwede makakita hini nga page
checkAdmin();

// Kun may GET ?back=1, ibalik ngadto ha admin dashboard
if (isset($_GET["back"]) && $_GET["back"] == 1) {
    redirectToPage("admin.php");
    exit();
}

// Ginkuha an tanan nga orders para i-display
$manage = new manage(); 

// Ginkuha an current page path para gamiton ha forms
$orders = $manage->get_all_orders();

// Variables para han search results
$dashboard = $_SERVER['PHP_SELF'];
$getsearch = [];
$getorder =null;

?>


<?php
    // Ginsusuri kun POST an request method (search, select, update, delete actions)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Pag search hit functionality
        if (isset($_POST["order_mode"])) {
            $order = $_POST["order_mode"];
            switch ($order) {

                // Pag search hit Order ID
                case "searchOrd_orderid":
                    $id = autocheckPOST("order_id");
                    if ($id) $getsearch = $manage->selective_ordersearch($id, false);
                    break;

                // Pag search hit User ID
                case "searchOrd_userid":
                    $user = autocheckPOST("user_id");
                    if ($user) $getsearch = $manage->selective_ordersearch($user, true);
                    break;

                // Pag search hit Order Status
                case "searchOrd_status":
                    $status = autocheckPOST("order_status");
                    if ($status !== false) $getsearch = $manage->selective_statussearch($status);
                    break;
            }
        }

        // Pag select han order para pag update ngan pag delete 
        if (isset($_POST["select_order"]) && isset($_POST["order_id"])) {
            $row = $manage->get_order($_POST['order_id']);
            $getorder = $row ? $row[0] : null;
        }

        // Pag delete han order
        if (isset($_POST["DELETE"])) {
            $manage->delete_order($_POST["order_id"]);
            redirectToPage($dashboard);
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
<header>
    <img src="../assets/images/Admin Img.jpg" loading="lazy">
</header>
<body>
    <div class="panel">
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

            <input type="radio" id="status_processing" name="order_status" value="processing">
            <label for="status_processing">Processing</label>

            <input type="radio" id="status_shipped" name="order_status" value="shipped">
            <label for="status_shipped">shipped</label>

            <input type="radio" id="status_delivered" name="order_status" value="delivered">
            <label for="status_delivered">delivered</label>

            <input type="radio" id="status_cancelled" name="order_status" value="cancelled">
            <label for="status_cancelled">cancelled</label>
            <button type="reset">Reset</button>
            <button type="submit">Search Order</button>
            <br>
        </form>
    </div>
    <br><br>
    <?php if($getorder): ?>
        <form action="<?= $dashboard ?>" method="post">
            <input type="hidden" name="DELETE" value="1">
            <input type="hidden" name="order_id" value="<?= $getorder['order_id'] ?>">
            Delete the selected order? 
            <input type="submit" value="Delete">
        </form>
        <div class="panel">
            <form action="../admin/dashboard.php" method="post">
                <input type="hidden" name="update_order" value="UPDATE">
                <input type="hidden" name="order_id" value="<?= $getorder["order_id"] ?>">
                <label for="order_id">Order ID</label><br>
                <input type="number" id="order_id_static" value="<?= $getorder["order_id"]?>" disabled>
                <br><label for="user_id">User ID</label><br><br>
                <input type="number" id="user_id_static" value="<?= $getorder["user_id"]?>" disabled>
                <br><label for="paymentMeth">Payment Method</label><br>
                <input type="text" id="paymentMeth"name= "new_payment_method" value="<?= htmlspecialchars($getorder["payment_method"])?>">
                <br><label for="paymentStat">Payment Status</label><br>
                <input type="text" id="paymentStat"name= "new_payment_status" value="<?= htmlspecialchars($getorder["payment_status"])?>">
                <br><label>Order Status</label><br>
                <?php
                $statuses = ["pending", "processing", "shipped", "delivered", "cancelled"];
                $current = $getorder["order_status"];

                foreach ($statuses as $status) {
                    echo '<input type="radio" id="stat_'.$status.'" name="new_order_status" value="'.$status.'"';
                    if ($current === $status) echo ' checked';
                    echo '>';
                    echo '<label for="stat_'.$status.'">'.ucfirst($status).'</label><br>';
                }
                ?>
                <input type="submit" value="Submit">
                <input type="reset" value="Reset changes">
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
        <input type="submit" name="select_order" value="Select">
    </form>

    <br>
    <br>
    <br>
    <h2>Display All Orders Table</h2>
    <div id="tablepannel">
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
            <?php

                // Pag check kun mayda pa sulod an $orders
                if (!empty($orders)) {

                    // Gin iikisa an kada order ha lista
                    foreach ($orders as $order) {
                        echo "<tr>"; // Naghihimo hin bag o na row ha table

                        // Pag pakita han order id
                        echo "<td>{$order['order_id']}</td>";

                         // Ginpapakita an ngaran han user (gin-clean gamit htmlspecialchars)
                        echo "<td>" . htmlspecialchars($order['username']) . "</td>";

                        // Ginpapakita an completo nga address (street + city + province)
                        echo "<td>"
                            . htmlspecialchars($order['street_address']) . ", "
                            . htmlspecialchars($order['city']). ", "
                            . htmlspecialchars($order['province']) 
                        ."</td>";

                        // Ginpapakita an petsa han order
                        echo "<td>{$order['order_date']}</td>";

                        // Ginpapakita an total nga bayad, naka-format ha Philippine Peso
                        echo "<td>P" . number_format($order['total_amount'], 2) . "</td>";

                        // Ginpapakita an status han order (ginkukuha an first letter nga kapital)
                        echo "<td>" . ucfirst($order['order_status']) . "</td>";

                        // Ginpapakita an status han bayad
                        echo "<td>" . ucfirst($order['payment_status']) . "</td>";

                        // Ginpapakita kun pira ka items an aada ha order
                        echo "<td>{$order['total_items']}</td>";

                        // Tapos nga row
                        echo "</tr>";
                    }
                } 
                // Kun wara gud la orders, igdisplay hin message
                else {
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