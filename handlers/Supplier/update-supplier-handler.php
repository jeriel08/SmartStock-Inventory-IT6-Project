<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

include '../../database/database.php';

if (!isset($conn) || $conn->connect_error) {
    $_SESSION['success_error'] = "Database connection failed.";
    header("Location: ../../views/suppliers.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['success_error'] = "Invalid request method.";
    header("Location: ../../views/suppliers.php");
    exit;
}

// Get form data
$supplierId = $_POST['supplierId'] ?? '';
$name = $_POST['supplierName'] ?? '';
$address = $_POST['supplierAddress'] ?? '';
$phone = $_POST['supplierPhone'] ?? '';
$status = $_POST['supplierStatus'] ?? '';

// Validate required fields
if (empty($supplierId) || empty($name) || empty($address) || empty($phone) || empty($status)) {
    $_SESSION['success_error'] = "All fields are required.";
    header("Location: ../../views/suppliers.php");
    exit;
}

// Sanitize inputs
$name = trim($name);
$address = trim($address);
$phone = trim($phone);
$status = ($status === 'Active') ? 'Active' : 'Inactive';

try {
    $stmt = $conn->prepare(
        "UPDATE suppliers SET Name = ?, Address = ?, PhoneNumber = ?, Status = ?, Updated_At = NOW(), Updated_By = ? WHERE SupplierID = ?"
    );

    if ($stmt === false) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $updatedBy = $_SESSION['user_id'];
    $stmt->bind_param('ssssii', $name, $address, $phone, $status, $updatedBy, $supplierId);

    if ($stmt->execute()) {
        $_SESSION['supplier_success'] = "Supplier updated successfully!";
    } else {
        throw new Exception("Update failed: " . $stmt->error);
    }

    $stmt->close();
} catch (Exception $e) {
    $_SESSION['success_error'] = "Error updating supplier: " . $e->getMessage();
}

$conn->close();
header("Location: ../../views/suppliers.php");
exit;
