<!--- functions na puydi nat ma reuse-->

<?php

function switchPage($file_name,$port=8000){
    /**
     * switch pages within directory.
     */
    header("Location: http://localhost:$port/".$file_name);
}
?>