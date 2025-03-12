<?php
session_start();
header('Content-Type: application/json');
include '../../database/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in', 'session_id' => session_id()]);
    exit();
}

$orderId = filter_input(INPUT_GET, 'orderId', FILTER_VALIDATE_INT);
if (!$orderId) {
    echo json_encode(['error' => 'Invalid Order ID']);
    exit();
}

$stmt = $conn->prepare("
    SELECT 
        ol.ProductID, 
        p.name AS ProductName, 
        ol.Quantity AS OriginalQuantity,
        COALESCE(SUM(rd.QuantityReturned), 0) AS TotalReturned,
        (ol.Quantity - COALESCE(SUM(rd.QuantityReturned), 0)) AS RemainingQuantity
    FROM orderline ol
    LEFT JOIN products p ON ol.ProductID = p.ProductID
    LEFT JOIN (
        SELECT rd.ProductID, rd.QuantityReturned
        FROM return_details rd
        JOIN returns r ON rd.ReturnID = r.ReturnID
        WHERE r.OrderID = ?
    ) rd ON ol.ProductID = rd.ProductID
    WHERE ol.OrderID = ?
    GROUP BY ol.ProductID, p.name, ol.Quantity
");
if (!$stmt) {
    echo json_encode(['error' => 'Database prepare failed: ' . $conn->error]);
    exit();
}
$stmt->bind_param("ii", $orderId, $orderId);
$stmt->execute();
$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if (empty($result)) {
    echo json_encode(['message' => 'No products found in orderline for OrderID: ' . $orderId, 'user_id' => $_SESSION['user_id']]);
} else {
    $response = array_map(function($row) {
        return [
            'ProductID' => $row['ProductID'],
            'ProductName' => $row['ProductName'],
            'Quantity' => $row['RemainingQuantity']
        ];
    }, $result);
    echo json_encode($response);
}

$stmt->close();
$conn->close();
?>