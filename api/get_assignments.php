<?php
include "../config/db.php";

$date = $_GET['date'];

$result = $conn->query("SELECT * FROM order_assignments WHERE DATE(assignment_date)='$date'");

$assignments = [];

while ($row = $result->fetch_assoc()) {
    $assignments[] = $row;
}

echo json_encode(["assignments" => $assignments]);
?>
