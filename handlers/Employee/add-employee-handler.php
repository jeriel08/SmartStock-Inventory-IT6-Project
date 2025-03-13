<?php
session_start();
include '../../database/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Get form data
        $firstName = $_POST['employeeFirstName'] ?? '';
        $lastName = $_POST['employeeLastName'] ?? '';
        $username = $_POST['employeeUsername'] ?? '';
        $password = $_POST['employeePassword'] ?? '';
        $role = $_POST['employeeRole'] ?? '';

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Get the current user's ID for Created_By and Updated_By
        $createdBy = $_SESSION['user_id'];
        $updatedBy = $_SESSION['user_id'];

        // Prepare and execute the insert statement
        $stmt = $conn->prepare(
            "INSERT INTO employees (FirstName, LastName, Username, Password, Role, Created_At, Created_By, Updated_At, Updated_By) 
            VALUES (?, ?, ?, ?, ?, NOW(), ?, NOW(), ?)"
        );
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param('sssssii', $firstName, $lastName, $username, $hashedPassword, $role, $createdBy, $updatedBy);

        if ($stmt->execute()) {
            $_SESSION['status_update_success'] = "Employee added successfully!";
            header("Location: ../../views/admin/employee-accounts.php"); // Redirect to accounts on success
            exit;
        } else {
            throw new Exception("Insert failed: " . $stmt->error);
        }
    } catch (Exception $e) {
        $_SESSION['status_update_error'] = "Operation Failed. Please try again.";
        header("Location: ../../views/admin/employee-accounts.php");
        exit;
    }
}
