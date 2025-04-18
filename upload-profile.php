<?php
session_start();
if (!isset($_SESSION['email'])) {
    http_response_code(401);
    exit('Unauthorized');
}

if (!isset($_FILES['profile']) || $_FILES['profile']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    exit('No file uploaded.');
}

$email = $_SESSION['email'];
$uploadDir = 'uploads/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$ext = pathinfo($_FILES['profile']['name'], PATHINFO_EXTENSION);
$filename = uniqid() . '.' . $ext;
$targetFile = $uploadDir . $filename;

if (move_uploaded_file($_FILES['profile']['tmp_name'], $targetFile)) {
    $conn = new mysqli("localhost", "root", "", "ideastream1");
    if ($conn->connect_error) exit('DB error');

    $stmt = $conn->prepare("UPDATE users SET profile_pic = ? WHERE email = ?");
    $stmt->bind_param("ss", $targetFile, $email);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo $targetFile;
} else {
    http_response_code(500);
    echo "Upload failed.";
}
?>
