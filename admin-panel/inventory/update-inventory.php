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

$productID = $_GET['id'];
$stock = getProductStockByID($conn, $productID);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newStock = isset($_POST['stockQty']) ? (int)$_POST['stockQty'] : 0;
    
    if ($newStock < 0) {
        $error = 'Stock quantity cannot be negative';
    } else {
        try {
            $stmt = $conn->prepare("UPDATE product SET stock = :stock WHERE productID = :productID");
            $stmt->bindParam(':stock', $newStock, PDO::PARAM_INT);
            $stmt->bindParam(':productID', $productID, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                $success = 'Stock quantity updated successfully!';
                // Refresh stock data
                $stock = getProductStockByID($conn, $productID);
            } else {
                $error = 'Failed to update stock quantity';
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
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
    <link rel="stylesheet" href="../admin-assets/css/inventory.css">
</head>
<body>

    <?php require_once '../admin-includes/header.php' ?>

    <?php require_once '../admin-includes/sidebar.php' ?>    

    <main class="inventory-update-main">
        <div class="inventory-update-card">
            <h2>Update Inventory</h2>

            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form action="" method="POST" id="inventoryForm">
                <div class="form-group">
                    <label for="productId">Product ID</label>
                    <input type="text" id="productId" value="<?= htmlspecialchars($productID) ?>" readonly />
                </div>
                <div class="form-group">
                    <label for="productName">Product Name</label>
                    <input type="text" id="productName" value="<?= htmlspecialchars($stock['name']) ?>" readonly />
                </div>
                <div class="form-group">
                    <label for="stockQty">Stock Quantity</label>
                    <div class="stock-adjust">
                        <button type="button" onclick="changeQty(-1)">-</button>
                        <input type="number" id="stockQty" name="stockQty" value="<?= htmlspecialchars($stock['stock']) ?>" min="0" />
                        <button type="button" onclick="changeQty(1)">+</button>
                    </div>
                </div>

                <button type="button" class="btn-back" onclick="window.location.href='index.php'">Back</button>
                <button type="submit" class="btn-submit">Update Stock</button>
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
<script>
    function changeQty(change) {
        const input = document.getElementById("stockQty");
        let value = parseInt(input.value) || 0;
        value += change;
        if (value < 0) value = 0;
        input.value = value;
    }
    document.getElementById("inventoryForm").addEventListener("submit", function(e) {
        const qtyInput = document.getElementById("stockQty");
        if (parseInt(qtyInput.value) < 0) {
            e.preventDefault();
            qtyInput.value = 0;
            alert("Stock quantity cannot be negative");
        }
    });
</script>
</html>
