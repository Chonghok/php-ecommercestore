<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Log In - Lets Gear</title>
    <link rel="icon" href="images/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="light-mode">

    <?php require_once 'includes/header.php' ?>

    <div class="login-container">
        <div class="form-wrapper">
            <h2 style="text-align: center;">Welcome Back</h2>
            <p class="subtitle">Please log in to continue to <strong>Lets Gear</strong></p>
            <!-- Add this message container below the subtitle -->
            <div id="formMessage" class="form-message"></div>
            <form class="login-form" id="loginForm" method="POST">
                <div class="input-group">
                    <label for="loginIdentifier">Email or Username</label>
                    <input type="text" id="loginIdentifier" name="loginIdentifier" placeholder="Enter your email or username" required />
                    <p id="identifierError" class="error-message"></p>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="loginPassword" name="password" placeholder="Enter your password" required />
                    <!-- Add error message container -->
                    <p id="passwordError" class="error-message"></p>
                </div>
                

                <button type="submit">Login</button>
                <a href="forgot-password.php" class="forgot-password">Forgot password?</a>
                <div class="signup-prompt">
                    <p>Don't have an account?</p>
                    <button type="button" class="secondary-btn" onclick="handleSignUp()">Create an Account</button>
                </div>
            </form>
        </div>
    </div>

    <?php require_once 'includes/footer.php' ?>

</body>
<script src="assets/js/script.js"></script>
<script src="assets/js/login.js"></script>
</html>
