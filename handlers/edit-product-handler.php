<?php
session_start();
include '../database/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['productId'];
    $price = $_POST['price'];
    $stockQuantity = $_POST['stockQuantity']; // Added StockQuantity
    $status = $_POST['status']; // Expects 'In Stock' or 'Out of Stock'
    $updatedBy = $_SESSION['user_id'];

    // Log incoming data for debugging
    error_log("Edit Product: productId=$productId, price=$price, stockQuantity=$stockQuantity, status=$status, updatedBy=$updatedBy");

    $stmt = $conn->prepare("UPDATE products SET Price = ?, StockQuantity = ?, Status = ?, Updated_At = NOW(), Updated_By = ? WHERE ProductID = ?");
    if ($stmt === false) {
        error_log("Prepare failed: " . $conn->error);
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("dissi", $price, $stockQuantity, $status, $updatedBy, $productId);
    if ($stmt->execute()) {
        error_log("Update successful for productId=$productId");
        header('Location: ../views/products.php');
    } else {
        error_log("Update failed: " . $stmt->error);
        header('Location: ../views/products.php');
    }
    $stmt->close();
} else {
    header('Location: ../views/products.php');
}

$conn->close();
exit();
?>