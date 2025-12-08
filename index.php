<?php 
    session_start();
    require_once __DIR__ . "/includes/functions.php";

    if($_SERVER["REQUEST_METHOD"] === "POST")
    {
        if(isset($_POST['registration']))
        {
            redirectToPage("auth/register.php");
            exit();
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/styles.css">
    <title>Manzanas</title>
</head>
<body>
    <?php include("./includes/header.html"); ?>
    <div class="login-form">
        <form action="auth/login.php" method="post">
            <input type="text" name="username" placeholder="Enter Name:">
            <input type="password" name="pass" placeholder="Enter Password:">
            <button>Log in</button>
        </form>
    </div>
    <br>
    <br>
        <form action="index.php" method="post">
            <button type="submit" name="registration">Register</button>
        </form>
    <br>
    <br>
    <hr>
    
    <div style="background-color: gray;"> <!--I cant see the debug cuz text is black-->
    <?php 
        include("./includes/footer.html");
        
        foreach($_SERVER as $key => $value)
        {
            echo"{$key} = {$value} <br><br>";
        }
    ?>
    </div>
    <h1>$_SERVER Keys and Values, for debuging</h1>
</body>
</html>

<style>
    body{
        background-image: url("assets/images/Login Page.jpg");
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
    }
</style>