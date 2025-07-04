<?php
    require_once '../includes/config.php';
    require_once '../includes/product-functions.php';

    $query = $_GET['query'] ?? '';

    if(!empty($query)) {
        $products = searchProducts($conn, $query);
        $results = [];

        foreach ($products as $product) {
            $price = floatval($product['price']);
            $discount = floatval($product['discount']);
            $discountedPrice = $discount > 0 ? $price * (1 - $discount / 100) : null;

            $results[] = [
                'productID' => $product['productID'],
                'name' => $product['name'],
                'categoryName' => $product['categoryName'],
                'mainImage' => $product['mainImage'],
                'stock' => $product['stock'],
                'price' => number_format($price, 2),
                'discountedPrice' => number_format($discountedPrice, 2)
            ];
        }
        echo json_encode($results);
    }
    else {
        echo json_encode([]);
    }
?>