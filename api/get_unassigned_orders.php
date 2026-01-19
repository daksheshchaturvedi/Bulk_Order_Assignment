<?php
include "../config/db.php";

$result = $conn->query("SELECT * FROM orders WHERE status='UNASSIGNED'");

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

echo json_encode(["orders" => $orders]);
?>
