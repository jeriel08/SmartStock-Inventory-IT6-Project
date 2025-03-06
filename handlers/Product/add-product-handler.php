<?php
session_start();
include '../../database/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = trim($_POST["productName"]);
    $categoryId = $_POST["categoryId"];
    $createdBy = $_SESSION["user_id"]; // Assuming user session is active

    try {
        $stmt = $conn->prepare("
            INSERT INTO products (Name, CategoryID, StockQuantity, Price, Status, Created_At, Created_By)
            VALUES (?, ?, 0, 0.00, 'Out of Stock', NOW(), ?)
        ");
        $stmt->bind_param("sii", $productName, $categoryId, $createdBy);
        if ($stmt->execute()) {
            $_SESSION["product_success"] = "Product added successfully!";
        } else {
            $_SESSION["product_error"] = "Error adding product.";
        }
        $stmt->close();
    } catch (Exception $e) {
        $_SESSION["product_error"] = "Error: " . $e->getMessage();
    }

    header("Location: ../../views/products.php");
    exit();
}
