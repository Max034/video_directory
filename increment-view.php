<?php
session_start();
$idea_id = intval($_POST['idea_id'] ?? 0);
if(!$idea_id) exit;

$conn = new mysqli("localhost","root","","ideastream1");
$conn->query("UPDATE ideas SET view_count = view_count + 1 WHERE id = $idea_id");
$conn->close();
?>
