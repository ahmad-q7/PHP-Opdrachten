<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Beveiligde Pagina</title>
</head>
<body>
    <h1>Welkom, <?php echo $_SESSION['username']; ?></h1>
    <p>Dit is een beveiligde pagina.</p>
    <a href="change_password.php">Wachtwoord wijzigen</a>
    <a href="logout.php">Uitloggen</a>
</body>
</html>