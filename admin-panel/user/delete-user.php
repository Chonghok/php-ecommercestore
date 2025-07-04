<?php
session_start();
require_once '../admin-includes/config.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['admin_logged_in'])) {
    http_response_code(403);
    exit(json_encode(['status' => 'error', 'message' => 'Unauthorized']));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['user_id'])) {
    http_response_code(400);
    exit(json_encode(['status' => 'error', 'message' => 'Invalid request']));
}

$userID = $_POST['user_id'];

try {
    // Check if user exists first
    $checkStmt = $conn->prepare("SELECT userID FROM user WHERE userID = :userID");
    $checkStmt->bindParam(':userID', $userID);
    $checkStmt->execute();
    
    if ($checkStmt->rowCount() === 0) {
        exit(json_encode(['status' => 'error', 'message' => 'User not found']));
    }

    // Delete the user
    $stmt = $conn->prepare("DELETE FROM user WHERE userID = :userID");
    $stmt->bindParam(':userID', $userID);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'User deleted successfully', 'userID' => $userID]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete user']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
exit;
?>