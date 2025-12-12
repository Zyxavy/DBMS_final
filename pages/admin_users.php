<?php
session_start();
require_once __DIR__."/../config/database.php";
require_once __DIR__."/../admin/manage.php";
require_once __DIR__."/../includes/functions.php";

checkAdmin();
$manage = new manage();
$getusers = $manage->get_all_users();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Users</title>
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
        .radio-group { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 5px; }
        #updateStatFalse { background-color: rgba(255,0,0,0.3); padding: 5px; }
        #updateStatTrue { background-color: rgba(0,255,0,0.3); padding: 5px; }
    </style>
</head>
<body>

<header>
    <img src="../assets/images/Admin Img.jpg" loading="lazy" alt="Admin Banner">
</header>

<div class="panel">
    <form method="post" action="../auth/logout.php">
        <input type="hidden" name="log-out" value="1">
        <button type="submit">Logout</button>
    </form>
    <form action="admin.php">
        <button type="submit">Back to Manage Page</button>
    </form>
</div>

<div class="panel">
    <h3>Update User Status</h3>
    <form action="../admin/dashboard.php" method="post">
        <input type="hidden" name="user_mode" value="UPDATE">

        <label for="user_id">User ID:</label>
        <input type="number" id="user_id" name="user_id" required>

        <label>Status:</label>
        <div class="radio-group">
            <input type="radio" name="status" value="admin" id="adminID_label">
            <label for="adminID_label">Admin</label>

            <input type="radio" name="status" value="customer" id="customerID_label">
            <label for="customerID_label">Customer</label>
        </div>

        <button type="submit">Submit</button>
    </form>
    <p>For demonstration, passwords are displayed in the table below.</p>
</div>

<div class="panel">
    <h3>All Users</h3>
    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Status</th>
                <th>Password</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($getusers as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user["user_id"]) ?></td>
                    <td><?= htmlspecialchars($user["username"]) ?></td>
                    <td><?= htmlspecialchars($user["status"]) ?></td>
                    <td><?= htmlspecialchars($user["password_hash"]) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
