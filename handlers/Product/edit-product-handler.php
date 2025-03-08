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

    // Fetch the latest UnitCost
    $query = "SELECT UnitCost FROM receiving_details WHERE ProductID = ? ORDER BY ReceivingID DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $unitCost = $row['UnitCost'] ?? 0; // Default to 0 if no entry exists
    $stmt->close();

    // Check price validity before updating
    if ($price < $unitCost) {
        $_SESSION['product_error'] = "Error: Price cannot be lower than UnitCost.";
    } else {
        // Proceed with update
        $stmt = $conn->prepare("UPDATE products SET Price = ?, Status = ?, Updated_By = ?, Updated_At = NOW() WHERE ProductID = ?");
        $stmt->bind_param("dsii", $price, $status, $updatedBy, $productId);

        if ($stmt->execute()) {
            $_SESSION['product_success'] = "Product updated successfully";
        } else {
            $_SESSION['product_error'] = "Failed to update product: " . $conn->error;
        }
        $stmt->close();
    }

    header("Location: ../../views/products.php?filter=" . urlencode($_GET['filter'] ?? 'In Stock'));
    exit();
}
