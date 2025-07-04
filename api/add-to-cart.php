<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['userID'])) {
    die(json_encode(['success' => false, 'message' => 'Please login first']));
}

require_once '../includes/config.php';

$data = json_decode(file_get_contents('php://input'), true);
$productId = (int)($data['product_id'] ?? 0);
$quantity = (int)($data['quantity'] ?? 1);

try {
    // Check if product already exists in cart
    $stmt = $conn->prepare("SELECT * FROM cart WHERE userID = ? AND productID = ?");
    $stmt->execute([$_SESSION['userID'], $productId]);
    $existingItem = $stmt->fetch();

    if ($existingItem) {
        // Update quantity if item exists
        $newQuantity = $existingItem['quantity'] + $quantity;
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE cartID = ?");
        $stmt->execute([$newQuantity, $existingItem['cartID']]);
    } else {
        // Add new item to cart
        $stmt = $conn->prepare("INSERT INTO cart (userID, productID, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['userID'], $productId, $quantity]);
    }

    // Get updated cart count
    $countStmt = $conn->prepare("SELECT COUNT(*) FROM cart WHERE userID = ?");
    $countStmt->execute([$_SESSION['userID']]);
    $cartCount = $countStmt->fetchColumn() ?? 0;

    echo json_encode([
        'success' => true,
        'count' => $cartCount
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}