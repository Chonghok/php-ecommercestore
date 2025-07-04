<?php
session_start();
require_once '../admin-includes/config.php';
require_once '../admin-includes/admin-functions.php';

if (!isset($_SESSION['admin_logged_in'])) {
    die(json_encode(['success' => false, 'message' => 'Unauthorized access']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryID = isset($_POST['categoryID']) ? (int)$_POST['categoryID'] : 0;
    
    if ($categoryID <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid category ID']);
        exit;
    }

    try {
        // Check if there are any products in this category
        $stmt = $conn->prepare("SELECT COUNT(*) as product_count FROM product WHERE categoryID = :categoryID");
        $stmt->bindParam(':categoryID', $categoryID, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['product_count'] > 0) {
            echo json_encode([
                'success' => false, 
                'message' => 'Cannot delete category: There are ' . $result['product_count'] . ' product(s) associated with it'
            ]);
            exit;
        }

        // Get category details before deletion
        $stmt = $conn->prepare("SELECT name, categoryImage FROM category WHERE categoryID = :categoryID");
        $stmt->bindParam(':categoryID', $categoryID, PDO::PARAM_INT);
        $stmt->execute();
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Delete the category
        $stmt = $conn->prepare("DELETE FROM category WHERE categoryID = :categoryID");
        $stmt->bindParam(':categoryID', $categoryID, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            // Delete the image file if it exists
            if ($category && !empty($category['categoryImage'])) {
                $imagePath = '../../images/Categories/' . $category['categoryImage'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            // Delete the category folder if it exists and is empty
            if ($category && !empty($category['name'])) {
                $folderPath = '../../images/' . $category['name'];
                
                if (file_exists($folderPath)) {
                    // Check if folder is empty
                    if (count(scandir($folderPath)) <= 2) { // 2 for . and ..
                        rmdir($folderPath);
                    } else {
                        // Folder not empty (shouldn't happen since we checked products)
                        error_log("Category folder not empty: " . $folderPath);
                    }
                }
            }
            
            echo json_encode(['success' => true, 'message' => 'Category deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete category']);
        }
    } catch (PDOException $e) {
        // Handle foreign key constraint violation
        if ($e->getCode() == '23000') {
            echo json_encode([
                'success' => false, 
                'message' => 'Cannot delete category: It is being used by one or more products'
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>