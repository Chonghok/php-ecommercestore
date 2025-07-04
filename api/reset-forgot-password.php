<?php
session_start();
header('Content-Type: application/json');
require_once '../includes/config.php'; // More reliable path

// Get form data
$emailReset = $_SESSION['reset_email'] ?? '';
$password = $_POST['password'] ?? '';

// Validate inputs
if (empty($password)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

try {
    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Update new password
    $stmt = $conn->prepare("UPDATE user SET password = ? WHERE email = ?");
    $stmt->execute([$hashedPassword, $emailReset]);
    unset($_SESSION['reset_email']);

    echo json_encode([
        'success' => true, 
        'message' => 'Password reset successfully.',
        'redirect' => 'login.php'
    ]);
    
} catch (PDOException $e) {
    error_log("Signup Error: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Reset password failed. Please try again later.'
    ]);
}
?>