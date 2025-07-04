<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['userID'])) {
    die(json_encode(['success' => false]));
}

require_once '../includes/config.php';

try {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM wishlist WHERE userID = ?");
    $stmt->execute([$_SESSION['userID']]);
    echo json_encode(['success' => true, 'count' => $stmt->fetchColumn()]);
} catch (PDOException $e) {
    echo json_encode(['success' => false]);
}