<?php

// Ginhihimo hini an pag-redirect han user ngadto hin espisipiko nga pahina ngan 
// ginpapahunong dayon an script katapos han redirect.
function redirectToPage($page) 
{
    header("Location: $page"); // Nagpapadara hin HTTP header para mag-redirect an browser
    exit(); 
}

// Ginsusuri kun mayda user session ngan kun an user_status customer o admin

function checkSession()
{
    // Kun waray nakaset nga user_id OR diri sakop han allowed roles an user_status
    if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_status'], ['customer', 'admin']))
    {
        redirectToPage("../index.php"); // I-redirect ngadto ha homepage kun diri valid an session
        exit();
    }
}

// Gin-aasoy hini nga function nga ADMIN la an pwede makasulod ha pipira nga pahina
function checkAdmin()
{
    // Kun waray session o kun diri admin an user
    if (!isset($_SESSION['user_id']) || $_SESSION['user_status'] !== 'admin') 
    {
        header("Location: ../index.php"); // I-redirect an diri admin ngadto ha homepage
        exit();
    }

}


// Awtomatiko nga ginsusuri kun mayda GET variable
// Iginbabalik an iya value kun mayda, kun waray balik false

function autocheckPOST($post){ // Ibalik an post value
    if(isset($_POST[$post])){
        return $_POST[$post];
    }
    else{
        return false;
    }
}
function autocheckGET($get){ //  Ginbabalik it $_GET kun true ini hiya, false kun dire
    if(isset($_GET[$get])){
        return $_GET[$get];
    }
    else{
        return false;
    }
}

?>