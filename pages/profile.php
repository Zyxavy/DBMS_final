<?php
session_start();
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../Classes/ProfileClass.php";
require_once __DIR__ . "/../Classes/AddressClass.php";
require_once __DIR__ . "/../includes/functions.php";

checkSession();

$session_user_id = $_SESSION['user_id'];               //?? 'mock_user';
$profileManager = new Profile($session_user_id);
$userDetails = $profileManager->getUserDetails();

    // Default values if fetch fails or uses mock data
    // if (!$userDetails) {
    //     $userDetails = [
    //         'username' => 'Guest User',
    //         'first_name' => 'N/A',
    //         'last_name' => 'N/A',
    //         'email' => 'N/A',
    //         'phone' => 'N/A',
    //         'province' => 'N/A',
    //         'city' => 'N/A',
    //         'street_address' => 'N/A',
    //         'unit_num' => 'N/A',
    //         'postal_code' => 'N/A',
    //         'is_default' => false,
    //     ];
    // }
    
    function e($value) {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }

    include('../includes/navbar.html');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile</title>
    <link rel="stylesheet" href="../pages/profileStyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

    <div id="profile_title">
        <h1>Your Profile</h1>
    </div>

    <div id="profile_container">
        <div id="profile_left">

            <div id="profile_pic_container">
                <div id="profile_pic"></div>
            </div>
        <form action="../includes/updateProfile.php" method="post">
            <div id="profile_username_container">
                <div id="profile_username"><?= e($userDetails['username']) ?></div>
            </div>


            <div id="personal_details_title">PERSONAL DETAILS:</div>
            <div id="input_firstname" class="input_box">
                <label for="firstname">First Name</label>
                <input type="text" id="firstname" value="<?= e($userDetails['first_name']) ?>" placeholder="FIRST NAME">
            </div>
            <div id="input_lastname" class="input_box">
                <label for="lastname">Last Name</label>
                <input type="text" id="lastname" value="<?= e($userDetails['last_name']) ?>" placeholder="LAST NAME">
            </div>
            
            <div class="input_box">
                <label for="password">Last Name</label>
                <input type="password" id="password" value="" placeholder="PASSWORD">
            </div>
            
            <div id="input_email" class="input_box">
                <label for="email">Email</label>
                <input type="email" id="email" value="<?= e($userDetails['email']) ?>" placeholder="EMAIL">
            </div>
            <div id="input_phone" class="input_box">
                <label for="phone">Phone</label>
                <input type="tel" id="phone" value="<?= e($userDetails['phone']) ?>" placeholder="PHONE">
            </div>

        </div>

        <div id="profile_right">

            <div id="address_details_title">ADDRESS DETAILS:</div>

            <div id="input_province" class="input_box">
                <label for="province">Province</label>
                <input type="text" id="province" value="<?= e($userDetails['province']) ?>" placeholder="PROVINCE">
            </div>
            <div id="input_city" class="input_box">
                <label for="city">City/Municipality</label>
                <input type="text" id="city" value="<?= e($userDetails['city']) ?>" placeholder="CITY/MUNICIPALITY">
            </div>
            <div id="input_street" class="input_box">
                <label for="address">Street Address</label>
                <input type="text" id="address" value="<?= e($userDetails['street_address']) ?>" placeholder="STREET ADDRESS">
            </div>
            <div id="input_street" class="input_box">
                <label for="unit_num">Street Address</label>
                <input type="text" id="unit_num" value="<?= e($userDetails['unit_num']) ?>" placeholder="UNIT/HOUSE/LOT NUMBER">
            </div>
            <div id="input_zip" class="input_box">
                <label for="zip">ZIP Code</label>
                <input type="text" id="postal_code" value="<?= e($userDetails['postal_code']) ?>" placeholder="POSTAL CODE">
            </div>

            <div id="address_options">
                <div id="address_default_check">
                    <input type="checkbox" id="default_address" <?= $userDetails['is_default'] ? 'checked' : '' ?>>
                </div>
                <div id="address_default_label">Default Address</div>
                <div id="btn_add_remove_address">Add or Remove Address</div>
            </div>
            
             <div class="input_box" style="margin-top: 50px;">
                <button type="submit" style="background-color: var(--color-light-text); color: var(--color-maroon); font-weight: 700; border: 2px solid var(--color-light-text);">SAVE CHANGES</button>
            </div>
        </form>
        </div>

    </div>

    <?= include('../includes/footer.html') ?>

</body>
</html>