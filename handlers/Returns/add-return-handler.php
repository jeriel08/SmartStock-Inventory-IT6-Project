<?php
session_start();
include '../../database/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerName = trim($_POST['customerName']);
    $address = trim($_POST['address']);
    $phoneNumber = trim($_POST['phoneNumber']);
    $orderId = $_POST['orderId'];
    $returnDate = $_POST['returnDate'];
    $reason = $_POST['reason'];
    $createdBy = $_SESSION['user_id'];

    // Validate inputs
    if (empty($customerName) || empty($address) || empty($phoneNumber) || empty($orderId) || empty($returnDate) || empty($reason)) {
        $_SESSION['return_error'] = "All fields are required";
        header('Location: ../../views/returns.php');
        exit();
    }

    // Look up or create CustomerID based on CustomerName
    $stmt = $conn->prepare("SELECT CustomerID FROM customers WHERE Name = ?");
    $stmt->bind_param("s", $customerName);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        // Customer not found, create new customer with address and phone number
        $stmt = $conn->prepare("
            INSERT INTO customers (Name, Address, PhoneNumber, Created_At, Created_By, Updated_At, Updated_By)
            VALUES (?, ?, ?, NOW(), ?, NOW(), ?)
        ");
        $stmt->bind_param("sssii", $customerName, $address, $phoneNumber, $createdBy, $createdBy);
        $stmt->execute();
        $customerId = $conn->insert_id; // Get the new CustomerID
        $stmt->close();
    } else {
        $customer = $result->fetch_assoc();
        $customerId = $customer['CustomerID'];
        $stmt->close();
    }

    // Insert into returns table
    $stmt = $conn->prepare("
        INSERT INTO returns (CustomerID, OrderID, ReturnDate, Reason, Created_At, Created_By, Updated_At, Updated_By)
        VALUES (?, ?, ?, ?, NOW(), ?, NOW(), ?)
    ");
    $stmt->bind_param("iissii", $customerId, $orderId, $returnDate, $reason, $createdBy, $createdBy);

    $stmt->execute();
    $stmt->close();
    $_SESSION['return_success'] = "Return recorded successfully.";
    header('Location: ../../views/returns.php');
    exit();
}
