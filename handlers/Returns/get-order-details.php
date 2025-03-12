<?php
session_start();
include '../../database/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode([]);
    exit();
}

$orderId = filter_input(INPUT_GET, 'orderId', FILTER_VALIDATE_INT);
if (!$orderId) {
    header('Content-Type: application/json');
    echo json_encode([]);
    exit();
}

$stmt = $conn->prepare("
    SELECT ol.ProductID, p.Name AS ProductName, ol.Quantity
    FROM orderline ol
    JOIN products p ON ol.ProductID = p.ProductID
    WHERE ol.OrderID = ?
");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

header('Content-Type: application/json');
echo json_encode($result);
$stmt->close();
$conn->close();
?>