<?php
require_once __DIR__ . "/../includes/functions.php";
session_destroy();
redirectToPage('../index.php');