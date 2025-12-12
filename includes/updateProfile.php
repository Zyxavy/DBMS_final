<?php
session_start();

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../Classes/ProfileClass.php";
require_once __DIR__ . "/../Classes/AddressClass.php";
require_once __DIR__ . "/../includes/functions.php";

checkSession();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') 
{
    $_SESSION['error_message'] = "Must be accessed through POST";
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

$profileFields = [
    'first_name' => 'setFirstName',
    'last_name'  => 'setLastName',
    'email'      => 'setEmail',
    'phone'      => 'setPhoneNum',
    'password'   => 'setPassword'
];

foreach ($profileFields as $field => $setter) 
    {
    if (!empty(trim($_POST[$field] ?? ''))) {
        $profile->$setter(clean($_POST[$field]));
        $changesMade = true;
    }
}

$addressFields = [
    'address_type',
    'province',
    'city',
    'street_address',
    'unit_num',
    'postal_code'
];

$addressData = [];
foreach ($addressFields as $field) 
{
    if (!empty(trim($_POST[$field] ?? ''))) 
    {
        $addressData[$field] = clean($_POST[$field]);
    }
}

$addressData['is_default'] = isset($_POST['default_address']) ? 1 : 0;

if (!empty($addressData)) 
{
    $addressResult = $address->addAddress($userId, $addressData);

    if ($addressResult === true) 
    {
        $changesMade = true;
    } else {
        $_SESSION['error_message'] = "Failed to add/update address. Please try again.";
    }
}

if ($changesMade) 
{
    $_SESSION['success_message'] = "Profile updated successfully";
} 
else 
{
    $_SESSION['error_message'] = "No changes detected or invalid data provided";
}

header("Location: ../pages/profile.php");
exit();
