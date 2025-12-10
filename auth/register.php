<?php
session_start();
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../Classes/RegisterClass.php";
require_once __DIR__ . "/../includes/functions.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") 
{

    if (isset($_POST['back_btn'])) 
    {
        redirectToPage('../index.php');
        exit();
    }

    $firstName = htmlspecialchars($_POST["firstname"]);
    $lastName = htmlspecialchars($_POST["lastname"]);

    $address = htmlspecialchars($_POST["address"]);
    $province = htmlspecialchars($_POST["province"]);
    $city = htmlspecialchars($_POST["city"]);
    $unitNum = htmlspecialchars($_POST["unit_num"]);
    $postalCode = htmlspecialchars($_POST["postal_code"]);
    $type = htmlspecialchars($_POST["type"]);

    $email = htmlspecialchars($_POST["email"]);
    $phone = htmlspecialchars($_POST["phone"]);

    $password = htmlspecialchars($_POST["pass"]);
    $username = htmlspecialchars($_POST["username"]);
    $error = "";

    $reg = new Register($firstName, $lastName, $email, $phone, $username, $password);
    $result = $reg->registerUser();

    if ($result) 
    {
        $reg->createAddress($username, $type, $address, $city, $province, $postalCode, $unitNum);

        $_SESSION["username"] = $username;
        redirectToPage("../pages/products.php");
        exit();
    } else 
    {
        $error = "Registration Failed";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MANZANAS - Create Account</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <div class="register-container">
        <div class="logo-section">
            <img src="assets/images/Manzanas.jpeg" alt="Manzanas Logo" class="logo-icon">
            <h1>MANZANAS Account Creation</h1>
            <p class="subtitle">To create an account fill out the form below.</p>
        </div>

        <form action="register.php" method="post">
            <div class="form-section">
                <h3>Personal Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName">First Name:</label>
                        <input type="text" id="firstName" name="firstName" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name:</label>
                        <input type="text" id="lastName" name="lastName" required>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3>Address Information</h3>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="province">Province/State/Region:</label>
                        <input type="text" id="province" name="province" required>
                    </div>
                    <div class="form-group">
                        <label for="city">City/Municipality:</label>
                        <input type="text" id="city" name="city" required>
                    </div>
                    <div class="form-group">
                        <label for="unit_num">Unit/House/Lot Number:</label>
                        <input type="text" id="unit_num" name="unit_num" required>
                    </div>
                    <div class="form-group">
                        <label for="postal_code">Postal Code:</label>
                        <input type="number" id="postal_code" name="postal_code" required>
                    </div>
                    <div class="form-group">
                        <label for="type">Address Type:</label>

                        <select name="type" id="type">
                        <option value="Home">Home</option>
                        <option value="Work">Work</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3>Contact Information</h3>
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
            </div>

            <div class="form-section">
                <h3>Account Details</h3>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password:</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-primary">SUBMIT</button>
        </form>

        <p class="terms">
            By creating an account you agree to the <a href="#">MANZANAS Terms of Use</a> and <a href="#">Privacy Policy</a>.
        </p>
    </div>

    <script>
        // Handle registration form submission
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const password = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                return;
            }
            
            if (password.length < 6) {
                alert('Password must be at least 6 characters long!');
                return;
            }
        });
    </script>
</body>
</html>