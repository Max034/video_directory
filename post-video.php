<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_email'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$conn = new mysqli("localhost", "root", "", "ideastream1");
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

$title = $conn->real_escape_string($data['title']);
$description = $conn->real_escape_string($data['description']);
$video_url = $conn->real_escape_string($data['video_url']);
$user_email = $_SESSION['user_email'];

$sql = "INSERT INTO ideas (title, description, video_url, user_email)
        VALUES ('$title', '$description', '$video_url', '$user_email')";

if ($conn->query($sql)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => $conn->error]);
}
?>
