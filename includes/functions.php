<?php

function redirectToPage($page) 
{
 
    header("Location: $page");
    exit();
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
