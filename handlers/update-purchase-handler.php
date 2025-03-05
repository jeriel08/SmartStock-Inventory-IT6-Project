<?php
session_start();
include '../database/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $receivingID = $_POST["receivingID"];
    $quantity = $_POST["quantity"];
    $cost = $_POST["cost"];
    $status = $_POST["status"];
    $updatedBy = $_SESSION["user_id"]; // Assuming user session

    try {
        // Update supplier order status, quantity, and cost
        $stmt = $conn->prepare("
            UPDATE receiving r
            JOIN receiving_details rd ON r.ReceivingID = rd.ReceivingID
            SET r.Status = ?, rd.Quantity = ?, rd.UnitCost = ?, r.Updated_At = NOW(), r.Updated_By = ?
            WHERE r.ReceivingID = ?
        ");
        $stmt->bind_param("siiii", $status, $quantity, $cost, $updatedBy, $receivingID);
        $stmt->execute();
        $stmt->close();

        // If status is "Received", update product stock and price
        if ($status === "Received") {
            $stmt = $conn->prepare("
                UPDATE products p
                JOIN receiving_details rd ON p.ProductID = rd.ProductID
                SET p.StockQuantity = p.StockQuantity + rd.Quantity,
                    p.Price = rd.UnitCost,
                    p.Status = 'In Stock'
                WHERE rd.ReceivingID = ?
            ");
            $stmt->bind_param("i", $receivingID);
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
