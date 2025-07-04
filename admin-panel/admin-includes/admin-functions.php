<?php
    // Get Total Revenue
    function getTotalRevenue($conn) {
        $sql = "SELECT SUM(totalAmount) AS total FROM orders";
        $stmt = $conn->query($sql);
        return $stmt->fetch()['total'];
    }


    // Get Last Order Total Amount
    function getLastOrderTotal($conn) {
        $sql = "SELECT totalAmount FROM orders
                ORDER BY orderID DESC LIMIT 1";
        $stmt = $conn->query($sql);
        return $stmt->fetch()['totalAmount'];
    }


    // Get Order Count 
    function getOrderCount($conn) {
        $sql = "SELECT COUNT(*) AS total FROM orders";
        $stmt = $conn->query($sql);
        return $stmt->fetch()['total'];
    }


    // Get Pending Orders Count
    function getPendingOrderCount($conn) {
        $sql = "SELECT COUNT(*) AS total FROM orders
                WHERE status = 'Pending'";
        $stmt = $conn->query($sql);
        return $stmt->fetch()['total'];
    }


    // Get Product Count
    function getProductCount($conn) {
        $sql = "SELECT COUNT(*) AS total FROM product";
        $stmt = $conn->query($sql);
        return $stmt->fetch()['total'];
    }


    // Get Stock Sum
    function getStockSum($conn) {
        $sql = "SELECT SUM(stock) AS total FROM product";
        $stmt = $conn->query($sql);
        return $stmt->fetch()['total'];
    }


    // Get Low Stock Product Count
    function getLowStockCount($conn) {
        $sql = "SELECT COUNT(*) AS total FROM product
                WHERE stock < 5";
        $stmt = $conn->query($sql);
        return $stmt->fetch()['total'];
    }


    // Get Category Chart Data
    function getCategoryChartData($conn) {
        $labels = [];
        $counts = [];
        // $colors = [];

        // $predefinedColors = ['#60a5fa', '#34d399', '#fbbf24', '#a78bfa', '#fb7185', '#f472b6'];
        $sql = "SELECT c.name,  SUM(p.stock) AS total
                FROM category c
                LEFT JOIN product p ON c.categoryID = p.categoryID
                GROUP BY c.categoryID";
        $result = $conn->query($sql);
        $i = 0;
        while ($row = $result->fetch()) {
            $labels[] = $row['name'];
            $counts[] = (int)$row['total'];
            // $colors[] = $predefinedColors[$i % count($predefinedColors)];
            $i++;
        }
        $colors = generatedColor(count($labels));
        return [
            'labels' => $labels,
            'counts' => $counts,
            'colors' => $colors
        ];
    }


    // Generate Random Color for Chart
    function generatedColor($count) {
        $colors = [];
        $hueStep = 360 / $count;
        for ($i = 0; $i < $count; $i++) {
            $h = ($i * $hueStep) % 360;
            $s = 60 + rand(0, 10);
            $l = 62 + rand(0, 10);
            $colors[] = "hsl($h, $s%, $l%)";
        }
        return $colors;
    }


    // Get Top 5 Best Selling Products
    function getTopSellingProducts($conn) {
        $labels = [];
        $sales = [];
        $sql = "SELECT productName, SUM(quantity) as totalSold FROM orderdetail 
                GROUP BY productID ORDER BY totalSold DESC LIMIT 5";
        $result = $conn->query($sql);

        while ($row = $result->fetch()) {
            $labels[] = $row['productName'];
            $sales[] = (int)$row['totalSold'];
        }

        return [
            'labels' => $labels,
            'sales' => $sales
        ];
    }


    // Fetch 3 Recent Orders
    function getRecentOrders($conn) {
        $sql = "SELECT * FROM orders 
                ORDER BY orderID DESC LIMIT 3";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Fetch All Admins
    function getAdmins($conn) {
        $sql = "SELECT * FROM admin";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Fetch All Categories
    function getCategories($conn) {
        $sql = "SELECT c.*, COUNT(p.productID) AS productCount
                FROM category c
                LEFT JOIN product p ON c.categoryID = p.categoryID
                GROUP BY c.categoryID";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Fetch All Products
    function getProducts($conn) {
        $sql = "SELECT p.*, c.name AS categoryName
                FROM product p
                JOIN category c ON p.categoryID = c.categoryID
                ORDER BY p.productID";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Fetch All Users
    function getUsers($conn) {
        $sql = "SELECT * FROM user";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Fetch All Stock/Inventory
    function getProductStock($conn) {
        $sql = "SELECT p.productID, p.name, p.stock, p.mainImage, c.name AS categoryName
                FROM product p
                JOIN category c ON p.categoryID = c.categoryID
                ORDER BY p.productID";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Fetch All Orders
    function getOrders($conn) {
        $sql = "SELECT * FROM orders";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Get Admin By ID
    function getAdminByID($conn, $adminID) {
        $sql = "SELECT * FROM admin
                WHERE adminID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$adminID]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Get User By ID
    function getUserByID($conn, $userID) {
        $sql = "SELECT * FROM user
                WHERE userID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userID]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Get Category By ID
    function getCategoryByID($conn, $categoryID) {
        $sql = "SELECT * FROM category
                WHERE categoryID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$categoryID]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Create a new Category Folder
    function createCategoryFolder($categoryName) {
    $basePath = '../../images/';
    $fullPath = $basePath . $categoryName;
    
    // Create directory if it doesn't exist
    if (!file_exists($fullPath)) {
        if (!mkdir($fullPath, 0755, true)) {
            return ['success' => false, 'message' => 'Failed to create category folder'];
        }
    }
        return ['success' => true, 'path' => $fullPath];
    }


    // Get Order By ID
    function getOrderByID($conn, $orderID) {
        $sql = "SELECT * FROM orders
                WHERE orderID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$orderID]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Get Order Details By ID
    function getOrderDetailsByID($conn, $orderID) {
        $sql = "SELECT * FROM orderdetail
                WHERE orderID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$orderID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Get Product Stock By ID
    function getProductStockByID($conn, $productID) {
        $sql = "SELECT productID, name, stock
                FROM product WHERE productID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$productID]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Get Product By ID
    function getProductByID($conn, $productID) {
        $sql = "SELECT p.*, c.name as categoryName FROM product p
                JOIN category c ON p.categoryID = c.categoryID
                WHERE p.productID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$productID]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Get Product Sub Images By ID
    function getSubImages($conn, $productID) {
        $sql = "SELECT * FROM product_image
                WHERE productID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$productID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Fetch all product sub images
    function getAllSubImages($conn) {
        $sql = "SELECT * FROM product_image";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
?>