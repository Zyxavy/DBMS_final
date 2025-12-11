<?php
session_start();

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../Classes/ProfileClass.php";
require_once __DIR__ . "/../Classes/AddressClass.php";
require_once __DIR__ . "/../includes/functions.php";

checkSession();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') 
{
    $_SESSION['error_log'] = "Must be accessed through POST";
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

if (isset($_POST['first_name']) && !empty(trim($_POST['first_name']))) 
{
    $profile->setFirstName(clean($_POST['first_name']));
    $changesMade = true;
}

if (isset($_POST['last_name']) && !empty(trim($_POST['last_name'])))
{
    $profile->setLastName(clean($_POST['last_name']));
    $changesMade = true;
}

if (isset($_POST['email']) && !empty(trim($_POST['email']))) 
{
    $profile->setEmail(clean($_POST['email']));
    $changesMade = true;
}

if (isset($_POST['phone']) && !empty(trim($_POST['phone']))) 
{
    $profile->setPhoneNum(clean($_POST['phone']));
    $changesMade = true;
}

if (isset($_POST['password']) && !empty(trim($_POST['password']))) 
{
    $profile->setPassword(clean($_POST['password']));
    $changesMade = true;
}

$addressData = [
    "address_type"  => clean($_POST['address_type'] ?? "home"), 
    "province"      => clean($_POST['province'] ?? "N/A"),
    "city"          => clean($_POST['city'] ?? "N/A"),
    "street_address" => clean($_POST['street_address'] ?? "N/A"),
    "unit_num"      => clean($_POST['unit_num'] ?? "N/A"),
    "postal_code"   => clean($_POST['postal_code'] ?? "N/A"),
    "is_default"    => isset($_POST['default_address']) ? 1 : 0
];

$addressFields = ['province', 'city', 'street_address', 'unit_num', 'postal_code'];
$addressProvided = false;
foreach ($addressFields as $field) 
{
    if (isset($_POST[$field]) && !empty(trim($_POST[$field]))) 
    {
        $addressProvided = true;
        break;
    }
}

if ($addressProvided) {
    $addressResult = $address->addAddress($userId, $addressData);
    
    if ($addressResult === true) 
    {
        $changesMade = true;
    } else 
    {
        $_SESSION['error_message'] = "Failed to add address. Please try again.";
    }
}


if ($changesMade) 
{
    $_SESSION['success_message'] = "Profile updated successfully";
} else 
{

    $_SESSION['error_message'] = "No changes detected or invalid data provided";
}

header("Location: ../pages/profile.php");
exit();

