<?php
session_start();
include '../../database/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode([]);
    exit();
}

$customerId = filter_input(INPUT_GET, 'customerId', FILTER_VALIDATE_INT);
if (!$customerId) {
    header('Content-Type: application/json');
    echo json_encode([]);
    exit();
}

$stmt = $conn->prepare("
    SELECT OrderID, Date 
    FROM orders 
    WHERE CustomerID = ? AND Status = 'Paid'
    ORDER BY Date DESC
");
$stmt->bind_param("i", $customerId);
$stmt->execute();
$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

header('Content-Type: application/json');
echo json_encode($result);
$stmt->close();
$conn->close();
?>