<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password - Lets Gear</title>
    <link rel="icon" href="images/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="assets/css/forgot-password.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="light-mode">

    <?php require_once 'includes/header.php' ?>

    <div class="login-container">
        <div class="form-wrapper">
            <h2 style="text-align: center; margin-bottom: 30px;">Reset Password</h2>
            <div id="formMessage" class="form-message"></div>
            <form class="login-form" id="loginForm" method="POST">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="text" id="emailInput" name="emailCheck" placeholder="Enter your email" required />
                    <p id="emailError" class="error-message"></p>
                </div>
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

    <?php require_once 'includes/footer.php' ?>

</body>
<script src="assets/js/script.js"></script>
<script src="assets/js/forgot-password.js"></script>
</html>