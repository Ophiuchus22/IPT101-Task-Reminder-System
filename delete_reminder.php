<?php
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['id'])) {
    echo json_encode(array("success" => false, "error" => "Invalid JSON data"));
    exit;
}

$id = $data['id'];

$sql = "DELETE FROM reminders WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

$response = array();
if ($stmt->execute()) {
    $response["success"] = true;
} else {
    $response["success"] = false;
    $response["error"] = $stmt->error;
}

echo json_encode($response);

$stmt->close();
$conn->close();
?>
