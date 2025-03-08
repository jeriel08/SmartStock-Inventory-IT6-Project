<?php
session_start();
include '../../database/database.php';

// Debug: Check if POST data is received correctly
error_log("ProductID: " . $_POST["productId"]);
error_log("Reason: " . $_POST["reason"]);
error_log("Quantity: " . $_POST["quantity"]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = intval($_POST["productId"]);
    $reason = $_POST["reason"];
    $quantity = $_POST["quantity"];
    $adminId = $_SESSION['user_id'];

    // Insert into Adjustments table
    $sql = "INSERT INTO Adjustments (AdminID, Reason, AdjustmentDate, Created_at, Created_by)
            VALUES (?, ?, NOW(), NOW(), ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $adminId, $reason, $adminId);
    $stmt->execute();
    $adjustmentId = $stmt->insert_id;
    $stmt->close();

    // Insert into Adjustment_details table
    $sql = "INSERT INTO Adjustment_details (ProductID, AdjustmentID, AdjustmentType, QuantityAdjusted)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisi", $productId, $adjustmentId, $reason, $quantity);
    $stmt->execute();
    $stmt->close();

    // Reduce stock quantity in Products table
    $sql = "UPDATE Products SET StockQuantity = StockQuantity - ? WHERE ProductID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $quantity, $productId);
    $stmt->execute();
    $stmt->close();

    $_SESSION["product_success"] = "Product discard successful!";
    header("Location: ../../views/products.php");
    exit();
}
