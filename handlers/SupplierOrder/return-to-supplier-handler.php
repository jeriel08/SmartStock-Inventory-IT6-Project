<?php
include '../../database/database.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $receivingId = (int)$_POST['receivingId'];
    $supplierId = (int)$_POST['supplierId'];
    $returnDate = $_POST['returnDate'];
    $products = $_POST['products'];
    $adminId = $_SESSION['user_id'];

    if (empty($receivingId) || empty($supplierId) || empty($returnDate) || empty($products)) {
        $_SESSION['supplierorder_error'] = "Missing required fields!";
        header("Location: ../../views/purchases.php");
        exit();
    }

    $conn->begin_transaction();

    try {
        // Insert into returntosupplier (no Reason)
        $stmt = $conn->prepare("
            INSERT INTO returntosupplier (SupplierID, ReturnDate, Status, Created_at, Created_by)
            VALUES (?, ?, 'Completed', NOW(), ?)
        ");
        $stmt->bind_param("isi", $supplierId, $returnDate, $adminId);
        $stmt->execute();
        $returnSupplierId = $stmt->insert_id;
        $stmt->close();

        // Process each product
        foreach ($products as $product) {
            $productId = (int)$product['productId'];
            $quantityReturned = (int)$product['quantityReturned'];
            $reason = trim($product['reason']);

            if ($quantityReturned <= 0) {
                continue;
            }

            $stmt = $conn->prepare("
                SELECT Quantity 
                FROM receiving_details 
                WHERE ReceivingID = ? AND ProductID = ?
            ");
            $stmt->bind_param("ii", $receivingId, $productId);
            $stmt->execute();
            $stmt->bind_result($quantityReceived);
            $stmt->fetch();
            $stmt->close();

            if ($quantityReturned > $quantityReceived) {
                throw new Exception("Quantity to return ($quantityReturned) exceeds received quantity ($quantityReceived) for product ID $productId!");
            }

            // Insert into returntosupplierdetails (with Reason)
            $stmt = $conn->prepare("
                INSERT INTO returntosupplierdetails (ReturnSupplierID, ProductID, QuantityReturned, Reason)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->bind_param("iiis", $returnSupplierId, $productId, $quantityReturned, $reason);
            $stmt->execute();
            $stmt->close();

            // Deduct stock from products table
            $stmt = $conn->prepare("
                UPDATE products 
                SET StockQuantity = StockQuantity - ? 
                WHERE ProductID = ?
            ");
            $stmt->bind_param("ii", $quantityReturned, $productId);
            $stmt->execute();
            $stmt->close();

            // Deduct quantity from receiving_details table
            $stmt = $conn->prepare("
                UPDATE receiving_details 
                SET Quantity = Quantity - ? 
                WHERE ReceivingID = ? AND ProductID = ?
            ");
            $stmt->bind_param("iii", $quantityReturned, $receivingId, $productId);
            $stmt->execute();
            $stmt->close();
        }

        $conn->commit();
        $_SESSION['supplierorder_success'] = "Products returned successfully!";
        header("Location: ../../views/purchases.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['supplierorder_error'] = "Failed to process return: " . $e->getMessage();
        header("Location: ../../views/purchases.php");
        exit();
    }
} else {
    $_SESSION['supplierorder_error'] = "Invalid request!";
    header("Location: ../../views/purchases.php");
    exit();
}
