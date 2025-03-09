<?php
include '../../database/database.php'; // Adjust this to your database connection file

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["orderID"])) {
    $orderID = intval($_POST["orderID"]);

    // Fetch order details
    $query = "SELECT * FROM Orders WHERE OrderID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $orderID);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();

    if (!$order) {
        echo json_encode(["success" => false]);
        exit;
    }

    // Fetch order items
    $query = "SELECT ProductName, Quantity, Price, Total FROM OrderDetailsView WHERE OrderID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $orderID);
    $stmt->execute();
    $result = $stmt->get_result();
    $orderLines = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode(["success" => true, "order" => $order, "orderLines" => $orderLines]);
    exit;
}
