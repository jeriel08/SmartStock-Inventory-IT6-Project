<?php
session_start();
include '../../database/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employeeID = $_POST['employeeID'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE Employees SET Status = ?, Updated_at = NOW() WHERE EmployeeID = ?");
    $stmt->bind_param("si", $status, $employeeID);

    if ($stmt->execute()) {
        $_SESSION['status_update_success'] = "Employee status updated successfully.";
    } else {
        $_SESSION['status_update_error'] = "Error updating status.";
    }

    header("Location: ../../views/admin/employee-accounts.php");
    exit();
}
