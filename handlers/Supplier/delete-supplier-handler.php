<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit;
}

include '../../database/database.php';

if (!isset($conn) || $conn->connect_error) {
    $_SESSION['error'] = "Database connection failed.";
    header("Location: ../../views/suppliers.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../views/suppliers.php");
    exit;
}

if (!isset($_POST['supplierId'])) {
    $_SESSION['error'] = "No supplier ID provided.";
    header("Location: ../../views/suppliers.php");
    exit;
}

$supplierId = $_POST['supplierId'];

// Fetch the image path to delete it
$stmt = $conn->prepare("SELECT ProfileImage FROM suppliers WHERE SupplierID = ?");
$stmt->bind_param('i', $supplierId);
$stmt->execute();
$result = $stmt->get_result();
$supplier = $result->fetch_assoc();
$stmt->close();

try {
    // Delete the supplier
    $stmt = $conn->prepare("DELETE FROM suppliers WHERE SupplierID = ?");
    $stmt->bind_param('i', $supplierId);

    if ($stmt->execute()) {
        // Remove the image file if it exists
        if ($supplier && !empty($supplier['ProfileImage']) && file_exists($supplier['ProfileImage'])) {
            unlink($supplier['ProfileImage']);
        }
        $_SESSION['success'] = "Supplier deleted successfully!";
    } else {
        throw new Exception("Delete failed: " . $stmt->error);
    }

    $stmt->close();
} catch (Exception $e) {
    $_SESSION['error'] = "Error deleting supplier: " . $e->getMessage();
}

$conn->close();
header("Location: ../../views/suppliers.php");
exit;
