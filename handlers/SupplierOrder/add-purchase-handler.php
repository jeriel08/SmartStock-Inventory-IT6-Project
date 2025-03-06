<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit;
}

include '../../database/database.php';

if (!isset($conn) || $conn->connect_error) {
    $_SESSION['error'] = "Database connection failed.";
    header("Location: ../../views/purchases/add-purchases.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: ../../views/purchases/add-purchases.php");
    exit;
}

// Get and validate header fields
$supplierId = $_POST['supplierId'] ?? '';
$date = $_POST['date'] ?? '';
$status = $_POST['status'] ?? '';
$products = $_POST['products'] ?? [];

if (empty($supplierId) || empty($date) || empty($status) || empty($products) || !is_array($products)) {
    $_SESSION['error'] = "All fields are required, including at least one product.";
    header("Location: ../../views/purchases/add-purchases.php");
    exit;
}

// Validate status
$validStatuses = ['Pending', 'Received', 'Cancelled'];
if (!in_array($status, $validStatuses)) {
    $_SESSION['error'] = "Invalid status value.";
    header("Location: ../../views/purchases/add-purchases.php");
    exit;
}

// Start transaction
$conn->begin_transaction();

try {
    // Insert into Receiving with explicit timestamps
    $stmt = $conn->prepare(
        "INSERT INTO Receiving (SupplierID, Date, Status, Created_At, Created_By, Updated_At, Updated_By) 
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    if ($stmt === false) {
        throw new Exception("Prepare failed for Receiving: " . $conn->error);
    }

    $createdBy = $_SESSION['user_id'];
    $updatedBy = $_SESSION['user_id'];
    $createdAt = date('Y-m-d H:i:s'); // Current timestamp
    $updatedAt = $createdAt;
    $stmt->bind_param('issssii', $supplierId, $date, $status, $createdAt, $createdBy, $updatedAt, $updatedBy);
    if (!$stmt->execute()) {
        throw new Exception("Insert failed for Receiving: " . $stmt->error);
    }
    $receivingId = $conn->insert_id;
    $stmt->close();

    // Insert into receiving_details
    $stmt = $conn->prepare(
        "INSERT INTO receiving_details (ReceivingID, ProductID, Quantity, UnitCost) 
         VALUES (?, ?, ?, ?)"
    );
    if ($stmt === false) {
        throw new Exception("Prepare failed for receiving_details: " . $conn->error);
    }

    foreach ($products as $index => $product) {
        $productId = $product['productId'] ?? '';
        $quantity = $product['quantity'] ?? '';
        $cost = $product['cost'] ?? '';

        if (empty($productId) || empty($quantity) || $cost === '' || $quantity < 1 || $cost < 0) {
            throw new Exception("Invalid product data at row " . ($index + 1) . ".");
        }

        $stmt->bind_param('iiid', $receivingId, $productId, $quantity, $cost);
        if (!$stmt->execute()) {
            throw new Exception("Insert failed for ReceivingDetails at row " . ($index + 1) . ": " . $stmt->error);
        }
    }
    $stmt->close();

    // Update Products stock, price, supplier, and status if Status is 'Received'
    if ($status === 'Received') {
        $stmt = $conn->prepare("
        UPDATE Products p
        JOIN receiving_details rd ON p.ProductID = rd.ProductID
        JOIN Receiving r ON rd.ReceivingID = r.ReceivingID
        SET p.StockQuantity = p.StockQuantity + ?,
            p.Price = ?,
            p.SupplierID = r.SupplierID,
            p.Status = 'In Stock'
        WHERE p.ProductID = ?
    ");

        if ($stmt === false) {
            throw new Exception("Prepare failed for Products update: " . $conn->error);
        }

        foreach ($products as $index => $product) {
            $productId = $product['productId'];
            $quantity = $product['quantity'];
            $cost = $product['cost']; // Ensure this value is passed correctly in the request

            $stmt->bind_param('idi', $quantity, $cost, $productId);
            if (!$stmt->execute()) {
                throw new Exception("Stock update failed for ProductID " . $productId . ": " . $stmt->error);
            }
        }

        $stmt->close();
    }


    // Commit transaction
    $conn->commit();
    $_SESSION['success'] = "Purchase added successfully!";
} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error'] = "Error adding purchase: " . $e->getMessage();
}

$conn->close();
header("Location: ../../views/purchases.php");
exit;
