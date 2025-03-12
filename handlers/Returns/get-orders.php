<?php
include '../../database/database.php';

if (isset($_GET['customerId'])) {
    $customerId = $_GET['customerId'];

    $stmt = $conn->prepare("SELECT OrderID FROM orders WHERE CustomerID = ?");
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $result = $stmt->get_result();

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    echo json_encode($orders);
}

$conn->close();
?>
