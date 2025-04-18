<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost","root","","ideastream1");
if ($conn->connect_error) die(json_encode([]));

$sql = "
  SELECT 
    id, title, description, video_url,
    view_count,
    (SELECT COUNT(*) FROM idea_likes    WHERE idea_id = i.id) AS likes,
    (SELECT COUNT(*) FROM idea_shares   WHERE idea_id = i.id) AS shares,
    (SELECT COUNT(*) FROM idea_comments WHERE idea_id = i.id) AS comments,
    tags
  FROM ideas i
  ORDER BY id DESC
";

$result = $conn->query($sql);
$videos = [];
while($row = $result->fetch_assoc()){
  // cast counts
  $row['view_count'] = (int)$row['view_count'];
  $row['likes']      = (int)$row['likes'];
  $row['shares']     = (int)$row['shares'];
  $row['comments']   = (int)$row['comments'];
  // split tags into array
  $row['tags']       = array_filter(array_map('trim', explode(',', $row['tags'])));
  $videos[] = $row;
}
echo json_encode($videos);
$conn->close();
?>
