<?php
session_start();
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../Classes/RegisterClass.php";
require_once __DIR__ . "/../includes/functions.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") 
{

    if (isset($_POST['back_btn'])) 
    {
        redirectToPage('../index.php');
        exit();
    }

    $password = htmlspecialchars($_POST["pass"]);
    $username = htmlspecialchars($_POST["username"]);
    $email = htmlspecialchars($_POST["email"]);
    $error = "";

    $reg = new Register($password, $username, $email);
    $result = $reg->registerUser();

    if ($result) 
    {
        $_SESSION["username"] = $username;
        redirectToPage("../pages/products.php");
        exit();
    } else 
    {
        $error = "Registration Failed";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
</head>
<body>
    <div class="register-form" style="text-align: center; background-color: chocolate;">
        <form action="register.php" method="post">
            <button type="submit" name="back_btn">Back</button>
            <input type="text" name="username" placeholder="Enter Name:">
            <input type="password" name="pass" placeholder="Enter Password:">
            <input type="email" name="email" placeholder="Enter Email:">
            <button>Register</button>
        </form>
    </div>

    <?php if (!empty($error)): ?>
        <h3><?= $error ?></h3>
    <?php endif; ?>

</body>
</html>
