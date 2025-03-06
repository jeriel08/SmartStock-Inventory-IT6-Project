<?php
session_start();
include '../database/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $receivingDetailID = $_POST["receivingDetailID"]; // Changed from receivingID
    $quantity = $_POST["quantity"];
    $cost = $_POST["cost"];
    $status = $_POST["status"];
    $updatedBy = $_SESSION["user_id"]; // Assuming user session

    try {
        // Update supplier order status, quantity, and cost
        $stmt = $conn->prepare("
            UPDATE receiving_details rd
            JOIN receiving r ON rd.ReceivingID = r.ReceivingID
            SET 
                rd.Quantity = ?, 
                rd.UnitCost = ?, 
                r.Status = ?, 
                r.Updated_At = NOW(), 
                r.Updated_By = ?
            WHERE rd.ReceivingDetailID = ?
        ");
        $stmt->bind_param("iisii", $quantity, $cost, $status, $updatedBy, $receivingDetailID);
        $stmt->execute();
        $stmt->close();

        // If status is "Received", update product stock, price, status, and supplier
        if ($status === "Received") {
            $stmt = $conn->prepare("
                UPDATE products p
                JOIN receiving_details rd ON p.ProductID = rd.ProductID
                SET 
                    p.StockQuantity = p.StockQuantity + rd.Quantity,
                    p.Price = rd.UnitCost,
                    p.Status = 'In Stock'
                WHERE rd.ReceivingDetailID = ?
            ");
            $stmt->bind_param("i", $receivingDetailID);
            $stmt->execute();
            $stmt->close();
        }

        $_SESSION["success_message"] = "Purchase updated successfully!";
    } catch (Exception $e) {
        $_SESSION["error_message"] = "Error: " . $e->getMessage();
    }

    header("Location: ../views/purchases.php");
    exit();
}
