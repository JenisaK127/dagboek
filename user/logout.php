<?php
require_once '../includes/db.php';
// Logout
session_destroy();
header('Location: index.php');
exit();
?>
