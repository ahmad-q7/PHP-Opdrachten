<?php
session_start();
require 'config.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'];
    $current_password = $_POST['current_password'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($current_password, $user['password'])) {
        $stmt = $pdo->prepare('UPDATE users SET password = :password WHERE username = :username');
        $stmt->execute(['password' => $new_password, 'username' => $username]);
        echo 'Wachtwoord succesvol gewijzigd.';
    } else {
        echo 'Huidig wachtwoord is onjuist.';
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Wachtwoord wijzigen</title>
</head>
<body>
    <h1>Wachtwoord wijzigen</h1>
    <form method="POST">
        <input type="password" name="current_password" placeholder="Huidig wachtwoord" required>
        <input type="password" name="new_password" placeholder="Nieuw wachtwoord" required>
        <button type="submit">Wijzigen</button>
    </form>
    <a href="index.php">Terug naar de beveiligde pagina</a>
</body>
</html>