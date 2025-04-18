<?php
session_start();

$conn = new mysqli("localhost", "root", "", "ideastream1");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// grab and sanitize
$title       = trim($_POST['title']       ?? '');
$description = trim($_POST['description'] ?? '');
$video_embed = $_POST['video_embed']      ?? '';
$user_email  = $_SESSION['email']         ?? null;

if (empty($title) || empty($description) || !$user_email) {
    header("Location: signin.html");
    exit();
}

// extract src URL
$video_url = '';
if (!empty($video_embed)) {
    preg_match('/src="([^"]+)"/', $video_embed, $m);
    if (isset($m[1])) $video_url = $m[1];
}

// insert
$stmt = $conn->prepare(
    "INSERT INTO ideas (title, description, video_url, user_email)
     VALUES (?, ?, ?, ?)"
);
$stmt->bind_param("ssss", $title, $description, $video_url, $user_email);

if ($stmt->execute()) {
    header("Location: profile.html?submitted=true");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
exit();
?>
