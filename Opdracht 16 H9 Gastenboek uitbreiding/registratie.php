<?php 
// Include de configuratie om toegang te krijgen tot de databaseverbinding
include 'config.php';

// Controleer of het formulier is ingediend via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Haal de gebruikersnaam, e-mail en wachtwoord op uit het formulier en maak ze veilig
    $gebruikersnaam = sanitize($_POST['gebruikersnaam']);
    $email = sanitize($_POST['email']);
    $wachtwoord = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT);  // Wachtwoord veilig hashen

    // Controleer of de gebruikersnaam of e-mail al in de database bestaat
    $check = $conn->prepare("SELECT id FROM gebruikers WHERE gebruikersnaam = ? OR email = ?");
    $check->bind_param("ss", $gebruikersnaam, $email);  // Bind de gebruikersnaam en e-mail aan de query
    $check->execute();  // Voer de query uit
    if ($check->get_result()->num_rows > 0) {
        // Als de gebruikersnaam of e-mail al bestaat, geef een foutmelding weer
        $error = "Gebruikersnaam of e-mail bestaat al!";
    } else {
        // Als de gebruikersnaam en e-mail uniek zijn, voer dan de registratie uit
        $stmt = $conn->prepare("INSERT INTO gebruikers (gebruikersnaam, email, wachtwoord) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $gebruikersnaam, $email, $wachtwoord);  // Bind de waarden voor gebruikersnaam, e-mail en wachtwoord
        if ($stmt->execute()) {
            // Als de registratie succesvol is, stuur de gebruiker door naar de loginpagina
            header("Location: login.php");
            exit();  // Stop de uitvoering van het script nadat de gebruiker is doorgestuurd
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registratie</title>
</head>
<body>
    <h2>Registratie</h2>

    <!-- Als er een fout is (bijvoorbeeld wanneer de gebruikersnaam of e-mail al bestaat), toon de foutmelding -->
    <?php if(isset($error)) echo "<p>$error</p>"; ?>

    <!-- Registratieformulier -->
    <form method="POST">
        <!-- Invoerveld voor gebruikersnaam -->
        <input type="text" name="gebruikersnaam" placeholder="Gebruikersnaam" required><br>
        
        <!-- Invoerveld voor e-mail -->
        <input type="email" name="email" placeholder="E-mail" required><br>
        
        <!-- Invoerveld voor wachtwoord -->
        <input type="password" name="wachtwoord" placeholder="Wachtwoord" required><br>
        
        <!-- Submit-knop om te registreren -->
        <button type="submit">Registreer</button>
    </form>

    <!-- Link naar de loginpagina als de gebruiker al een account heeft -->
    <p>Al account? <a href="login.php">Login hier</a></p>
</body>
</html>
