<?php
include "../config/db.php";

// Validate input
if (!isset($_GET['location']) || empty($_GET['location'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Please provide location parameter"
    ]);
    exit;
}

$location = $conn->real_escape_string($_GET['location']);

$sql = "
SELECT c.id, c.name, 
       (c.daily_capacity - c.current_assigned_count) AS remaining_capacity
FROM couriers c
JOIN courier_locations cl ON c.id = cl.courier_id
WHERE cl.location = '$location'
AND c.current_assigned_count < c.daily_capacity
";

$result = $conn->query($sql);

$couriers = [];
while ($row = $result->fetch_assoc()) {
    $couriers[] = $row;
}

echo json_encode([
    "status" => "success",
    "couriers" => $couriers
]);
?>
