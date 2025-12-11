<?php
    session_start();
    require_once __DIR__."/../config/database.php";
    require_once __DIR__."/../admin/manage.php";
    require_once __DIR__."/../includes/functions.php";

    checkAdmin();
    $manage = new manage();
    $getusers= $manage->get_all_users();

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager</title>
</head>
<header>
    <img src="../assets/images/Admin Img.jpg" loading="lazy">
</header>
<body>
    <div>
        <form method="post" action="../auth/logout.php">
            <input type="hidden" name="log-out" value="1">
            <button type="submit" value="Log out">Logout</button>
        </form>
        <br>
        <form action="admin.php">
            <button type="submit">Back to manage page</button>
        </form>
    </div>
    <div>
        <form action="../admin/dashboard.php" method="post">
            <p>Update user status</p>
            <input type="hidden" name="user_mode" value="UPDATE">
            <input type="number" id="id" name="user_id">
            <label for="id">Enter user_ID</label>
            <br>Status:
            <input type="radio" name="status" value="admin" id="adminID_label">
            <label for="adminID_label">Admin</label>
            <input type="radio" name="status" value="customer" id="customerID_label">
            <label for="customerID_label">Customer</label>
            <input type="submit" value="Submit">
            <br><br>
            <p>For the purpose of demonstration the password will be printed in the table</p>
        </form>
    </div>
    <div>
        <table>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>status</th>
                <th>password</th>
            </tr>
            <?php
                foreach($getusers as $i){
                    echo "<tr>";
                    echo "<td>".htmlspecialchars($i["user_id"])."</td>";
                    echo "<td>".htmlspecialchars($i["username"])."</td>";
                    echo "<td>".htmlspecialchars($i["status"])."</td>";
                    echo "<td>".htmlspecialchars($i["password_hash"])."</td>";
                    echo "</tr>";
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