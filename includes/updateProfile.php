<?php
session_start();
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../Classes/ProfileClass.php";
require_once __DIR__ . "/../Classes/AddressClass.php";
require_once __DIR__ . "/../includes/functions.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') 
{
    $_SESSION['error_log'] = "Must be accessed through POST";
    exit();
}

if (!isset($_POST['user_id']) || empty($_POST['user_id'])) 
{
    $_SESSION['error_log'] = "Error: Missing user ID";
    exit();
}

$userId = $_POST['user_id'];
$profile = new Profile($userId);
$address = new Address($userId);

$changesMade = false;

function clean($v) 
{
    return trim(htmlspecialchars($v ?? ""));
}

if (isset($_POST['first_name'])) 
{
    $profile->setFirstName(clean($_POST['first_name']));
    $changesMade = true;
}

if (isset($_POST['last_name']))
 {
    $profile->setLastName(clean($_POST['last_name']));
    $changesMade = true;
}

if (isset($_POST['email'])) 
{
    $profile->setEmail(clean($_POST['email']));
    $changesMade = true;
}

if (isset($_POST['phone'])) 
{
    $profile->setPhoneNum(clean($_POST['phone']));
    $changesMade = true;
}

$addressData = [
    "province"      => clean($_POST['province'] ?? "N/A"),
    "city"          => clean($_POST['city'] ?? "N/A"),
    "street"        => clean($_POST['street_address'] ?? "N/A"),
    "unit_num"      => clean($_POST['unit_num'] ?? "N/A"),
    "postal_code"   => clean($_POST['postal_code'] ?? "N/A"),
    "is_default"    => isset($_POST['default_address']) ? 1 : 0
];

$address->addAddress($userId, $addressData);
$changesMade = true;

if ($changesMade) {
    $_SESSION['success_message'] = "Profile updated successfully";
} else {
    $_SESSION['error_message'] = "No changes detected";
}

header("Location: ../pages/profile.php");
exit();

