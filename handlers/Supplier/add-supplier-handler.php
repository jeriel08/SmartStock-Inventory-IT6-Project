<?php
session_start();
include '../../database/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php"); // Adjusted to root level
    exit;
}

// Verify database connection
if (!isset($conn) || $conn->connect_error) {
    $_SESSION['error'] = "Database connection failed.";
    header("Location: ../../views/suppliers.php");
    exit;
}

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../views/suppliers.php");
    exit;
}

// Get form data with fallbacks
$name = $_POST['supplierName'] ?? '';
$address = $_POST['address'] ?? '';
$contact = $_POST['contactNumber'] ?? '';
$imageFile = $_FILES['profileImage'] ?? null;

// Validate required fields
if (empty($name) || empty($address) || empty($contact)) {
    $_SESSION['error'] = "Name, address, and contact number are required.";
    header("Location: ../../views/suppliers.php");
    exit;
}

// Handle image upload (optional field)
$profileImagePath = null; // Default to null if no image is uploaded
if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../statics/images/uploads/'; // Ensure this directory exists and is writable
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB

    // Validate file
    if (!in_array($imageFile['type'], $allowedTypes)) {
        $_SESSION['error'] = "Only JPEG, PNG, and GIF images are allowed.";
        header("Location: ../../views/suppliers.php");
        exit;
    }
    if ($imageFile['size'] > $maxFileSize) {
        $_SESSION['error'] = "Image file size must be less than 5MB.";
        header("Location: ../../views/suppliers.php");
        exit;
    }

    // Generate a unique filename
    $fileExt = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
    $fileName = 'supplier_' . time() . '.' . $fileExt;
    $profileImagePath = $uploadDir . $fileName;

    // Move the uploaded file
    if (!move_uploaded_file($imageFile['tmp_name'], $profileImagePath)) {
        $_SESSION['error'] = "Failed to upload image.";
        header("Location: ../../views/suppliers.php");
        exit;
    }
}

// Insert into database
try {
    $stmt = $conn->prepare(
        "INSERT INTO suppliers (Name, Address, PhoneNumber, ProfileImage, Created_At, Created_By, Updated_At, Updated_By) 
         VALUES (?, ?, ?, ?, NOW(), ?, NOW(), ?)"
    );
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $createdBy = $_SESSION['user_id'];
    $updatedBy = $_SESSION['user_id'];
    $stmt->bind_param('ssssii', $name, $address, $contact, $profileImagePath, $createdBy, $updatedBy);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Supplier added successfully!";
        header("Location: ../../views/suppliers.php");
        exit;
    } else {
        throw new Exception("Insert failed: " . $stmt->error);
    }

    $stmt->close();
} catch (Exception $e) {
    // Clean up uploaded file if insert fails
    if ($profileImagePath && file_exists($profileImagePath)) {
        unlink($profileImagePath);
    }
    $_SESSION['error'] = "Error adding supplier: " . $e->getMessage();
    header("Location: ../../views/suppliers.php");
    exit;
}

$conn->close();
