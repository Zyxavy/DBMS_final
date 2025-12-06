
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    //like #include <>
    require_once __DIR__ . "/../config/database.php";
    require_once __DIR__ . "/../Classes/RegisterClass.php";
    require_once __DIR__ . "/../includes/functions.php";

    $password = $_POST["pass"];
    $userName = $_POST["username"];
    $email = $_POST["email"];

    $reg = new Register($password, $userName, $email);

    if($reg->registerUser())
    {
        echo "REGISTRATION SUCCESS!!!";
        redirectToPage("../pages/products.php");
    }
    else
    {
        redirectToPage('../index.php');
        echo "REGISTRATION Failed :(";
    }

}


