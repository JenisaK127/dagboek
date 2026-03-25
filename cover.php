<?php
require_once 'config.php';

// Check of ingelogd
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$full_name = $_SESSION['full_name'] ?? 'Gebruiker';
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dagboek - Kaftpagina</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="cover-page">
    <div class="cover">
        <div class="cover-header">
            <!-- <div class="cover-icon">📖</div> -->
            <div class="cover-title">DAGBOEK</div>
            <div class="subtitle">Mijn persoonlijke herinneringen</div>
            <div class="owner-name"><?php echo htmlspecialchars($full_name); ?></div>
        </div>
        
        <div class="cover-footer">
            <a href="dashboard.php" style="text-decoration: none;">
                <button class="open-btn">OPEN →</button>
            </a>
            <form action="logout.php" method="POST">
                <button type="submit" class="logout-btn">Uitloggen</button>
            </form>
        </div>
    </div>
</body>
</html>
