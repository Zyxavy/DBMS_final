<?php

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    //like #include <>
    require_once __DIR__ . "/../config/Database.php";
    require_once __DIR__ . "/../Classes/LoginClass.php";
    require_once __DIR__ . "/../includes/functions.php";
    $userName = $_POST["username"];
    $password = $_POST["pass"];

    $login = new Login($userName, $password);

    if($login->authenticateUser())
    {
        echo "LOGIN SUCCESS!!!";
        switchPage("pages/products.php");

    }
    else
    {
        echo "Login Failed :(";
    }

    
}