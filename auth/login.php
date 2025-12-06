<?php

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    //like #include <>
    require_once __DIR__ . "/../config/database.php"; //small d
    require_once __DIR__ . "/../Classes/LoginClass.php";
    require_once __DIR__ . "/../includes/functions.php";

    $userName = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars_decode($_POST["pass"]);

    $login = new Login($userName, $password);

    if($login->authenticateUser())
    {

        if($login->getUserStatus()['status'] == "customer")
        {
            redirectToPage("../pages/products.php");
        }
        else
        {
            redirectToPage("../pages/admin.php");
        }

    }
    else
    {
        header("Location: ../index.php");
        exit();
    }

}

