<?php
include '../admin-includes/config.php';

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

$sql = "SELECT p.productID, p.name, p.stock, p.mainImage, c.name AS categoryName
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
$stocks = $stmt->fetchAll();

foreach ($stocks as $stock):
?>
<tr>
    <td><?= $stock['productID'] ?></td>
    <td><img src="../../images/<?= $stock['categoryName'] ?>/<?= $stock['mainImage'] ?>" alt="<?= $stock['name'] ?>"></td>
    <td><?= $stock['name'] ?></td>
    <?php if ($stock['stock'] === 0 ): ?>
    <td style="text-align: center; color: red; font-weight: bold;"><?= $stock['stock'] ?></td>
    <?php else: ?>
    <td style="text-align: center;"><?= $stock['stock'] ?></td>
    <?php endif; ?>
    <td style="text-align: center;">
        <button class="btn-update" onclick="window.location.href='update-inventory.php?id=<?= $stock['productID'] ?>'">Update</button>
    </td>
</tr>
<?php endforeach; ?>