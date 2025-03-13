<?php
// Start the session
session_start();

include '../../database/database.php';

// Capture session data before destroying it
$employeeID = $_SESSION['user_id'] ?? 0;
$username = $_SESSION['username'] ?? 'Unknown';

if ($employeeID > 0) {
    $stmt = $conn->prepare("INSERT INTO audit_logs (TableName, RecordID, ActionType, NewValue, AdminID, Timestamp) 
                            VALUES ('Employees', ?, 'Logout', ?, ?, NOW())");
    $stmt->bind_param('isi', $employeeID, $username, $employeeID);
    $stmt->execute();
    $stmt->close();
}

// Destroy all session data
session_unset();  // Remove all session variables
session_destroy(); // Destroy the session

// Redirect to the login page
header("Location: ../../index.php");
exit;
