<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up - Lets Gear</title>
    <link rel="icon" href="images/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="assets/css/register.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    
    <?php require_once 'includes/header.php' ?>

    <div class="signup-container">
        <div class="form-wrapper">
            <h2 style="text-align: center;">Create Account</h2>
            <p class="subtitle">Please fill in the information below</p>
            <!-- Add this div to show form submission messages -->
            <div id="formMessage" class="form-message"></div>
            <form id="signupForm" action="api/sign-up-process.php" method="POST">
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required />
                </div>

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required />
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required />
                </div>

                <div class="input-group">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password" required />
                    <!-- Add this for password mismatch message -->
                    <p id="passwordError" class="error-message"></p>
                </div>
                
                <button type="submit">Register</button>
            </form>
            <div class="signup-prompt">
                Already have an account?
                <button class="secondary-btn" onclick="goToLogin()">Log in</button>
            </div>
        </div>
    </div>

    <?php require_once 'includes/footer.php' ?>

    <!-- Add this confirmation modal -->
    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <p>Are you sure you want to submit this information?</p>
            <div class="modal-buttons">
                <button id="confirmSubmit">Yes, Submit</button>
                <button id="cancelSubmit">Cancel</button>
            </div>
        </div>
    </div>

</body>
<script src="assets/js/script.js"></script>
<script src="assets/js/register.js"></script>
</html>
