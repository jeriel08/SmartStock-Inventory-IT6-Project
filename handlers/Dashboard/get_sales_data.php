<?php
include '../../database/database.php';

$sql = "SELECT DATE(Date) AS order_date, SUM(total) AS total_sales 
        FROM orders 
        GROUP BY DATE(Date) 
        ORDER BY Date ASC";

$result = $conn->query($sql);

$sales_data = [];
while ($row = $result->fetch_assoc()) {
    $sales_data[] = $row;
}

echo json_encode($sales_data);
