<?php
require_once '../../database/database.php'; // Database connection
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $receivingDetailId = $_POST['receivingDetailId'];
    $quantityReturned = intval($_POST['quantity']);
    $reason = trim($_POST['reason']);
    $adminId = $_SESSION['user_id']; // Assuming an admin is logged in

    // Retrieve product ID and supplier ID
    $stmt = $conn->prepare(
        "SELECT rd.ProductID, r.SupplierID 
    FROM receiving_details rd
    JOIN receiving r ON rd.ReceivingID = r.ReceivingID
    WHERE rd.ReceivingDetailID = ?"
    );
    $stmt->bind_param("i", $receivingDetailId);
    $stmt->execute();
    $stmt->bind_result($productId, $supplierId);
    $stmt->fetch();
    $stmt->close();

    if (!$productId) {
        $_SESSION['error'] = "Invalid receiving detail!";
        header("Location: ../../views/purchases.php?status=return_failed");
        exit();
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert into returntosupplier table
        $stmt = $conn->prepare("INSERT INTO returntosupplier (SupplierID, ReturnDate, Reason, Status, Created_at, Created_by)
                                VALUES (?, NOW(), ?, 'Completed', NOW(), ?)");
        $stmt->bind_param("isi", $supplierId, $reason, $adminId);
        $stmt->execute();
        $returnSupplierId = $stmt->insert_id;
        $stmt->close();

        // Insert into returntosupplierdetails table
        $stmt = $conn->prepare("INSERT INTO returntosupplierdetails (ReturnSupplierID, ProductID, QuantityReturned)
                                VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $returnSupplierId, $productId, $quantityReturned);
        $stmt->execute();
        $stmt->close();

        // Deduct stock from products table
        $stmt = $conn->prepare("UPDATE products SET StockQuantity = StockQuantity - ? WHERE ProductID = ?");
        $stmt->bind_param("ii", $quantityReturned, $productId);
        $stmt->execute();
        $stmt->close();

        // Commit transaction
        $conn->commit();

        $_SESSION['success'] = "Product returned successfully!";
        header("Location: ../../views/purchases.php?status=return_success");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Failed to process return: " . $e->getMessage();
        header("Location: ../../views/purchases.php?status=return_failed");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request!";
    header("Location: ../../views/purchases.php?status=return_failed");
    exit();
}
