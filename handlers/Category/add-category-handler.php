<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit;
}

include '../database/database.php';

if (!isset($conn) || $conn->connect_error) {
    $_SESSION['error'] = "Database connection failed.";
    header("Location: ../views/products.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../views/products.php");
    exit;
}

$name = $_POST['categoryName'] ?? '';
$description = $_POST['categoryDescription'] ?? '';

if (empty($name)) {
    $_SESSION['error'] = "Category name is required.";
    header("Location: ../views/products.php");
    exit;
}

try {
    $stmt = $conn->prepare(
        "INSERT INTO categories (Name, Description, Created_At, Created_By, Updated_At, Updated_By) 
         VALUES (?, ?, NOW(), ?, NOW(), ?)"
    );
    $createdBy = $_SESSION['user_id'];
    $updatedBy = $_SESSION['user_id'];
    $stmt->bind_param('ssii', $name, $description, $createdBy, $updatedBy);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Category added successfully!";
    } else {
        throw new Exception("Insert failed: " . $stmt->error);
    }

    $stmt->close();
} catch (Exception $e) {
    $_SESSION['error'] = "Error adding category: " . $e->getMessage();
}

$conn->close();
header("Location: ../views/products.php");
exit;
?>