<?php
include '../../database/database.php';

$employeeID = isset($_GET['employeeID']) ? intval($_GET['employeeID']) : 0;

if ($employeeID > 0) {
    $stmt = $conn->prepare("SELECT EmployeeID, FirstName, LastName, Username, Password, Role, Status FROM employees WHERE EmployeeID = ?");
    $stmt->bind_param('i', $employeeID);
    $stmt->execute();
    $result = $stmt->get_result();
    $employee = $result->fetch_assoc();

    if ($employee) {
        echo json_encode($employee);
    } else {
        echo json_encode(['error' => 'Employee not found']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid EmployeeID']);
}

$conn->close();
