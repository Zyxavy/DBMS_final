<?php
session_start();
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../Classes/RegisterClass.php";
require_once __DIR__ . "/../includes/functions.php";

$error = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST['back_btn'])) 
    {
        redirectToPage('../index.php');
    }


    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) 
    {
        $error = "Invalid request.";
    } else {

        $firstName = sanitizeInput($_POST["firstName"]);
        $lastName = sanitizeInput($_POST["lastName"]);
        $address = sanitizeInput($_POST["address"]);
        $province = sanitizeInput($_POST["province"]);
        $city = sanitizeInput($_POST["city"]);
        $unitNum = sanitizeInput($_POST["unit_num"]);
        $postalCode = sanitizeInput($_POST["postal_code"]);
        $type = sanitizeInput($_POST["type"]);
        $email = sanitizeInput($_POST["email"]);
        $phone = sanitizeInput($_POST["phone"]);
        $password = $_POST["password"];
        $confirmPassword = $_POST["confirmPassword"];
        $username = sanitizeInput($_POST["username"]);

        if ($password !== $confirmPassword) 
        {
            $error = "Passwords do not match.";
        } 
        elseif (strlen($password) < 6) 
        {
            $error = "Password must be at least 6 characters long.";
        } 
        elseif (!isValidEmail($email)) 
        {
            $error = "Invalid email format.";
        } 
        elseif (!isValidPhone($phone)) 
        {
            $error = "Invalid phone number.";
        } 
        else 
        {
            $reg = new Register($firstName, $lastName, $email, $phone, $username, $password);
            $result = $reg->registerUser();

            if ($result) 
            {
                $reg->createAddress($username, $type, $address, $city, $province, $postalCode, $unitNum);
                $_SESSION["username"] = $username;
                $_SESSION['success_message'] = "Registration successful! Redirecting to products...";
                $success = true;

                redirectToPage("../pages/products.php");
                exit();
            } 
            else 
            {
                $error = "Registration failed: username or email may already exist.";
            }
        }
    }
}

//Generate CSRF token for form
$csrfToken = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MANZANAS - Create Account</title>
<link rel="stylesheet" href="../auth/authStyles.css">
</head>
<style>

.error-message,
.success-message {
    padding: 12px 20px;
    margin-bottom: 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    text-align: center;
    max-width: 100%;
    box-sizing: border-box;
}

.error-message {
    background-color: #f8d7da;
    color: #842029;
    border: 1px solid #f5c2c7;
}

.success-message {
    background-color: #d1e7dd;
    color: #0f5132;
    border: 1px solid #badbcc;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.error-message,
.success-message {
    animation: fadeIn 0.5s ease-in-out;
}
</style>

<body>
<div class="register-container">
    <a href="../index.php" class="btn-primary"><button class="btn-primary">Back to Log-in</button></a>

    <div class="logo-section">
        <img src="../assets/images/Masanas Logo standalone 1.png" alt="MANZANAS Logo" class="logo-icon">
        <h1>MANZANAS Account Creation</h1>
        <p class="subtitle">To create an account, fill out the form below.</p>
    </div>

    <?php if ($error): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="success-message"><?= $_SESSION['success_message'] ?></div>
    <?php endif; ?>

    <form id="registerForm" action="register.php" method="post">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

        <div class="form-section">
            <h3>Personal Information</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="firstName">First Name:</label>
                    <input type="text" id="firstName" name="firstName" required value="<?= htmlspecialchars($firstName ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name:</label>
                    <input type="text" id="lastName" name="lastName" required value="<?= htmlspecialchars($lastName ?? '') ?>">
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3>Address Information</h3>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required value="<?= htmlspecialchars($address ?? '') ?>">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="province">Province/State/Region:</label>
                    <input type="text" id="province" name="province" required value="<?= htmlspecialchars($province ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="city">City/Municipality:</label>
                    <input type="text" id="city" name="city" required value="<?= htmlspecialchars($city ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="unit_num">Unit/House/Lot Number / Land Mark:</label>
                    <input type="text" id="unit_num" name="unit_num" required value="<?= htmlspecialchars($unitNum ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="postal_code">Postal Code:</label>
                    <input type="number" id="postal_code" name="postal_code" required value="<?= htmlspecialchars($postalCode ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="type">Address Type:</label>
                    <select name="type" id="type">
                        <option value="Home" <?= (isset($type) && $type === "Home") ? 'selected' : '' ?>>Home</option>
                        <option value="Work" <?= (isset($type) && $type === "Work") ? 'selected' : '' ?>>Work</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3>Contact Information</h3>
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($email ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" required value="<?= htmlspecialchars($phone ?? '') ?>">
            </div>
        </div>

        <div class="form-section">
            <h3>Account Details</h3>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required value="<?= htmlspecialchars($username ?? '') ?>">
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

document.getElementById('registerForm').addEventListener('submit', function(e) 
{
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    if(password !== confirmPassword) {
        alert('Passwords do not match!');
        e.preventDefault();
        return;
    }
    if(password.length < 6) {
        alert('Password must be at least 6 characters long!');
        e.preventDefault();
        return;
    }
});
</script>
</body>
</html>
