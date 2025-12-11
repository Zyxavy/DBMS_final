<?php
session_start();

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../Classes/AddressClass.php";
require_once __DIR__ . "/../Classes/ProfileClass.php";
require_once __DIR__ . "/../includes/functions.php";

checkSession();

$userId = $_SESSION['user_id'];

$profile = new Profile($userId);
$userDetails = $profile->getUserDetails();

$address = new Address($userId);
$userAddresses = $address->getUserAddresses($userId);
$defaultAddress = $address->getDefaultAddress($userId);

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

                <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">

                <div id="profile_username_container">
                    <div id="profile_username">
                        <?= htmlspecialchars($userDetails['username'] ?? 'User') ?>
                    </div>
                </div>

                <div id="personal_details_title">PERSONAL DETAILS:</div>

                <div id="input_firstname" class="input_box">
                    <label for="firstname">First Name</label>
                    <input type="text" 
                           id="firstname" 
                           name="first_name" 
                           placeholder="FIRST NAME"
                           value="<?= htmlspecialchars($userDetails['first_name'] ?? '') ?>">
                </div>

                <div id="input_lastname" class="input_box">
                    <label for="lastname">Last Name</label>
                    <input type="text" 
                           id="lastname" 
                           name="last_name" 
                           placeholder="LAST NAME"
                           value="<?= htmlspecialchars($userDetails['last_name'] ?? '') ?>">
                </div>

                <div class="input_box">
                    <label for="password">Password</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           placeholder="ENTER NEW PASSWORD">
                </div>

                <div id="input_email" class="input_box">
                    <label for="email">Email</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           placeholder="EMAIL"
                           value="<?= htmlspecialchars($userDetails['email'] ?? '') ?>">
                </div>

                <div id="input_phone" class="input_box">
                    <label for="phone">Phone</label>
                    <input type="tel" 
                           id="phone" 
                           name="phone" 
                           placeholder="PHONE"
                           value="<?= htmlspecialchars($userDetails['phone'] ?? '') ?>">
                </div>

                <div id="address_details_title">ADDRESS DETAILS:</div>

                <div id="input_province" class="input_box">
                    <label for="province">Province</label>
                    <input type="text" 
                           id="province" 
                           name="province" 
                           placeholder="PROVINCE"
                           value="<?= htmlspecialchars($defaultAddress['province'] ?? '') ?>">
                </div>

                <div id="input_city" class="input_box">
                    <label for="city">City/Municipality</label>
                    <input type="text" 
                           id="city" 
                           name="city" 
                           placeholder="CITY/MUNICIPALITY"
                           value="<?= htmlspecialchars($defaultAddress['city'] ?? '') ?>">
                </div>

                <div id="input_street" class="input_box">
                    <label for="address">Street Address</label>
                    <input type="text" 
                           id="address" 
                           name="street_address" 
                           placeholder="STREET ADDRESS"
                           value="<?= htmlspecialchars($defaultAddress['street_address'] ?? '') ?>">
                </div>

                <div id="input_street" class="input_box">
                    <label for="unit_num">Unit Number</label>
                    <input type="text" 
                           id="unit_num" 
                           name="unit_num" 
                           placeholder="UNIT/HOUSE/LOT NUMBER"
                           value="<?= htmlspecialchars($defaultAddress['unit_num'] ?? '') ?>">
                </div>

                <div id="input_zip" class="input_box">
                    <label for="zip">ZIP Code</label>
                    <input type="text" 
                           id="postal_code" 
                           name="postal_code" 
                           placeholder="POSTAL CODE"
                           value="<?= htmlspecialchars($defaultAddress['postal_code'] ?? '') ?>">
                </div>
                <div id="address_type" class="input_box">
                   <label for="type">Address Type:</label>
                    <select name="type" id="type">
                    <option value="Home">Home</option>
                    <option value="Work">Work</option>
                    </select>
                </div>

                <div id="address_options">
                    <div id="address_default_check">
                        <input type="checkbox" 
                               id="default_address" 
                               name="default_address" 
                               <?= ($defaultAddress) ? 'checked' : '' ?>>
                    </div>
                    <div id="address_default_label">Default Address</div>
                </div>

                <div class="input_box" style="margin-top: 50px;">
                    <button type="submit"
                            style="background-color: var(--color-light-text); color: var(--color-maroon); font-weight: 700; 
                                   border: 2px solid var(--color-light-text);
                                   cursor: pointer;
                                   transition: all 0.3s ease;">
                        SAVE CHANGES
                    </button>
                </div>

            </form>

        </div>

    
    </div>

    <?= include('../includes/footer.html') ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() 
        {
            <?php if (isset($_SESSION['success_message'])): ?>
                showMessage('<?= $_SESSION['success_message'] ?>', 'success');
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                showMessage('<?= $_SESSION['error_message'] ?>', 'error');
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
        });

        function showMessage(text, type) 
        {
            const messageDiv = document.createElement('div');
            messageDiv.className = type + '-message';
            messageDiv.textContent = text;
            
            const container = document.getElementById('profile_container');
            container.parentNode.insertBefore(messageDiv, container);
            
            setTimeout(() => {messageDiv.remove();}, 5000);
        }

        document.querySelector('form').addEventListener('submit', function(e) 
        {
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;

            if (email && !isValidEmail(email)) 
            {
                e.preventDefault();
                showMessage('Please enter a valid email address', 'error');
                return false;
            }

            if (phone && !isValidPhone(phone)) 
            {
                e.preventDefault();
                showMessage('Please enter a valid phone number', 'error');
                return false;
            }
        });

        function isValidEmail(email) 
        {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function isValidPhone(phone) 
        {
            const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
            return phoneRegex.test(phone.replace(/\s/g, ''));
        }
    </script>

</body>
</html>
