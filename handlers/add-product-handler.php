<?php
session_start();
include '../database/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = $_POST['productName'];
    $supplierId = $_POST['supplierId'];
    $categoryId = $_POST['categoryId'];
    $quantity = $_POST['quantity'];
    $cost = $_POST['cost']; // Not stored directly in products table
    $price = $_POST['price'];
    $createdBy = $_SESSION['user_id'];
    $status = 'In Stock'; // Explicitly set default status

    $stmt = $conn->prepare("
        INSERT INTO products (Name, CategoryID, Price, StockQuantity, SupplierID, Status, Created_At, Created_By, Updated_At, Updated_By) 
        VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, NOW(), ?)
    ");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Corrected bind_param with 8 parameters
    $stmt->bind_param("sidiisii", $productName, $categoryId, $price, $quantity, $supplierId, $status, $createdBy, $createdBy);

    if ($stmt->execute()) {
        header('Location: ../views/products.php');
    } else {
        header('Location: ../views/products.php');
    }
    $stmt->close();
} else {
    header('Location: ../views/products.php');
}

$conn->close();
exit();
?>