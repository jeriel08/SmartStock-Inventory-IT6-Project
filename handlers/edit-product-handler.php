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
    $status = $_POST['status'];
    $updatedBy = $_SESSION['user_id'];

    // Validate status against allowed enum values
    $validStatuses = ['In Stock', 'Out of Stock'];
    if (!in_array($status, $validStatuses)) {
        $_SESSION['error'] = "Invalid status value";
        header('Location: ../products.php');
        exit();
    }

    $stmt = $conn->prepare("
        UPDATE products 
        SET Price = ?, Status = ?, Updated_By = ?, Updated_At = NOW()
        WHERE ProductID = ?
    ");
    $stmt->bind_param("dsii", $price, $status, $updatedBy, $productId);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Product updated successfully";
    } else {
        $_SESSION['error'] = "Failed to update product: " . $conn->error;
    }

    $stmt->close();
    header('Location: ../views/products.php');
    exit();
}
?>