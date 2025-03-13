<?php
session_start();
if (!isset($_SESSION['role']) || strtoupper($_SESSION['role']) !== 'ADMIN') {
    header("Location: ../../index.php");
    exit();
}

include "../../database/database.php";

$employeeID = isset($_POST['employeeID']) ? intval($_POST['employeeID']) : 0;
$firstName = $_POST['firstName'] ?? '';
$lastName = $_POST['lastName'] ?? '';
$username = $_POST['username'] ?? '';
$role = $_POST['role'] ?? '';
$password = $_POST['password'] ?? '';
$status = $_POST['status'] ?? '';
$updatedBy = $_SESSION['user_id'];

if ($employeeID > 0 && !empty($firstName) && !empty($lastName) && !empty($username) && !empty($role) && in_array($status, ['Active', 'Inactive'])) {
    // Prepare the base query
    $query = "UPDATE employees SET FirstName = ?, LastName = ?, Username = ?, Role = ?, Status = ?, Updated_at = NOW(), Updated_by = ?";
    $params = [$firstName, $lastName, $username, $role, $status, $updatedBy];
    $types = 'sssssi';

    // If a new password is provided, hash it and include it in the update
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query .= ", Password = ?";
        $params[] = $hashedPassword;
        $types .= 's';
    }

    $query .= " WHERE EmployeeID = ?";
    $params[] = $employeeID;
    $types .= 'i';

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        $_SESSION['status_update_success'] = "Employee ID $employeeID updated successfully!";
        header("Location: ../../views/admin/employee-accounts.php");
    } else {
        $_SESSION['status_update_error'] = "Failed to update employee: " . $conn->error;
        header("Location: ../../views/admin/employee-accounts.php");
    }

    $stmt->close();
} else {
    $_SESSION['status_update_error'] = "Invalid or incomplete data provided.";
    header("Location: ../../views/admin/employee-accounts.php");
}

$conn->close();
