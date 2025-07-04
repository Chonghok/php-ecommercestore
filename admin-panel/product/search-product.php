<?php
include '../admin-includes/config.php';

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

$sql = "SELECT p.*, c.name AS categoryName
        FROM product p
        JOIN category c ON p.categoryID = c.categoryID
        WHERE 1=1";
$params = [];

if (!empty($search)) {
    if (ctype_digit($search)) {
        $sql .= " AND p.productID = ?";
        $params[] = $search;
    } else {
        $sql .= " AND p.name LIKE ?";
        $params[] = "%$search%";
    }
}

if (!empty($category)) {
    $sql .= " AND p.categoryID = ?";
    $params[] = $category;
}

$sql .= " ORDER BY p.productID";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

foreach ($products as $product):
?>
<tr data-productid="<?= $product['productID'] ?>">
    <td><?= $product['productID'] ?></td>
    <td><img src="../../images/<?= $product['categoryName'] ?>/<?= $product['mainImage'] ?>" alt="<?= $product['name'] ?>"></td>
    <td class="word-wrap-cell"><?= $product['name'] ?></td>
    <td><?= $product['categoryName'] ?></td>
    <td style="text-align: center;">$<?= number_format($product['price'], 2) ?></td>
    <td style="text-align: center;"><?= number_format($product['discount'], 2) ?>%</td>
    <td style="text-align: center;">
        <button type="button" class="btn-update" onclick="window.location.href='update-product.php?id=<?= $product['productID'] ?>'">Update</button>
        <button class="btn-delete" data-productid="<?= $product['productID'] ?>" data-category="<?= $product['categoryName'] ?>" data-image="<?= $product['mainImage'] ?>">Delete</button>
    </td>
</tr>
<?php endforeach; ?>