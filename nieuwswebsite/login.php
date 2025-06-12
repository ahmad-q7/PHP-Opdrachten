<?php
require 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE username = '$username'");
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
        exit;
    } else {
        $error = " Ongeldige inloggegevens.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Inloggen</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="overlay">
    <h1>Inloggen</h1>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post" autocomplete="off">
      <input type="text" name="username" placeholder="Gebruikersnaam" required autocomplete="off">
      <input type="password" name="password" placeholder="Wachtwoord" required autocomplete="off">
      <button type="submit">Inloggen</button>
    </form>
    <p>Nog geen account? <a href="register.php"> Registreer hier</a></p>
  </div>
</body>
</html>
