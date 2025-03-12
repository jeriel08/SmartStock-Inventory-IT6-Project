<?php
include '../../database/database.php';

if (isset($_GET['orderId'])) {
    $orderId = $_GET['orderId'];

    $stmt = $conn->prepare("
        SELECT p.ProductID, p.ProductName 
        FROM order_details od 
        JOIN products p ON od.ProductID = p.ProductID 
        WHERE od.OrderID = ?
    ");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    echo json_encode($products);
}

$conn->close();
?>
