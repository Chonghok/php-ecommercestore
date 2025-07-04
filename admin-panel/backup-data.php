<?php
require_once 'admin-includes/config.php';
require_once 'admin-includes/admin-functions.php';

$tables = [
        "admin",
        "cart",
        "category",
        "orderdetail",
        "orders",
        "product",
        "product_image",
        "user",
        "wishlist"
    ];

$filename = '../backup-data.txt';
$isNewFile = !file_exists($filename);
$file = fopen($filename, "w");

date_default_timezone_set('Asia/Bangkok');
fwrite($file, "\n========== FULL BACKUP: " . date('F j, Y \a\t g:i A') . " ==========\n");

foreach ($tables as $table) {
    fwrite($file, "\n--- Table: $table ---\n");

    $stmt = $conn->query("SELECT * FROM $table");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($rows) > 0) {
        // Write column headers once per table
        $headers = implode(" | ", array_keys($rows[0]));
        fwrite($file, $headers . PHP_EOL);
        fwrite($file, str_repeat("-", strlen($headers)) . PHP_EOL);

        // Write data rows
        foreach ($rows as $row) {
            $line = implode(" | ", $row);
            fwrite($file, $line . PHP_EOL);
            }
    } else {
        fwrite($file, "[ No data found in this table ]\n");
    }
}

fclose($file);

session_start();
$_SESSION['backup_success'] = true;
header("Location: index.php");
exit;
?>