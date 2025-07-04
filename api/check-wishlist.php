<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['userID'])) {
    die(json_encode(['isFavorited' => false]));
}

require_once '../includes/config.php';

$productId = (int)($_GET['product_id'] ?? 0);

try {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM wishlist WHERE userID = ? AND productID = ?");
    $stmt->execute([$_SESSION['userID'], $productId]);
    echo json_encode(['isFavorited' => $stmt->fetchColumn() > 0]);
} catch (PDOException $e) {
    echo json_encode(['isFavorited' => false]);
}