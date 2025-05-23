<?php
// Hier staan de gegevens om verbinding te maken met de database
$host = 'localhost';  // De server waar de database op staat (vaak localhost)
$user = 'root';       // Gebruikersnaam om in te loggen op de database
$pass = '';           // Wachtwoord voor de databasegebruiker
$db = 'ziekmeldingen';// Naam van de database die we gebruiken

// Maak een nieuwe verbinding met de database
$conn = new mysqli($host, $user, $pass, $db);

// Controleer of de verbinding goed is gegaan
if ($conn->connect_error) {
    // Stop met verder uitvoeren en laat een foutmelding zien
    die("Connectie mislukt: " . $conn->connect_error);
}
?>
