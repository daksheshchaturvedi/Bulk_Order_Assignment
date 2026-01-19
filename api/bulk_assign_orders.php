<?php
include "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!isset($data['order_ids']) || !is_array($data['order_ids'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Please provide order_ids array"
    ]);
    exit;
}

// Limit to 500 per run (performance safe)
$order_ids = array_slice($data['order_ids'], 0, 500);

$assigned = [];
$failed = [];

$conn->begin_transaction();

try {
    foreach ($order_ids as $order_id) {

        // Lock order row
        $order = $conn->query("
            SELECT delivery_location 
            FROM orders 
            WHERE order_id=$order_id AND status='UNASSIGNED' 
            FOR UPDATE
        ");

        if ($order->num_rows == 0) {
            $failed[] = $order_id;
            continue;
        }

        $orderData = $order->fetch_assoc();
        $location = $orderData['delivery_location'];

        // Find courier with capacity
        $courier = $conn->query("
            SELECT c.id 
            FROM couriers c
            JOIN courier_locations cl ON c.id = cl.courier_id
            WHERE cl.location='$location'
            AND c.current_assigned_count < c.daily_capacity
            LIMIT 1 
            FOR UPDATE
        ");

        if ($courier->num_rows == 0) {
            $failed[] = $order_id;
            continue;
        }

        $c = $courier->fetch_assoc();
        $courier_id = $c['id'];

        // Insert assignment
        $conn->query("
            INSERT INTO order_assignments(order_id, courier_id, assignment_date)
            VALUES($order_id, $courier_id, NOW())
        ");

        // Update order
        $conn->query("UPDATE orders SET status='ASSIGNED' WHERE order_id=$order_id");

        // Update courier load
        $conn->query("
            UPDATE couriers 
            SET current_assigned_count = current_assigned_count + 1 
            WHERE id=$courier_id
        ");

        $assigned[] = $order_id;
    }

    $conn->commit();

    echo json_encode([
        "status" => "success",
        "assigned" => $assigned,
        "failed" => $failed
    ]);

} catch(Exception $e) {

    $conn->rollback();

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?>
