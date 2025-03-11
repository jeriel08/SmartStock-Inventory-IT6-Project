<?php
session_start();
include '../../database/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = filter_input(INPUT_POST, 'productId', FILTER_VALIDATE_INT);
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $status = $_POST['status'];
    $unitId = filter_input(INPUT_POST, 'unit', FILTER_VALIDATE_INT);
    $updatedBy = $_SESSION['user_id'];
    $filter = $_GET['filter'] ?? 'In Stock';

    if (!$productId || !$price || $price < 0 || !$unitId) {
        $_SESSION['product_error'] = "Invalid input.";
        header('Location: ../../views/products.php');
        exit();
    }

    $validStatuses = ['In Stock', 'Out of Stock'];
    if (!in_array($status, $validStatuses)) {
        $_SESSION['product_error'] = "Invalid status value.";
        header('Location: ../../views/products.php');
        exit();
    }

    // Validate UnitID
    $stmt = $conn->prepare("SELECT COUNT(*) FROM units WHERE UnitID = ?");
    $stmt->bind_param("i", $unitId);
    $stmt->execute();
    $stmt->bind_result($unitExists);
    $stmt->fetch();
    $stmt->close();

    if ($unitExists == 0) {
        $_SESSION['product_error'] = "Invalid unit.";
        header('Location: ../../views/products.php');
        exit();
    }

    // Get the latest UnitCost
    $stmt = $conn->prepare("SELECT UnitCost FROM receiving_details WHERE ProductID = ? ORDER BY ReceivingID DESC LIMIT 1");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $unitCost = $result->fetch_assoc()['UnitCost'] ?? 0;
    $stmt->close();

    if ($price < $unitCost) {
        $_SESSION['product_error'] = "Error: Price cannot be lower than UnitCost.";
        header('Location: ../../views/products.php?filter=' . urlencode($filter));
        exit();
    }

    // Update the product
    $stmt = $conn->prepare("UPDATE products SET Price = ?, Status = ?, UnitID = ?, Updated_By = ?, Updated_At = NOW() WHERE ProductID = ?");
    $stmt->bind_param("dsiii", $price, $status, $unitId, $updatedBy, $productId);

    if ($stmt->execute()) {
        $_SESSION['product_success'] = "Product updated successfully.";
    } else {
        $_SESSION['product_error'] = "Database error: " . $conn->error;
    }
    $stmt->close();

    header("Location: ../../views/products.php?filter=" . urlencode($_GET['filter'] ?? 'In Stock'));
    exit();
}
