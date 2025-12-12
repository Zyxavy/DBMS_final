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
    redirectToPage("../pages/profile.php");
    exit();
}

$userId = $_POST['user_id'];
$profile = new Profile($userId);
$address = new Address();
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

$requiredAddressFields = [
    'street_address',
    'city',
    'province',
    'unit_num',
    'postal_code'
];

$optionalAddressFields = [
    'address_type'
];


$anyAddressFieldFilled = false;
foreach (array_merge($requiredAddressFields, $optionalAddressFields) as $field) 
{
    if (!empty(trim($_POST[$field] ?? '')))
    {

        $anyAddressFieldFilled = true;
        break;
    }
}

if ($anyAddressFieldFilled) {
    $missingFields = [];
    
    foreach ($requiredAddressFields as $field) 
    {
        if (empty(trim($_POST[$field] ?? ''))) 
        {
            $missingFields[] = ucwords(str_replace('_', ' ', $field));
        }
    }
    

    if (!empty($missingFields)) 
    {
        $_SESSION['error_message'] = "Cannot update address. Missing required fields: " . implode(', ', $missingFields);
        redirectToPage("../pages/profile.php");
        exit();
    }

    $addressData = [];
    
    foreach (array_merge($requiredAddressFields, $optionalAddressFields) as $field) {
        $addressData[$field] = clean($_POST[$field] ?? '');
    }
    
    $addressData['is_default'] = isset($_POST['default_address']) ? 1 : 0;
    
    $addressResult = $address->addAddress($userId, $addressData);
    
    if ($addressResult === true) 
    {
        $changesMade = true;
    } 
    else 
    {
        $_SESSION['error_message'] = "Failed to update address. Please try again.";
        redirectToPage("../pages/profile.php");
        exit();
    }
}

if ($changesMade) 
{
    $_SESSION['success_message'] = "Profile updated successfully!";
} 
else 
{
    $_SESSION['error_message'] = "No changes detected. Please fill in at least one field to update.";
}

redirectToPage("../pages/profile.php");
exit();