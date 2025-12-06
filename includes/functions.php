<?php

function redirectToPage($page) 
{
    //Anoter function to redirect page, in case the first one does not work. I find this reliable
    header("Location: $page");
    exit();
}


