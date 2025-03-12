<?php
include '../../database/database.php';

header('Content-Type: application/json');

if (isset($_POST['receiving_id'])) {
    $receivingId = $_POST['receiving_id'];

    // Query the view
    $query = "
        SELECT 
            ReceivingID AS receiving_id,
            SupplierID AS supplier_id,
            Date AS date,
            product_name,
            Quantity AS quantity,
            UnitCost AS unit_cost
        FROM Receiving_Details_View
        WHERE ReceivingID = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $receivingId);
    $stmt->execute();
    $result = $stmt->get_result();

    $response = [
        'receiving_id' => null,
        'supplier_id' => null,
        'date' => null,
        'products' => []
    ];

    while ($row = $result->fetch_assoc()) {
        // Set header info (only once, assuming ReceivingID is unique)
        if (!$response['receiving_id']) {
            $response['receiving_id'] = $row['receiving_id'];
            $response['supplier_id'] = $row['supplier_id'];
            $response['date'] = date('m-d-y', strtotime($row['date']));
        }

        // Add product details if they exist
        if ($row['product_name']) {
            $response['products'][] = [
                'product_name' => htmlspecialchars($row['product_name']),
                'quantity' => $row['quantity'],
                'unit_cost' => $row['unit_cost']
            ];
        }
    }

    echo json_encode($response);

    $stmt->close();
} else {
    echo json_encode(['error' => 'No ReceivingID provided']);
}

$conn->close();
