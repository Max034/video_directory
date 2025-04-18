<?php
session_start();
if (!isset($_SESSION['email'])) {
    http_response_code(401);
    echo json_encode([]);
    exit;
}

$user_email = $_SESSION['email'];

$conn = new mysqli("localhost", "root", "", "ideastream1");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "
  SELECT 
    i.id,
    i.title,
    i.description,
    i.video_url,
    i.view_count,
    (SELECT COUNT(*) FROM idea_likes    WHERE idea_id = i.id) AS likes,
    (SELECT COUNT(*) FROM idea_comments WHERE idea_id = i.id) AS comments
  FROM ideas i
  WHERE i.user_email = ?
  ORDER BY i.id DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

$videos = [];
while ($row = $result->fetch_assoc()) {
    $row['view_count'] = (int)$row['view_count'];
    $row['likes']      = (int)$row['likes'];
    $row['comments']   = (int)$row['comments'];
    $videos[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($videos);
?>
