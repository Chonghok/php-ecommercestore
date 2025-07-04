<?php
$servername = "localhost";
$user = "root";
$password = "";
$dbname = "ecommercedb";
$port = 3307;
//Change port to your port number if you are using a different one.

try{
    $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}
?>