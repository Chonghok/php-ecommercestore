<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/product-functions.php';

// Set CORS headers
header("Access-Control-Allow-Origin: " . (isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : ''));
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if (!isset($_SESSION['logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$userID = $_SESSION['userID'];
$response = ['success' => false];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';
        
        switch ($action) {
            case 'update':
                if (isset($_POST['productID'], $_POST['quantity'])) {
                    $productID = $_POST['productID'];
                    $quantity = (int)$_POST['quantity'];
                    
                    // Update quantity in cart
                    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE userID = ? AND productID = ?");
                    $stmt->execute([$quantity, $userID, $productID]);
                    
                    $response['success'] = true;
                    $response['cartCount'] = getCartCount($conn, $userID);
                }
                break;
                
            case 'remove':
                if (isset($_POST['productID'])) {
                    $productID = $_POST['productID'];
                    
                    // Remove item from cart
                    $stmt = $conn->prepare("DELETE FROM cart WHERE userID = ? AND productID = ?");
                    $stmt->execute([$userID, $productID]);
                    
                    $response['success'] = true;
                    $response['cartCount'] = getCartCount($conn, $userID);
                }
                break;
                
            case 'clear':
                // Clear all items from cart
                $stmt = $conn->prepare("DELETE FROM cart WHERE userID = ?");
                $stmt->execute([$userID]);
                
                $response['success'] = true;
                $response['cartCount'] = 0;
                break;
                
            default:
                $response['message'] = 'Invalid action';
        }
    }
} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>