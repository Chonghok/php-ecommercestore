<?php
session_start();
require_once '../admin-includes/config.php';
require_once '../admin-includes/admin-functions.php';

if (!isset($_SESSION['admin_logged_in'])){
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$orderID = $_GET['id'];
$order = getOrderByID($conn, $orderID);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newStatus = $_POST['status'];
    
    try {
        $stmt = $conn->prepare("UPDATE orders SET status = :status WHERE orderID = :orderID");
        $stmt->bindParam(':status', $newStatus);
        $stmt->bindParam(':orderID', $orderID);
        
        if ($stmt->execute()) {
            $success = "Order status updated successfully!";
            // Refresh order data
            $order = getOrderByID($conn, $orderID);
        } else {
            $error = "Failed to update order status";
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>LetsGear Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="../admin-assets/css/style.css">
    <link rel="stylesheet" href="../admin-assets/css/order.css">
</head>
<body>

    <?php require_once '../admin-includes/header.php' ?>

    <?php require_once '../admin-includes/sidebar.php' ?>

    <main class="order-update-main">
        <div class="order-update-form-wrapper">
            <div class="order-update-header">
                <h2>Update Order Status</h2>
            </div>

            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <form action="" method="POST" class="order-update-form">
                <div class="form-group">
                    <label for="orderid">Order ID</label>
                    <input type="text" id="orderid" name="orderid" value="<?= htmlspecialchars($orderID) ?>" readonly />
                </div>

                <div class="form-group">
                    <label for="status">Order Status</label>
                    <select id="status" name="status" class="status-dropdown">
                        <option value="Pending" <?= $order['status'] === 'Pending' ? 'selected' : '' ?>>🕒 Pending</option>
                        <option value="Completed" <?= $order['status'] === 'Completed' ? 'selected' : '' ?>>✅ Completed</option>
                        <option value="Cancelled" <?= $order['status'] === 'Cancelled' ? 'selected' : '' ?>>❌ Cancelled</option>
                    </select>
                </div>
                
                <button type="button" class="btn-back" onclick="window.location.href='index.php'">Back</button>
                <button type="submit" class="btn-update-submit">Update Status</button>
            </form>
        </div>
    </main>
  
    <div class="confirmation-modal" id="confirmationModal">
        <div class="confirmation-content">
            <div class="confirmation-message" id="confirmationMessage">Are you sure you want to log out?</div>
            <div class="confirmation-buttons">
                <button class="confirmation-btn cancel-btn" id="cancelBtn">Cancel</button>
                <button class="confirmation-btn confirm-btn" id="confirmBtn">Log Out</button>
            </div>
        </div>
    </div>
    
</body>
<script src="../admin-assets/js/script.js"></script>
</html>
