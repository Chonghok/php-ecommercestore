<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['userID'])) {
    die(json_encode(['success' => false, 'message' => 'Please login first']));
}

require_once '../includes/config.php';

try {
    $stmt = $conn->prepare("DELETE FROM wishlist WHERE userID = ?");
    $stmt->execute([$_SESSION['userID']]);
    
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}