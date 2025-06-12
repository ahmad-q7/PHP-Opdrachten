<?php
require 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->query("SELECT * FROM users WHERE username = '$username'");
    if ($check->num_rows > 0) {
        $error = " Gebruikersnaam bestaat al.";
    } else {
        $conn->query("INSERT INTO users (username, password) VALUES ('$username', '$password')");
        $_SESSION['user'] = $conn->insert_id;
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Registreren</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="overlay">
    <h1>Account aanmaken</h1>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post" autocomplete="off">
      <input type="text" name="username" placeholder="Gebruikersnaam" required autocomplete="off">
      <input type="password" name="password" placeholder="Wachtwoord" required autocomplete="new-password">
      <button type="submit">Registreren</button>
    </form>
    <p>Heb je al een account? <a href="login.php">Log hier in</a></p>
  </div>
</body>
</html>
