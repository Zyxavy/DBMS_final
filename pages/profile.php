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
    <link rel="stylesheet" href="../pages/profileTW.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Your Profile</title>
</head>
<body>

<main class="flex flex-col items-center py-10">
    <h1 class="text-3xl font-bold mb-10 mt-10">Your Profile</h1>

    <div class="bg-[#800000] text-white w-full max-w-5xl rounded-xl p-10" id="profile_container">

        <div class="flex flex-col items-center mb-10">
            <div id="profile_pic_container" class="w-32 h-32 bg-gray-300 rounded-full overflow-hidden mb-3">
                <div id="profile_pic" class="w-full h-full bg-gray-500">
                    <img src="../assets/images/default-profile.jpg" alt="Profile">
                </div>
            </div>

            <div id="profile_username_container" class="text-xl font-semibold mb-4">
                <?= htmlspecialchars($userDetails['username'] ?? "User") ?>
            </div>

            <form action="../includes/updateProfile.php" method="post" class="w-full">
                <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-end">

            <div class="flex flex-col">
                <div class="font-semibold mb-3">PERSONAL DETAILS:</div>

                <div class="bg-white text-black px-4 py-2 rounded-lg mb-3">
                    <label for="firstname" class="text-xs font-bold">FIRST NAME</label>
                    <input type="text" id="firstname" name="first_name"
                           value="<?= htmlspecialchars($userDetails['first_name'] ?? '') ?>"
                           class="w-full bg-transparent outline-none">
                </div>

                <div class="bg-white text-black px-4 py-2 rounded-lg mb-3">
                    <label for="lastname" class="text-xs font-bold">LAST NAME</label>
                    <input type="text" id="lastname" name="last_name"
                           value="<?= htmlspecialchars($userDetails['last_name'] ?? '') ?>"
                           class="w-full bg-transparent outline-none">
                </div>

                <div class="bg-white text-black px-4 py-2 rounded-lg mb-3">
                    <label for="password" class="text-xs font-bold">NEW PASSWORD</label>
                    <input type="password" id="password" name="password"
                           class="w-full bg-transparent outline-none"
                           placeholder="Leave blank to keep current password">
                </div>

                <div class="bg-white text-black px-4 py-2 rounded-lg mb-3">
                    <label for="email" class="text-xs font-bold">EMAIL</label>
                    <input type="email" id="email" name="email"
                           value="<?= htmlspecialchars($userDetails['email'] ?? '') ?>"
                           class="w-full bg-transparent outline-none">
                </div>

                <div class="bg-white text-black px-4 py-2 rounded-lg mb-3">
                    <label for="phone" class="text-xs font-bold">PHONE</label>
                    <input type="tel" id="phone" name="phone"
                           value="<?= htmlspecialchars($userDetails['phone'] ?? '') ?>"
                           class="w-full bg-transparent outline-none">
                </div>
            </div>

            <div class="flex flex-col">
                <div class="font-semibold mb-3">ADDRESS DETAILS:</div>

                <div class="bg-white text-black px-4 py-2 rounded-lg mb-3">
                    <label for="province" class="text-xs font-bold">PROVINCE</label>
                    <input type="text" id="province" name="province"
                           value="<?= htmlspecialchars($defaultAddress['province'] ?? '') ?>"
                           class="w-full bg-transparent outline-none">
                </div>

                <div class="bg-white text-black px-4 py-2 rounded-lg mb-3">
                    <label for="city" class="text-xs font-bold">CITY/MUNICIPALITY</label>
                    <input type="text" id="city" name="city"
                           value="<?= htmlspecialchars($defaultAddress['city'] ?? '') ?>"
                           class="w-full bg-transparent outline-none">
                </div>

                <div class="bg-white text-black px-4 py-2 rounded-lg mb-3">
                    <label for="address" class="text-xs font-bold">STREET ADDRESS</label>
                    <input type="text" id="address" name="street_address"
                           value="<?= htmlspecialchars($defaultAddress['street_address'] ?? '') ?>"
                           class="w-full bg-transparent outline-none">
                </div>

                

                <div class="bg-white text-black px-4 py-2 rounded-lg mb-3">
                    <label for="postal_code" class="text-xs font-bold">POSTAL CODE</label>
                    <input type="text" id="postal_code" name="postal_code"
                           value="<?= htmlspecialchars($defaultAddress['postal_code'] ?? '') ?>"
                           class="w-full bg-transparent outline-none">
                </div>

                <div class="bg-white text-black px-4 py-2 rounded-lg mb-3">
                    <label for="address_type" class="text-xs font-bold">ADDRESS TYPE</label>
                    <select name="address_type" id="address_type" class="w-full bg-transparent outline-none">
                        <option value="home" <?= ($defaultAddress['address_type'] ?? '') === "home" ? "selected" : "" ?>>Home</option>
                        <option value="work" <?= ($defaultAddress['address_type'] ?? '') === "work" ? "selected" : "" ?>>Work</option>
                    </select>
                </div>

                <div class="flex items-center gap-4 mt-3">
                    <input type="checkbox" id="default_address" name="default_address"
                        <?= ($defaultAddress) ? 'checked' : '' ?>
                        class="w-5 h-5 rounded accent-black">
                    <label for="default_address">Default</label>
                </div>
            </div>
        </div>

        <div class="mt-10">
            <button type="submit"
                class="bg-white text-[#800000] px-6 py-3 rounded-lg font-bold w-full">
                SAVE CHANGES
            </button>
        </div>

        </form>

    </div>
</main>
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
