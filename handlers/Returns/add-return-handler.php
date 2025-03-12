<?php
session_start();
include '../../database/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php');
    exit();
}

$customerId = $_POST['customerId'];
$orderId = $_POST['orderId'];
$returnDate = $_POST['returnDate'];
$products = $_POST['products'];
$createdBy = $_SESSION['user_id'];

$conn->begin_transaction();

try {
    // Insert into returns table (no Reason) - unchanged
    $stmt = $conn->prepare("INSERT INTO returns (CustomerID, OrderID, ReturnDate, Created_by) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Prepare failed for returns insert: " . $conn->error);
    }
    $stmt->bind_param("iisi", $customerId, $orderId, $returnDate, $createdBy);
    $stmt->execute();
    $returnId = $conn->insert_id;

    // Insert into return_details with Reason - unchanged
    $detailStmt = $conn->prepare("INSERT INTO return_details (ReturnID, ProductID, QuantityReturned, Reason) VALUES (?, ?, ?, ?)");
    if (!$detailStmt) {
        throw new Exception("Prepare failed for return_details insert: " . $conn->error);
    }

    // Update StockQuantity instead of stock - unchanged
    $stockStmt = $conn->prepare("UPDATE products SET StockQuantity = StockQuantity + ? WHERE ProductID = ?");
    if (!$stockStmt) {
        throw new Exception("Prepare failed for stock update: " . $conn->error);
    }

    foreach ($products as $product) {
        $quantity = (int)$product['quantity'];
        if ($quantity > 0) {
            $productId = $product['productId'];
            $reason = $product['reason'];
            $detailStmt->bind_param("iiis", $returnId, $productId, $quantity, $reason);
            $detailStmt->execute();

            // Update stock if reason is "Wrong Item" - unchanged
            if ($reason === "Wrong Item") {
                $stockStmt->bind_param("ii", $quantity, $productId);
                $stockStmt->execute();
            }
            // Removed: No "Damaged/Expired" logic involving Active or DamagedQuantity
        }
    }

    $conn->commit();
    $_SESSION['return_success'] = "Return added successfully.";
} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['return_error'] = "Error adding return: " . $e->getMessage();
}

if (isset($stmt) && $stmt !== false) $stmt->close();
if (isset($detailStmt) && $detailStmt !== false) $detailStmt->close();
if (isset($stockStmt) && $stockStmt !== false) $stockStmt->close();
$conn->close();

header('Location: ../../views/returns.php');
exit();
