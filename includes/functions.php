<?php

function redirectToPage($page) 
{
 
    header("Location: $page");
    exit();
}


