<?php
session_start();
if(!isset($_SESSION['email'])) { http_response_code(401); exit; }
$idea_id    = intval($_POST['idea_id']);
$user_email = $conn->real_escape_string($_SESSION['email']);

$conn = new mysqli("localhost","root","","ideastream1");
$stmt = $conn->prepare("
  INSERT IGNORE INTO idea_likes (idea_id, user_email)
  VALUES (?, ?)
");
$stmt->bind_param("is", $idea_id, $user_email);
$stmt->execute();
echo json_encode(['success'=>true]);
?>