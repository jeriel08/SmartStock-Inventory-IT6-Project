<?php
session_start();
include '../../database/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php');
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
        $_SESSION['product_error'] = "Invalid status value";
        header('Location: ../../views/products.php');
        exit();
    }

    $stmt = $conn->prepare("
        UPDATE products 
        SET Price = ?, Status = ?, Updated_By = ?, Updated_At = NOW()
        WHERE ProductID = ?
    ");
    $stmt->bind_param("dsii", $price, $status, $updatedBy, $productId);

    if ($stmt->execute()) {
        $_SESSION['product_success'] = "Product updated successfully";
    } else {
        $_SESSION['product_error'] = "Failed to update product: " . $conn->error;
    }

    $stmt->close();
    // Preserve the filter in the URL
    $filter = $_GET['filter'] ?? 'In Stock'; // Keep the current filter
    header("Location: ../../views/products.php?filter=" . urlencode($filter));
    exit();
}
