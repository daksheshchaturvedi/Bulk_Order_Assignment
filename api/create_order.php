<?php
include "../config/db.php";

// Read JSON input
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

// Validation
if (!$data || !isset($data['delivery_location']) || !isset($data['order_value'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid input. Please send delivery_location and order_value"
    ]);
    exit;
}

$date = date("Y-m-d H:i:s");
$location = $conn->real_escape_string($data['delivery_location']);
$value = (float)$data['order_value'];

$sql = "INSERT INTO orders(order_date, delivery_location, order_value, status)
        VALUES('$date','$location',$value,'UNASSIGNED')";

if ($conn->query($sql)) {
    echo json_encode([
        "status" => "success",
        "message" => "Order created successfully"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => $conn->error
    ]);
}
?>
