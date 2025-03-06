<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

include '../database/database.php';

if (!isset($conn) || $conn->connect_error) {
    $_SESSION['error'] = "Database connection failed.";
    header("Location: ../views/products/categories.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: ../views/products/categories.php");
    exit;
}

// Get form data
$categoryId = $_POST['categoryID'] ?? '';

if (empty($categoryId)) {
    $_SESSION['error'] = "No category ID provided.";
    header("Location: ../views/products/categories.php");
    exit;
}

// Check if category has associated products (optional safety check)
$stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE CategoryID = ?");
if ($stmt === false) {
    $_SESSION['error'] = "Error checking associated products.";
    header("Location: ../views/products/categories.php");
    exit;
}

$stmt->bind_param('i', $categoryId);
$stmt->execute();
$result = $stmt->get_result();
$count = $result->fetch_array()[0];
$stmt->close();

if ($count > 0) {
    $_SESSION['error'] = "Cannot delete category with associated products. Please reassign or delete the products first.";
    header("Location: ../views/products/categories.php");
    exit;
}

// Delete the category
try {
    $stmt = $conn->prepare("DELETE FROM categories WHERE CategoryID = ?");
    if ($stmt === false) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param('i', $categoryId);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Category deleted successfully!";
    } else {
        throw new Exception("Delete failed: " . $stmt->error);
    }
    
    $stmt->close();
} catch (Exception $e) {
    $_SESSION['error'] = "Error deleting category: " . $e->getMessage();
}

$conn->close();
header("Location: ../views/products/categories.php");
exit;
?>