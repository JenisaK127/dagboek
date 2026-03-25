<?php
require_once 'db.php';

// Check of ingelogd
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$entry_id = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$entry_id) {
    header('Location: dashboard.php');
    exit();
}

// Check of entry van deze gebruiker is
$stmt = $conn->prepare("SELECT id FROM diary_entries WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $entry_id, $user_id);
$stmt->execute();

if ($stmt->get_result()->num_rows === 0) {
    header('Location: dashboard.php');
    exit();
}

// Verwijder entry
$stmt = $conn->prepare("DELETE FROM diary_entries WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $entry_id, $user_id);

if ($stmt->execute()) {
    header('Location: dashboard.php?deleted=1');
} else {
    header('Location: dashboard.php?error=1');
}
exit();
?>
