<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['userID'])) {
    die(json_encode(['success' => false, 'message' => 'Please login first']));
}

require_once '../includes/config.php';

$data = json_decode(file_get_contents('php://input'), true);
$productId = (int)($data['product_id'] ?? 0);

try {
    $stmt = $conn->prepare("DELETE FROM wishlist WHERE userID = ? AND productID = ?");
    $stmt->execute([$_SESSION['userID'], $productId]);
    
    // Get updated wishlist count
    $countStmt = $conn->prepare("SELECT COUNT(*) FROM wishlist WHERE userID = ?");
    $countStmt->execute([$_SESSION['userID']]);
    $wishlistCount = $countStmt->fetchColumn() ?? 0;
    
    echo json_encode([
        'success' => true,
        'count' => $wishlistCount
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}