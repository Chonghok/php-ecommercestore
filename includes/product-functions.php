<?php
    // Fetch Products with Pagination
    function getProducts($conn, $page = 1, $perPage = 20) {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT p.*, c.name AS categoryName 
                FROM product p
                JOIN category c ON p.categoryID = c.categoryID
                ORDER BY p.productID
                LIMIT :perPage OFFSET :offset";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Count total products
    function countProducts($conn) {
        $sql = "SELECT COUNT(*) AS total FROM product";
        $stmt = $conn->query($sql);
        return $stmt->fetch()['total'];
    }



    

    // Fetch All Categories
    function getAllCategories($conn){
        $sql = "SELECT * FROM category";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Fetch Featured Products by IDs
    function getFeaturedProducts($conn, $ids){
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT p.*, c.name AS categoryName FROM product p 
                JOIN category c ON p.categoryID = c.categoryID
                WHERE productID IN ($placeholders)
                ORDER BY FIELD(productID, $placeholders)";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array_merge($ids, $ids));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Fetch New Arrivals
    function getNewArrivals($conn){
        $sql = "SELECT p.*, c.name AS categoryName FROM product p 
                JOIN category c ON p.categoryID = c.categoryID
                ORDER BY productID DESC LIMIT 8";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Fetch Products by Category
    function getProductsByCategory($conn, $categoryID, $page = 1, $perPage = 20) {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT p.*, c.name AS categoryName 
                FROM product p
                JOIN category c ON p.categoryID = c.categoryID
                WHERE p.categoryID = :categoryID
                LIMIT :perPage OFFSET :offset";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':categoryID', $categoryID, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Count total product by category
    function countProductsByCategory($conn, $categoryID) {
        $sql = "SELECT COUNT(*) AS total FROM product WHERE categoryID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$categoryID]);
        return $stmt->fetch()['total'];
    }


    // Get Category by categoryID
    function getCategoryInfo($conn, $categoryID) {
        $sql = "SELECT * FROM category WHERE categoryID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$categoryID]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Fetch Products on Sale
    function getPromotionsProducts($conn, $page = 1, $perPage = 20) {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT p.*, c.name AS categoryName 
                FROM product p
                JOIN category c ON p.categoryID = c.categoryID
                WHERE p.discount > 0
                ORDER BY p.productID
                LIMIT :perPage OFFSET :offset";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Count total product in promotion
    function countDiscountedProducts($conn) {
        $sql = "SELECT COUNT(*) AS total FROM product WHERE discount > 0";
        $stmt = $conn->query($sql);
        return $stmt->fetch()['total'];
    }


    // Fetch Product Details by ID
    function getProductDetails($conn, $productID){
        $sql = "SELECT p.*, c.name AS categoryName FROM product p
                JOIN category c ON p.categoryID = c.categoryID
                WHERE p.productID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$productID]);
        $products = $stmt->fetch(PDO::FETCH_ASSOC);
        $sqlImg = "SELECT * FROM product_image WHERE productID = ?";
        $stmtImg = $conn->prepare($sqlImg);
        $stmtImg->execute([$productID]);
        $images = $stmtImg->fetchAll(PDO::FETCH_ASSOC);

        return [
            'product' => $products,
            'images' => $images
        ];
    }


    // Split Product Details
    function splitProductDetails($productDetail){
        $normalized = preg_replace('/\s*[\|\\\\]\s*/', '|', $productDetail);
        $parts = explode('|', $normalized);
        $parts = array_map('trim', $parts);
        return $parts;
    }


    // Fetch Related Products
    function getRelatedProducts($conn, $productID){
        // $relatedCategories = [
        //     1 => [1, 2, 3, 4],     // Keyboard → Keycaps, Switches, Mouse
        //     2 => [2, 1, 3],        // Keycaps → Keyboard, Switches
        //     3 => [3, 1, 2],        // Switches → Keyboard, Keycaps
        //     4 => [4, 5, 1],        // Mouse → Mousepad, Keyboard
        //     5 => [5, 4, 8],        // Mousepad → Mouse, Accessories
        //     6 => [6, 7, 8],        // Controller → Headset, Accessories
        //     7 => [7, 6, 8],        // Headset → Controller, Accessories
        //     8 => [8, 1, 4, 6, 7],  // Accessories → everything
        // ];

        // $categoriesToSearch = $relatedCategories[$categoryID] ?? [$categoryID];
        // $placeholders = implode(',', array_fill(0, count($categoriesToSearch), '?'));

        // $sql = "SELECT p.*, c.name AS categoryName FROM product p
        //         JOIN category c ON p.categoryID = c.categoryID
        //         WHERE p.categoryID IN ($placeholders) AND p.productID != ?
        //         ORDER BY RAND() LIMIT 4";`
        $sql = "SELECT p.*, c.name AS categoryName FROM product p
                JOIN category c ON p.categoryID = c.categoryID
                WHERE p.productID != ?
                ORDER BY RAND() LIMIT 4";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$productID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Fetch Products by Search Query
    function searchProducts($conn, $query){
        $likeQuery = "%". $query. "%";
        $sql = "SELECT p.*, c.name AS categoryName FROM product p
                JOIN category c ON p.categoryID = c.categoryID
                WHERE p.name LIKE ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$likeQuery]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Fetch Products by Search Query with Pagination
    function getSearchProducts($conn, $query, $page = 1, $perPage = 20){
        $offset = ($page - 1) * $perPage;
        $likeQuery = "%". $query. "%";
        
        $sql = "SELECT p.*, c.name AS categoryName 
                FROM product p
                JOIN category c ON p.categoryID = c.categoryID
                WHERE p.name LIKE ?
                LIMIT ? OFFSET ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $likeQuery, PDO::PARAM_STR);
        $stmt->bindValue(2, $perPage, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Count Search Result
    function countSearchResults($conn, $query) {
        $likeQuery = "%". $query. "%";
        $sql = "SELECT COUNT(*) AS total 
                FROM product p
                WHERE p.name LIKE ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$likeQuery]);
        return $stmt->fetch()['total'];
    }


    // Get Wishlist Count
    function getWishlistCount($conn, $userID){
        $stmt = $conn->prepare("SELECT COUNT(*) FROM wishlist WHERE userID = ?");
        $stmt->execute([$userID]);
        return $stmt->fetchColumn();
    }


    // Get Cart Count
    function getCartCount($conn, $userID){
        $stmt = $conn->prepare("SELECT COUNT(*) FROM cart WHERE userID = ?");
        $stmt->execute([$userID]);
        return $stmt->fetchColumn() ?? 0;
    }


    // Fetch Wishlist Product by UserID
    function getWishlistProducts($conn, $userID){
        $sql = "SELECT p.*, c.name AS categoryName
                FROM wishlist w
                JOIN product p ON w.productID = p.productID
                JOIN category c ON p.categoryID = c.categoryID
                WHERE w.userID = ?
                ORDER BY w.wishlistID DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Check Product in Cart
    function checkIfInCart($conn, $userID, $productID) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM cart WHERE userID = ? AND productID = ?");
        $stmt->execute([$userID, $productID]);
        return $stmt->fetchColumn() > 0;
    }


    // Fetch Products to Cart by UserID
    function getCartProducts($conn, $userID){
        $sql = "SELECT p.*, cat.name AS categoryName, c.quantity
                FROM cart c
                JOIN product p ON c.productID = p.productID
                JOIN category cat ON p.categoryID = cat.categoryID
                WHERE c.userID = ?
                ORDER BY c.cartID DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Fetch Order Info for Success Order Page
    function getOrderInfo($conn, $orderID) {
        $sql = "SELECT * FROM orders
                WHERE orderID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$orderID]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Fetch Order Details for Success Order Page
    function getOrderDetails($conn, $orderID) {
        $sql = "SELECT * FROM orderdetail
                WHERE orderID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$orderID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Fetch Order History by UserID
    function getUserOrderHistory($conn, $userID) {
        $sql = "SELECT * FROM orders
                WHERE userID = ?
                ORDER BY orderDate DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
?>