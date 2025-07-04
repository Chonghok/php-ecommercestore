<?php
session_start();
require_once '../admin-includes/config.php';
require_once '../admin-includes/admin-functions.php';

if (!isset($_SESSION['admin_logged_in'])){
    header("Location: ../login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // Validate inputs
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Please fill in all fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } else {
        try {
            // Check if username or email already exists
            $stmt = $conn->prepare("SELECT adminID FROM admin WHERE username = :username OR email = :email");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $error = 'Username or email already exists';
            } else {
                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert new admin
                $stmt = $conn->prepare("INSERT INTO admin (username, email, password) VALUES (:username, :email, :password)");
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashedPassword);
                
                if ($stmt->execute()) {
                    $success = 'Admin account created successfully!';
                    // Clear form fields
                    $_POST = array();
                } else {
                    $error = 'Failed to create admin account';
                }
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LetsGear Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../admin-assets/css/style.css">
    <link rel="stylesheet" href="../admin-assets/css/admin.css">
</head>
<body>
    
    <?php require_once '../admin-includes/header.php' ?>

    <?php require_once '../admin-includes/sidebar.php' ?>

    <main class="create-admin">
        <h2>Create New Admin</h2>

        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form id="adminForm" method="POST" action="create-admin.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter username" 
                    value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required />
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter email"
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required />
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter password" required />
            </div>
            <div class="form-actions">
                <button type="button" class="btn-back" onclick="window.location.href='index.php'">Back</button>
                <button type="submit" id="submitBtn">Create</button>
            </div>
        </form>
    </main>

    <div class="confirm-modal" id="confirmModal">
        <div class="confirm-content">
            <div class="confirm-message" id="confirmMessage">Are you sure you want to create this admin account?</div>
            <div class="confirm-buttons">
                <button class="confirming-btn cancelbtn" id="btnCancel">Cancel</button>
                <button class="confirming-btn confirmbtn" id="btnConfirm">Create</button>
            </div>
        </div>
    </div>

    <div class="confirmation-modal" id="confirmationModal">
        <div class="confirmation-content">
            <div class="confirmation-message" id="confirmationMessage">Are you sure you want to log out?</div>
            <div class="confirmation-buttons">
                <button class="confirmation-btn cancel-btn" id="cancelBtn">Cancel</button>
                <button class="confirmation-btn confirm-btn" id="confirmBtn">Log Out</button>
            </div>
        </div>
    </div>
</body>
<script src="../admin-assets/js/script.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('adminForm');
    const confirmModal = document.getElementById('confirmModal'); // Note: matches your HTML id
    const btnCancel = document.getElementById('btnCancel');
    const btnConfirm = document.getElementById('btnConfirm');
    
    // Change the submit button to type="button" to prevent default submission
    const submitBtn = document.getElementById('submitBtn');
    
    submitBtn.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent default form submission
        
        // Check if form is valid (browser validation)
        if (form.checkValidity()) {
            // Show confirmation modal
            confirmModal.classList.add('active');
        }
        // If not valid, browser will show validation messages
    });
    
    btnCancel.addEventListener('click', function() {
        confirmModal.classList.remove('active');
    });
    
    btnConfirm.addEventListener('click', function() {
        // Submit the form when confirmed
        form.submit();
    });
    
    // Close modal when clicking outside
    confirmModal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
        }
    });
    
    // Close with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && confirmModal.classList.contains('active')) {
            confirmModal.classList.remove('active');
        }
    });
});
</script>
</html>
