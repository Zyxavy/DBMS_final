<?php

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    //like #include <>
    require_once "../config/Database.php";
    require_once "../Classes/RegisterClass.php";

    $password = $_POST["pass"];
    $userName = $_POST["username"];
    $email = $_POST["email"];

    $reg = new Register($password, $userName, $email);

    if($reg->registerUser())
    {
        echo "REGISTRATION SUCCESS!!!";
    }
    else
    {
        echo "REGISTRATION Failed :(";
    }


}