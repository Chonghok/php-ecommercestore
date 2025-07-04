<?php
session_start();
require_once '../admin-includes/config.php';
require_once '../admin-includes/admin-functions.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(['success' => false, 'message' => 'Not authorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $productId = $_POST['productId'];
        $category = $_POST['category'];
        $mainImage = $_POST['image'];
        
        // Get sub images first before deleting the product
        $stmt = $conn->prepare("SELECT subImage FROM product_image WHERE productID = ?");
        $stmt->execute([$productId]);
        $subImages = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Delete the product from database
        $conn->beginTransaction();
        
        // First delete from product_image table
        $stmt = $conn->prepare("DELETE FROM product_image WHERE productID = ?");
        $stmt->execute([$productId]);
        
        // Then delete from product table
        $stmt = $conn->prepare("DELETE FROM product WHERE productID = ?");
        $stmt->execute([$productId]);
        
        $conn->commit();
        
        // Delete images from server
        $imageDir = "../../images/{$category}/";
        
        // Delete main image
        if (file_exists($imageDir . $mainImage)) {
            unlink($imageDir . $mainImage);
        }
        
        // Delete sub images
        foreach ($subImages as $subImage) {
            if (file_exists($imageDir . $subImage)) {
                unlink($imageDir . $subImage);
            }
        }
        
        echo json_encode(['success' => true, 'message' => 'Product deleted successfully']);
    } catch (PDOException $e) {
        $conn->rollBack();
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    header("HTTP/1.1 405 Method Not Allowed");
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}