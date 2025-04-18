<?php
session_start();
if (!isset($_SESSION['email'])) {
    http_response_code(401);
    exit;
}

$conn = new mysqli("localhost", "root", "", "ideastream1");
if ($conn->connect_error) exit;

$stmt = $conn->prepare("SELECT profile_pic FROM users WHERE email = ?");
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();
$stmt->bind_result($pic);
$stmt->fetch();
$stmt->close();
$conn->close();

echo $pic ?: 'default-profile.png';
?>
