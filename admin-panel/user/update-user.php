<?php
session_start();
require_once '../admin-includes/config.php';
require_once '../admin-includes/admin-functions.php';

if (!isset($_SESSION['admin_logged_in'])){
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])){
    header("Location: index.php");
    exit;
}

$userID = $_GET['id'];
$user = getUserByID($conn, $userID);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // Validate inputs
    if (empty($username) || empty($email)) {
        $error = 'Username and email are required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } else {
        try {
            // Check if username or email already exists (excluding current admin)
            $stmt = $conn->prepare("SELECT userID FROM user WHERE (username = :username OR email = :email) AND userID != :userID");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':userID', $userID);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $error = 'Username or email already exists';
            } else {
                // Prepare base update query
                $sql = "UPDATE user SET username = :username, email = :email";
                $params = [
                    ':username' => $username,
                    ':email' => $email,
                    ':userID' => $userID
                ];
                
                // Only update password if provided
                if (!empty($password)) {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $sql .= ", password = :password";
                    $params[':password'] = $hashedPassword;
                }
                
                $sql .= " WHERE userID = :userID";
                
                // Execute update
                $stmt = $conn->prepare($sql);
                $stmt->execute($params);
                
                $success = 'User account updated successfully!';
                // Refresh admin data
                $user = getUserByID($conn, $userID);
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>LetsGear Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../admin-assets/css/style.css">
    <link rel="stylesheet" href="../admin-assets/css/user.css">
</head>
<body>
    
    <?php require_once '../admin-includes/header.php' ?>

    <?php require_once '../admin-includes/sidebar.php' ?>

    <main class="update-admin">
        <h2>Update User</h2>

         <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form id="adminForm" method="POST" action="">
            <input type="hidden" name="id" value="<?= $userID ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" placeholder="Enter username" required />
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" placeholder="Enter email" required />
            </div>
            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" placeholder="Enter new password (leave blank to keep current)" />
            </div>
            <div class="form-actions">
                <button type="button" class="btn-back" onclick="window.location.href='index.php'">Back</button>
                <button type="submit" id="submitBtn">Update</button>
            </div>
        </form>
    </main>

    <div class="confirm-modal" id="confirmModal">
        <div class="confirm-content">
            <div class="confirm-message" id="confirmMessage">Are you sure you want to update this user account?</div>
            <div class="confirm-buttons">
                <button class="confirming-btn cancelbtn" id="btnCancel">Cancel</button>
                <button class="confirming-btn updatebtn" id="btnUpdate">Update</button>
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
    const confirmModal = document.getElementById('confirmModal');
    const btnCancel = document.getElementById('btnCancel');
    const btnUpdate = document.getElementById('btnUpdate'); // Changed from btnConfirm to btnUpdate
    const submitBtn = document.getElementById('submitBtn');
    
    // Change the submit button to type="button" in your HTML
    submitBtn.type = 'button';
    
    submitBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Check if form is valid
        if (form.checkValidity()) {
            confirmModal.classList.add('active');
        }
    });
    
    btnCancel.addEventListener('click', function() {
        confirmModal.classList.remove('active');
    });
    
    btnUpdate.addEventListener('click', function() {
        // Change button type back to submit temporarily
        submitBtn.type = 'submit';
        // Submit the form
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
