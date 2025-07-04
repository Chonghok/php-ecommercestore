<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/product-functions.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $email = $_POST['email'];
    $fullName = $_POST['fullname'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $paymentMethod = $_POST['paymentMethod'];
    $userID = $_SESSION['userID'];
    
    if ($paymentMethod === 'qr') {
        $paymentMethod = 'QR Code';
    } else {
        $paymentMethod = 'Cash on Delivery';
    }
    $phone = preg_replace('/\D/', '', $phone);
    // Get cart items
    $cartItems = getCartProducts($conn, $userID);
    
    if (empty($cartItems)) {
        $_SESSION['error'] = "Your cart is empty";
        header("Location: checkout.php");
        exit;
    }
    
    // Calculate totals
    $subtotal = 0;
    foreach ($cartItems as $item) {
        $price = $item['discount'] > 0 ? 
            $item['price'] * (1 - ($item['discount'] / 100)) : 
            $item['price'];
        $subtotal += number_format($price * $item['quantity'], 2);
    }
    $shippingFee = floor($subtotal * 0.10);
    $totalAmount = $subtotal + $shippingFee;
    
    try {
        $conn->beginTransaction();
        
        // Insert into orders table
        $orderSQL = "INSERT INTO orders (userID, email, fullName, address, phoneNumber, 
                     shippingFee, totalAmount, paymentMethod, status, orderDate)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending', NOW())";
        $stmt = $conn->prepare($orderSQL);
        $stmt->execute([
            $userID, $email, $fullName, $address, $phone, 
            $shippingFee, $totalAmount, $paymentMethod
        ]);
        $orderID = $conn->lastInsertId();
        
        // Insert into orderdetail table
        $detailSQL = "INSERT INTO orderdetail (orderID, productID, productName, quantity, unitPrice)
                      VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($detailSQL);
        
        foreach ($cartItems as $item) {
            $unitPrice = $item['discount'] > 0 ? 
                $item['price'] * (1 - ($item['discount'] / 100)) : 
                $item['price'];
            $unitPrice = (float)number_format($unitPrice, 2);
            
            $stmt->execute([
                $orderID, 
                $item['productID'], 
                $item['name'], 
                $item['quantity'], 
                $unitPrice
            ]);

            // Deduct stock
            $updateStockSQL = "UPDATE product SET stock = stock - ? WHERE productID = ?";
            $stmtUpdate = $conn->prepare($updateStockSQL);
            $stmtUpdate->execute([$item['quantity'], $item['productID']]);
        }
        
        // Clear the cart
        $clearCartSQL = "DELETE FROM cart WHERE userID = ?";
        $stmt = $conn->prepare($clearCartSQL);
        $stmt->execute([$userID]);
        
        $conn->commit();
        
        // Store order ID in session for success page
        $_SESSION['order_id'] = $orderID;
        header("Location: order-success.php");
        exit;
        
    } catch (PDOException $e) {
        $conn->rollBack();
        $_SESSION['error'] = "Order processing failed: " . $e->getMessage();
        header("Location: checkout.php");
        exit;
    }
} else {
    header("Location: checkout.php");
    exit;
}
?>