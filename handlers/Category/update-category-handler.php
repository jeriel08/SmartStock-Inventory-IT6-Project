<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

include '../../database/database.php';

if (!isset($conn) || $conn->connect_error) {
    $_SESSION['error'] = "Database connection failed.";
    header("Location: ../../views/products/categories.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: ../../views/products/categories.php");
    exit;
}

// Get form data
$categoryId = $_POST['categoryID'] ?? '';
$name = $_POST['categoryName'] ?? '';
$description = $_POST['categoryDescription'] ?? '';
$status = $_POST['categoryStatus'] ?? 'Active'; // Default to Active if not provided

// Validate required fields
if (empty($categoryId) || empty($name) || !in_array($status, ['Active', 'Inactive'])) {
    $_SESSION['error'] = "Category ID, name, and valid status are required.";
    header("Location: ../../views/products/categories.php");
    exit;
}

// Sanitize inputs
$name = trim($name);
$description = trim($description);

// Update the category
try {
    $stmt = $conn->prepare(
        "UPDATE categories SET Name = ?, Description = ?, Status = ?, Updated_At = NOW(), Updated_By = ? WHERE CategoryID = ?"
    );

    if ($stmt === false) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $updatedBy = $_SESSION['user_id'];
    $stmt->bind_param('sssii', $name, $description, $status, $updatedBy, $categoryId);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Category updated successfully!";
    } else {
        throw new Exception("Update failed: " . $stmt->error);
    }

    $stmt->close();
} catch (Exception $e) {
    $_SESSION['error'] = "Error updating category: " . $e->getMessage();
}

$conn->close();
header("Location: ../../views/products/categories.php");
exit;
