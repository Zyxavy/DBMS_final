<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    //like #include <>
    require_once __DIR__ . "/../config/database.php";
    require_once __DIR__ . "/../Classes/LoginClass.php";
    require_once __DIR__ . "/../includes/functions.php";

    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);

    $login = new Login($username, $password);

    if($login->authenticateUser())
    {
        $userData = $login->getUserStatus();
        session_regenerate_id(true);
    
        $_SESSION['user_id'] = $userData['user_id']; 
        $_SESSION["username"] = $username;
        $_SESSION["user_status"] = $userData['status']; 

        if($_SESSION['user_status'] === "customer")
        {
            redirectToPage("../pages/products.php");
	        exit();
        }
        else
        {
            redirectToPage("../pages/admin.php");
	        exit();
        }

    }
    else
    {
        header("Location: ../index.php?error=invalid_login");
        exit();
    }

}

