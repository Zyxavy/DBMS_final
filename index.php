<?php 
    session_start();
    require_once __DIR__ . "/includes/functions.php";

    if($_SERVER["REQUEST_METHOD"] === "POST")
    {
        if(isset($_POST['registration']))
        {
            redirectToPage("auth/register.php");
            exit();
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MANZANAS Account Portal - Login</title>
    <link rel="stylesheet" href="auth/authStyles.css">
</head>
<body>
    <div class="white-rectangle">
        <div class="logo-header">
             <img src="assets/images/MANSANAS LOGO 1.jpg" alt="Manzanas Logo with name">
         </div>
    </div>

    <div class="login-container">
        <div class="logo-section">
            <img src="assets/images/Masanas Logo standalone 1.png" alt="Manzanas Logo" class="logo-icon">
            <h1>MANZANAS ACCOUNT PORTAL</h1>
            <p class="subtitle">Enter your account to log in or create an account</p>
        </div>

        <form action="auth/login.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="stayLoggedIn" name="stayLoggedIn">
                <label for="stayLoggedIn">Stay logged in</label>
            </div>

            <div class="button-group">
                <button type="submit" class="btn-primary">Log In</button>
                <button type="button" class="btn-secondary" onclick="redirectToRegister()">Register</button>
            </div>
        </form>

        <p class="terms">
            By proceeding, i agree to the <a href="#">MANZANAS Account Terms of Use</a> and <a href="#">Privacy Policy</a>.
        </p>

        <p class="copyright">Copyright 2025 MANZANAS</p>
    </div>

    <script>
        function redirectToRegister() {
            window.location.href = 'auth/register.php';
        }
        // we use this for client side, and redirectPage for server side
    </script>
</body>
</html>