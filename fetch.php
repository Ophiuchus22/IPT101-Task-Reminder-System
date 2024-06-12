<?php
header('Content-Type: application/json');

// Include the database connection file
include 'db.php';

// Update reminders that are in the past to 'completed'
$update_sql = "UPDATE reminders SET status = 'completed' WHERE CONCAT(date, ' ', time) < NOW()";
$conn->query($update_sql);

// Fetch reminders that are not in the past
$sql = "SELECT id,title, description, date, time, status FROM reminders ORDER BY id DESC";
$result = $conn->query($sql);

$reminders = array();
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $reminders[] = $row;
  }
}

echo json_encode($reminders);

$conn->close();
?>
