<?php
include '../../database/database.php';

header('Content-Type: application/json');

$query = "CALL GetSalesByCategory()";

$result = $conn->query($query);

$salesData = [];

while ($row = $result->fetch_assoc()) {
    $salesData[] = [
        "category" => $row['CategoryName'],
        "sales" => (float) $row['TotalSales']
    ];
}

echo json_encode($salesData);
