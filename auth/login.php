<?php

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    //like #include <>
    require_once "../config/Database.php";
    require_once "../Classes/LoginClass.php";

    $userName = $_POST["username"];
    $password = $_POST["pass"];

    $login = new Login($userName, $password);

    if($login->authenticateUser())
    {
        echo "LOGIN SUCCESS!!!";
    }
    else
    {
        echo "Login Failed :(";
    }

    
}