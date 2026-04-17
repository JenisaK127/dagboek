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
    $db = new DB();
    $stmt = $db->run("SELECT id, date, title, content FROM diary_entries WHERE id = ? AND user_id = ?", [$entry_id, $user_id]);
    
    $entry = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$entry) {
        header('Location: dashboard.php');
        exit();
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $date = $_POST['date'] ?? date('Y-m-d');

    if (empty($title) || empty($content)) {
        $error = 'Titel en inhoud zijn verplicht!';
    } else {
        $db = new DB();

        if ($entry_id) {
            $db->run(
                "UPDATE diary_entries 
                 SET title = ?, content = ?, date = ? 
                 WHERE id = ? AND user_id = ?",
                [$title, $content, $date, $entry_id, $user_id]
            );

            header('Location: dashboard.php?success=1');
            exit();

        } else {
            $stmt = $db->run(
                "SELECT id FROM diary_entries 
                 WHERE user_id = ? AND date = ?",
                [$user_id, $date]
            );

            if ($stmt->rowCount() > 0) {
                $error = 'Je hebt al een dagboekentry voor deze datum!';
            } else {
        
                $db->run(
                    "INSERT INTO diary_entries (user_id, title, content, date) 
                     VALUES (?, ?, ?, ?)",
                    [$user_id, $title, $content, $date]
                );

                header('Location: dashboard.php?success=1');
                exit();
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
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container-entry">
        <h1 class="dashboard-title"><?php echo $entry ? ' Dagboekentry Bewerken' : ' Nieuwe Dagboekentry'; ?></h1>
        
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
                <button type="submit" class="btn-success"> Opslaan</button>
                <a href="dashboard.php" class="btn btn-secondary">Terug</a>
            </div>
        </form>
    </div>
</body>
</html>
