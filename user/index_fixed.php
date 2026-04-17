<?php
require_once '../includes/db.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Als al ingelogd, ga naar kapstok
if (isset($_SESSION['user_id'])) {
    header('Location: cover.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($username) || empty($password)) {
            $error = 'Vul beide velden in!';
        } else {
            $stmt = $conn->prepare("SELECT id, password, full_name FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $username;
                    $_SESSION['full_name'] = $user['full_name'];
                    header('Location: cover.php');
                    exit();
                } else {
                    $error = 'Ongeldig wachtwoord!';
                }
            } else {
                $error = 'Gebruiker niet gevonden!';
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
    <title>Dagboek - Inloggen</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container-auth">
        <h1>Dagboek</h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">Gebruikersnaam</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Wachtwoord</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" name="login" class="btn-primary">Inloggen</button>
        </form>
        
        <div class="links">
            Nog geen account?
            <a href="register.php">Account aanmaken</a>
        </div>
    </div>
</body>
</html>

