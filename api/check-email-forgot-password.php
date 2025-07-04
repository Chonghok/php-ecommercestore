<?php
session_start();
require_once '../includes/config.php'; // connect to DB


$email = $_POST['emailCheck'] ?? '';
if (empty($email)) {
    echo json_encode([
        'success' => false,
        'message' => 'Email is required',
        'errorField' => 'identifier'
    ]);
    exit;
}
try {
    $stmt = $conn->prepare("SELECT userID FROM user WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode([
            'success' => false,
            'message' => 'No account found with this email',
            'errorField' => 'identifier'
        ]);
        exit;
    }

    $_SESSION['reset_email'] = $email;

    echo json_encode([
        'success' => true,
        'message' => 'Account found. You can reset your password.'
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