<?php
session_start();
include '../../database/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = trim($_POST["productName"]);
    $categoryId = $_POST["categoryId"];
    $unitId = $_POST["unitId"]; // Get Unit ID from form
    $createdBy = $_SESSION["user_id"];

    try {
        $stmt = $conn->prepare("CALL AddProduct(?, ?, ?, ?)");
        $stmt->bind_param("siis", $productName, $categoryId, $unitId, $createdBy);
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
