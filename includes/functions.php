<?php
function redirectToPage($page) 
{
    header("Location: $page");
    exit();
}

function checkSession()
{
    if(!isset($_SESSION['user_id']) || $_SESSION['user_status'] !== 'customer' || $_SESSION['user_status'] !== 'admin')
    {
        redirectToPage("../index.php"); 
        exit();
    }
}

function checkAdmin()
{
    if (!isset($_SESSION['user_id']) || $_SESSION['user_status'] !== 'admin') 
    {
        header("Location: ../index.php");
        exit();
    }

}


function autocheckPOST($post){ //Returns the $_POST when isset is true, else return false
    if(isset($_POST[$post])){
        return $_POST[$post];
    }
    else{
        return false;
    }
}
function autocheckGET($get){ //Returns the $_GET when isset is true, else return false
    if(isset($_GET[$get])){
        return $_GET[$get];
    }
    else{
        return false;
    }
}