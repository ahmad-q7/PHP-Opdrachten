<?php 
// Include de configuratie om toegang te krijgen tot de databaseverbinding
include 'config.php';

// Controleer of het formulier is ingediend via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Haal de gebruikersnaam en het wachtwoord op uit het formulier en maak ze veilig
    $gebruikersnaam = sanitize($_POST['gebruikersnaam']);
    $wachtwoord = $_POST['wachtwoord'];

    // Bereid de SQL-query voor om de gebruiker op te halen op basis van de gebruikersnaam
    $stmt = $conn->prepare("SELECT * FROM gebruikers WHERE gebruikersnaam = ?");
    $stmt->bind_param("s", $gebruikersnaam);  // Bind de gebruikersnaam aan de query
    $stmt->execute();  // Voer de query uit
    $result = $stmt->get_result();  // Verkrijg het resultaat van de query
    
    // Als er precies één gebruiker met de opgegeven gebruikersnaam is
    if ($result->num_rows === 1) {
        // Haal de gebruiker gegevens op uit de database
        $user = $result->fetch_assoc();
        
        // Vergelijk het ingevoerde wachtwoord met het opgeslagen wachtwoord in de database
        if (password_verify($wachtwoord, $user['wachtwoord'])) {
            // Als het wachtwoord klopt, zet de sessievariabelen voor de ingelogde gebruiker
            $_SESSION['user_id'] = $user['id'];  // Sla de gebruikers-ID op in de sessie
            $_SESSION['is_admin'] = $user['is_admin'];  // Sla op of de gebruiker admin is

            // Stuur de gebruiker door naar de indexpagina (hoofdscherm)
            header("Location: index.php");
            exit();  // Stop de uitvoering van het script nadat de gebruiker is doorgestuurd
        }
    }

    // Als de inloggegevens ongeldig zijn, geef dan een foutmelding weer
    $error = "Ongeldige inloggegevens!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    
    <!-- Als er een fout is, toon de foutmelding -->
    <?php if(isset($error)) echo "<p>$error</p>"; ?>

    <!-- Loginformulier -->
    <form method="POST">
        <!-- Invoerveld voor gebruikersnaam -->
        <input type="text" name="gebruikersnaam" placeholder="Gebruikersnaam" required><br>
        
        <!-- Invoerveld voor wachtwoord -->
        <input type="password" name="wachtwoord" placeholder="Wachtwoord" required><br>
        
        <!-- Submit-knop om in te loggen -->
        <button type="submit">Inloggen</button>
    </form>

    <!-- Link naar registratiepagina voor gebruikers zonder account -->
    <p>Nog geen account? <a href="registratie.php">Registreer hier</a></p>
</body>
</html>
