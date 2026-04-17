<?php
require_once '../includes/db.php';
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
    $query .= " AND (title LIKE ? OR content LIKE ? OR date LIKE ?)";
    $search_term = "%$search%";
    $params = [$user_id, $search_term, $search_term, $search_term];
} else {
    $params = [$user_id];
}

$db = new DB();
$stmt = $db->run($query, $params);
$total_result = $stmt->fetch(PDO::FETCH_ASSOC);
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
    $query .= " AND (title LIKE ? OR content LIKE ? OR date LIKE ?)";
    $search_term = "%$search%";
    $params = [$user_id, $search_term, $search_term, $search_term];
}

$limit = (int)$entries_per_page;
$off = (int)$offset;

$query .= " ORDER BY date ASC LIMIT $limit OFFSET $off";

$db = new DB();
$stmt = $db->run($query, $params);
$entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dagboek - Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <?php if (isset($_GET['deleted'])): ?>
            <div class="success-message" style="background-color: #d4edda; color: #155724; padding: 12px; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 20px;">
                Entry succesvol verwijderd!
            </div>
        <?php endif; ?>

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
                <button type="submit" class="btn-primary"> Zoeken</button>
                <?php if ($search): ?>de search filter de 
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
            <span class="entry-date">
                <?php echo date('d M Y', strtotime($entry['date'])); ?>
            </span>
        </div>

        <div class="entry-title">
            <?php echo htmlspecialchars($entry['title']); ?>
        </div>

        <div class="entry-preview">
            <?php 
                $preview = substr(strip_tags($entry['content']), 0, 150);
                echo htmlspecialchars($preview) . '...'; 
            ?>
        </div>

        <div class="entry-actions">
            <a href="entry.php?id=<?php echo $entry['id']; ?>" class="edit-btn">
                Bewerken
            </a>

            <a href="../includes/delete_entry.php?id=<?php echo $entry['id']; ?>" 
               class="delete-btn" 
               onclick="return confirm('Weet u zeker dat u deze entry wilt verwijderen?')">
               Verwijderen
            </a>
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
