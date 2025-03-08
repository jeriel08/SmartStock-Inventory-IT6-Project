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

        $stmt = $conn->prepare("CALL DiscardStock(?, ?, ?, ?)");
        $stmt->bind_param("iisi", $adminId, $productId, $reason, $quantity);
        $stmt->execute();
        $stmt->close();


        $_SESSION["product_success"] = "Product discard successful!";
        header("Location: ../../views/products.php");
        exit();
}
