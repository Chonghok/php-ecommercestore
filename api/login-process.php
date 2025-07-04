<?php
header('Content-Type: application/json');
require_once '../includes/config.php';
session_start();

// Get form data
$identifier = trim($_POST['loginIdentifier'] ?? '');
$password = $_POST['password'] ?? '';

// Validate inputs
if (empty($identifier)) {
    echo json_encode([
        'success' => false,
        'message' => 'Email or username is required',
        'errorField' => 'identifier'
    ]);
    exit;
}

if (empty($password)) {
    echo json_encode([
        'success' => false,
        'message' => 'Password is required',
        'errorField' => 'password'
    ]);
    exit;
}

try {
    // Check if user exists
    $stmt = $conn->prepare("SELECT userID, username, email, password FROM user WHERE email = ? OR username = ?");
    $stmt->execute([$identifier, $identifier]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode([
            'success' => false,
            'message' => 'No account found with this email/username',
            'errorField' => 'identifier'
        ]);
        exit;
    }

    // Verify password
    if (!password_verify($password, $user['password'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Incorrect password',
            'errorField' => 'password'
        ]);
        exit;
    }

    // Set session variables
    $_SESSION['userID'] = $user['userID'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['logged_in'] = true;

    echo json_encode([
        'success' => true,
        'message' => 'Login successful!',
        'redirect' => 'index.php'
    ]);
    
} catch (PDOException $e) {
    error_log("Login Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'A system error occurred. Please try again later.'
    ]);
}
?>