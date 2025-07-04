<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['userID'])) {
    // Return a specific response that we'll handle in JavaScript
    // die(json_encode([
    //     'success' => false, 
    //     'message' => 'Please login first',
    //     'redirect' => true
    // ]));

    die(json_encode(['success' => false, 'message' => 'Please login first']));
}

require_once '../includes/config.php';

$data = json_decode(file_get_contents('php://input'), true);
$productId = (int)($data['product_id'] ?? 0);
$action = $data['action'] ?? 'add';

try {
    if ($action === 'add') {
        $stmt = $conn->prepare("INSERT INTO wishlist (userID, productID) VALUES (?, ?)");
        $stmt->execute([$_SESSION['userID'], $productId]);
    } else {
        $stmt = $conn->prepare("DELETE FROM wishlist WHERE userID = ? AND productID = ?");
        $stmt->execute([$_SESSION['userID'], $productId]);
    }
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}