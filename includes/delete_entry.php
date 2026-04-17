<?php
session_start();
require_once 'db.php';

// Check of ingelogd
if (!isset($_SESSION['user_id'])) {
    header('Location: ../user/index.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$entry_id = isset($_GET['id']) ? intval($_GET['id']) : null;

// Als geen id, terug naar dashboard
if (!$entry_id) {
    header('Location: ../user/dashboard.php');
    exit();
}

// Check of entry van deze gebruiker is
$db = new DB();
$stmt = $db->run("SELECT id FROM diary_entries WHERE id = ? AND user_id = ?", [$entry_id, $user_id]);

if (!$stmt->fetch()) {
    header('Location: ../user/dashboard.php');
    exit();
}

// Verwijder entry
$db->run("DELETE FROM diary_entries WHERE id = ? AND user_id = ?", [$entry_id, $user_id]);

// Redirect terug naar dashboard
header('Location: ../user/dashboard.php?deleted=1');
exit();