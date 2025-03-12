<?php
session_start();
include '../../database/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $receivingId = (int)$_POST['receivingId'];
    $supplierId = (int)$_POST['supplierId'];
    $date = $_POST['date'];
    $status = $_POST['status'];
    $products = $_POST['products'];
    $updatedBy = $_SESSION['user_id'];

    $conn->begin_transaction();
    try {
        // Update receiving table
        $stmt = $conn->prepare("
            UPDATE receiving 
            SET SupplierID = ?, Date = ?, Status = ?, Updated_At = NOW(), Updated_By = ?
            WHERE ReceivingID = ?
        ");
        $stmt->bind_param("issii", $supplierId, $date, $status, $updatedBy, $receivingId);
        $stmt->execute();
        $stmt->close();

        // Delete existing receiving_details for this ReceivingID
        $stmt = $conn->prepare("DELETE FROM receiving_details WHERE ReceivingID = ?");
        $stmt->bind_param("i", $receivingId);
        $stmt->execute();
        $stmt->close();

        // Insert updated products into receiving_details
        foreach ($products as $product) {
            $productId = (int)$product['productId'];
            $quantity = (int)$product['quantity'];
            $cost = floatval($product['cost']);
            $sellingPrice = floatval($product['sellingPrice']);

            $stmt = $conn->prepare("
                INSERT INTO receiving_details (ReceivingID, ProductID, Quantity, UnitCost)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->bind_param("iiid", $receivingId, $productId, $quantity, $cost);
            $stmt->execute();
            $stmt->close();

            // If status is "Received", update products table
            if ($status === "Received") {
                $stmt = $conn->prepare("
                    UPDATE products 
                    SET StockQuantity = StockQuantity + ?, Price = ?, Status = 'In Stock', SupplierID = ?
                    WHERE ProductID = ?
                ");
                $stmt->bind_param("idis", $quantity, $sellingPrice, $supplierId, $productId);
                $stmt->execute();
                $stmt->close();
            }
        }

        $conn->commit();
        $_SESSION["supplierorder_success"] = "Purchase updated successfully!";
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION["supplierorder_error"] = "Error: " . $e->getMessage();
    }

    header("Location: ../../views/purchases.php");
    exit();
}
