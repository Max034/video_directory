<?php
session_start();
session_unset();
session_destroy();
header("Location: project.html?loggedout=1");
exit();
?>
