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
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MANZANAS Account Portal</title>
    <link rel="stylesheet" href="./assets/dist/loginTW.css">
</head>
<body class="bg-black">
    <header class="bg-white w-full h-30 flex justify-between items-center" >
        <img  src="./assets/images/MANSANAS LOGO 2.png" class="w-[30%] [h-30%] "/>
    </header>
    <div class="text-white font font-bold text-2xl ml-18 p-5"  >
            <h1 >MANZANAS ACCOUNT PORTAL</h1>
    </div>
    
    <main class="flex item-center ml-[20%] p-15" >
        <div class = "login_container" >
            <div class="center">
                <img src="./assets/images/Masanas Logo standalone 1.png" alt="Account Logo" class="w-[30%] [h-30%] p-5 mt-15" >
            </div>

            <h2 class="center text-2xl font-bold font">MANZANAS Account</h2>

            <p class="center font mt-5">Enter your account to log in or create an account</p>

            <form  action="auth/login.php" method="post" id="login-form">

                <div class="ml-20 mt-5 p-5">
                    <label class="font font-bold" for="username">Username:</label><br>
                    <input class="w-150 border-3" id="username" name="username">
                </div>

                <div class="ml-20 p-5" >
                    <label class="font font-bold" for="password">Password:</label><br>
                    <input class="w-150 border-3" type="password" id="password" name="password">
                </div>

                <div class="ml-25">
                    <input class="accent-[#800000]" type="checkbox" id="stay-logged-in">
                    <label for="stay-logged-in">Stay logged in</label>
                </div>

                <div class="center mt-5 mb-20" >
                    <button class="buttons mr-5 font" type="submit" name="login"id="login-btn">Log In</button>
                    <a href="./auth/register.php"><button class="buttons ml-5 font" type="button" name="registration" id="register-btn">Register</button></a>
                </div>

                <div class="ml-25 w-[70%]">
                    <p class="font">
                        By proceeding, I agree to the 
                        <a href="" class="text-[#800000] font-bold">MANZANAS Account Terms of Use</a>
                        and 
                        <a href="" class="text-[#800000] font-bold">Privacy Policy</a>
                    </p>
                </div>

            </form>
        </div>
    </main>
    <script>
        function redirectToRegister() {
            window.location.href = 'auth/register.php';
        }
        // we use this for client side, and redirectPage for server side
    </script>
</body>
</html>