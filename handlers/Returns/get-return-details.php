<?php
session_start();
header('Content-Type: application/json');
include '../../database/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

$returnId = filter_input(INPUT_GET, 'returnId', FILTER_VALIDATE_INT);
if (!$returnId) {
    echo json_encode(['error' => 'Invalid Return ID']);
    exit();
}

$stmt = $conn->prepare("
    SELECT 
        rd.ProductID,
        p.name AS ProductName,
        rd.QuantityReturned,
        rd.Reason
    FROM return_details rd
    LEFT JOIN products p ON rd.ProductID = p.ProductID
    WHERE rd.ReturnID = ?
");
if (!$stmt) {
    echo json_encode(['error' => 'Database prepare failed: ' . $conn->error]);
    exit();
}
$stmt->bind_param("i", $returnId);
$stmt->execute();
$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if (empty($result)) {
    echo json_encode([]);
} else {
    echo json_encode($result);
}

$stmt->close();
$conn->close();
?>