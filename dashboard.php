<?php
require_once 'config.php';

// Check of ingelogd
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$full_name = $_SESSION['full_name'] ?? 'Gebruiker';

// Paginering instellingen
$entries_per_page = 5;
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

// Zoekfilter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Query totaal entries
$query = "SELECT COUNT(*) as total FROM diary_entries WHERE user_id = ?";
$params = [$user_id];
$types = "i";

if ($search) {
    $query .= " AND (title LIKE ? OR content LIKE ?)";
    $search_term = "%$search%";
    $params = [$user_id, $search_term, $search_term];
    $types = "iss";
}

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$total_result = $stmt->get_result()->fetch_assoc();
$total_entries = $total_result['total'];
$total_pages = ceil($total_entries / $entries_per_page);

// Zorg dat current page niet groter dan total pages
$current_page = min($current_page, max(1, $total_pages));

$offset = ($current_page - 1) * $entries_per_page;

// Query entries
$query = "SELECT id, date, title, content FROM diary_entries WHERE user_id = ?";
$params = [$user_id];
$types = "i";

if ($search) {
    $query .= " AND (title LIKE ? OR content LIKE ?)";
    $search_term = "%$search%";
    $params = [$user_id, $search_term, $search_term];
    $types = "iss";
}

$query .= " ORDER BY date DESC LIMIT ? OFFSET ?";
$params[] = $entries_per_page;
$params[] = $offset;
$types .= "ii";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$entries = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dagboek - Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1> Dagboek</h1>
                <p class="user-welcome">Welkom, <?php echo htmlspecialchars($full_name); ?>!</p>
            </div>
            <div class="user-info">
                <a href="cover.php">← Terug naar kaft</a>
            </div>
        </div>
        
        <div class="search-section">
            <form class="search-form" method="GET">
                <input type="text" name="search" placeholder="Zoeken in je dagboek..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn-primary">🔍 Zoeken</button>
                <?php if ($search): ?>
                    <a href="dashboard.php">Wissen</a>
                <?php endif; ?>
            </form>
        </div>
        
        <a href="entry.php" class="new-entry-btn">+ Nieuwe dagboek entry</a>
        
        <?php if (empty($entries)): ?>
            <div class="empty-state">
                <p> Nog geen dagboek entries</p>
                <p>Begin met het schrijven van je eerste dagboek entry!</p>
            </div>
        <?php else: ?>
            <div class="entries">
                <?php foreach ($entries as $entry): ?>
                    <div class="entry">
                        <div class="entry-header">
                            <span class="entry-date"><?php echo date('d M Y', strtotime($entry['date'])); ?></span>
                        </div>
                        <div class="entry-title"><?php echo htmlspecialchars($entry['title']); ?></div>
                        <div class="entry-preview"><?php echo htmlspecialchars(substr(strip_tags($entry['content']), 0, 150)) . '...'; ?></div>
                        <div class="entry-actions">
                            <a href="entry.php?id=<?php echo $entry['id']; ?>" class="edit-btn">✏️ Bewerken</a>
                            <a href="delete_entry.php?id=<?php echo $entry['id']; ?>" class="delete-btn" onclick="return confirm('Zeker weten?')">🗑️ Verwijderen</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($current_page > 1): ?>
                        <a href="dashboard.php?page=1<?php if ($search): ?>&search=<?php echo urlencode($search); ?><?php endif; ?>">« Eerste</a>
                        <a href="dashboard.php?page=<?php echo $current_page - 1; ?><?php if ($search): ?>&search=<?php echo urlencode($search); ?><?php endif; ?>">‹ Vorige</a>
                    <?php else: ?>
                        <span class="disabled">« Eerste</span>
                        <span class="disabled">‹ Vorige</span>
                    <?php endif; ?>
                    
                    <?php
                    for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++):
                    ?>
                        <?php if ($i === $current_page): ?>
                            <span class="active"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="dashboard.php?page=<?php echo $i; ?><?php if ($search): ?>&search=<?php echo urlencode($search); ?><?php endif; ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($current_page < $total_pages): ?>
                        <a href="dashboard.php?page=<?php echo $current_page + 1; ?><?php if ($search): ?>&search=<?php echo urlencode($search); ?><?php endif; ?>">Volgende ›</a>
                        <a href="dashboard.php?page=<?php echo $total_pages; ?><?php if ($search): ?>&search=<?php echo urlencode($search); ?><?php endif; ?>">Laatste »</a>
                    <?php else: ?>
                        <span class="disabled">Volgende ›</span>
                        <span class="disabled">Laatste »</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
