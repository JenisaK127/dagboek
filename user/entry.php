<?php
require_once '../includes/db.php';
// Check of ingelogd
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$entry_id = isset($_GET['id']) ? intval($_GET['id']) : null;
$entry = null;
$error = '';
$success = '';

// Laad entry als edit mode
if ($entry_id) {
    $stmt = $conn->prepare("SELECT id, date, title, content FROM diary_entries WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $entry_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        header('Location: dashboard.php');
        exit();
    }
    $entry = $result->fetch_assoc();
}

// Handler voor POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $date = $_POST['date'] ?? date('Y-m-d');
    
    if (empty($title) || empty($content)) {
        $error = 'Titel en inhoud zijn verplicht!';
    } else {
        if ($entry_id) {
            // Update entry
            $stmt = $conn->prepare("UPDATE diary_entries SET title = ?, content = ?, date = ? WHERE id = ? AND user_id = ?");
            $stmt->bind_param("sssii", $title, $content, $date, $entry_id, $user_id);
            
            if ($stmt->execute()) {
                header('Location: dashboard.php?success=1');
                exit();
            } else {
                $error = 'Fout bij opslaan entry!';
            }
        } else {
            // Controleer of voor deze dag al een entry bestaat
            $stmt = $conn->prepare("SELECT id FROM diary_entries WHERE user_id = ? AND date = ?");
            $stmt->bind_param("is", $user_id, $date);
            $stmt->execute();
            
            if ($stmt->get_result()->num_rows > 0) {
                $error = 'Je hebt al een dagboekentry voor deze datum!';
            } else {
                // Voeg nieuwe entry toe
                $stmt = $conn->prepare("INSERT INTO diary_entries (user_id, title, content, date) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $user_id, $title, $content, $date);
                
                if ($stmt->execute()) {
                    header('Location: dashboard.php?success=1');
                    exit();
                } else {
                    $error = 'Fout bij opslaan entry!';
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dagboek - <?php echo $entry ? 'Bewerken' : 'Nieuwe Entry'; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container-entry">
        <h1 class="dashboard-title"><?php echo $entry ? '✏️ Dagboekentry Bewerken' : '📝 Nieuwe Dagboekentry'; ?></h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="date">Datum</label>
                    <input type="date" id="date" name="date" value="<?php echo $entry ? $entry['date'] : date('Y-m-d'); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="title">Titel</label>
                    <input type="text" id="title" name="title" placeholder="Titel van je entry..." value="<?php echo htmlspecialchars($entry['title'] ?? ''); ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="content">Inhoud</label>
                <textarea id="content" name="content" placeholder="Schrijf hier je gedachten, gevoelens en herinneringen..." required><?php echo htmlspecialchars($entry['content'] ?? ''); ?></textarea>
            </div>
            
            <div class="button-group">
                <button type="submit" class="btn-success">💾 Opslaan</button>
                <a href="dashboard.php" class="btn btn-secondary">← Terug</a>
            </div>
        </form>
    </div>
</body>
</html>
