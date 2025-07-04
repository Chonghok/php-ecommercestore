<?php
session_start();
if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot-password.php");
    exit;
}
$email = htmlspecialchars($_SESSION['reset_email']);
?>
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
    <link rel="stylesheet" href="assets/css/reset-password.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    
    <?php require_once 'includes/header.php' ?>

    <div class="signup-container">
        <div class="form-wrapper">
            <h2 style="text-align: center;">Reset Password</h2>
            <p class="subtitle">Resetting password for: <strong><?= $email ?></strong></p>
            <div id="formMessage" class="form-message"></div>
            <form id="resetPasswordForm" action="api/reset-forgot-password.php" method="POST">
                
                <div class="input-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your new password" required />
                </div>

                <div class="input-group">
                    <label for="confirm-password">Confirm New Password</label>
                    <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your new password" required />
                    <p id="passwordError" class="error-message"></p>
                </div>
                <button type="submit">Reset Password</button>
            </form>
        </div>
    </div>

    <?php require_once 'includes/footer.php' ?>

    <!-- Add this confirmation modal -->
    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <p>Are you sure you want to reset your password?</p>
            <div class="modal-buttons">
                <button id="confirmSubmit">Yes</button>
                <button id="cancelSubmit">Cancel</button>
            </div>
        </div>
    </div>

</body>
<script src="assets/js/script.js"></script>
<script src="assets/js/reset-password.js"></script>
</html>
