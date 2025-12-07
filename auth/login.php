<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    //like #include <>
    require_once __DIR__ . "/../config/database.php"; //small d
    require_once __DIR__ . "/../Classes/LoginClass.php";
    require_once __DIR__ . "/../includes/functions.php";

    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["pass"]);

    $login = new Login($username, $password);

    if($login->authenticateUser())
    {

        if($login->getUserStatus()['status'] == "customer")
        {
            $_SESSION["username"] = $username;
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
        header("Location: ../index.php");
        exit();
    }

}

