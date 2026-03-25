<?php
require_once '../includes/db.php';

// Als al ingelogd, ga naar kapstok
if (isset($_SESSION['user_id'])) {
    header('Location: cover.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $full_name = $_POST['full_name'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    
    // Validatie
    if (empty($username) || empty($email) || empty($full_name) || empty($password)) {
        $error = 'Vul alle velden in!';
    } elseif (strlen($password) < 6) {
        $error = 'Wachtwoord moet minimaal 6 tekens zijn!';
    } elseif ($password !== $password_confirm) {
        $error = 'Wachtwoorden komen niet overeen!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Ongeldig email adres!';
    } else {
        // Check of gebruiker al bestaat
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            $error = 'Gebruikersnaam of email bestaat al!';
        } else {
            // Wachtwoord hashen en opslaan
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, full_name, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $full_name, $hashed_password);
            
            if ($stmt->execute()) {
                $success = 'Account aangemaakt! Je kunt nu inloggen.';
                // Redirect na 2 seconden
                echo "<meta http-equiv='refresh' content='2;url=index.php'>";
            } else {
                $error = 'Fout bij aanmaken account!';
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
    <title>Dagboek - Account Aanmaken</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container-auth">
        <h1>Account Aanmaken</h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="full_name">Volledige naam</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>
            
            <div class="form-group">
                <label for="username">Gebruikersnaam</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Wachtwoord</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="password_confirm">Wachtwoord bevestigen</label>
                <input type="password" id="password_confirm" name="password_confirm" required>
            </div>
            
            <button type="submit" class="btn-primary">Account Aanmaken</button>
        </form>
        
        <div class="links">
            Heb je al een account? <a href="index.php">Inloggen</a>
        </div>
    </div>
</body>
</html>
