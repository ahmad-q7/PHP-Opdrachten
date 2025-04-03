<?php 
// Controleer of er al een sessie is gestart. Zo niet, start een nieuwe sessie.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Definieer de databaseverbinding parameters
$host = "localhost";  // De server waarop de database draait (meestal 'localhost')
$user = "root";       // Gebruikersnaam voor de database (meestal 'root' bij lokale ontwikkelomgevingen)
$pass = "";           // Wachtwoord voor de database (leeg in dit geval, kan nodig zijn voor andere omgevingen)
$dbname = "gastenboek";  // De naam van de database waar we mee willen verbinden

// Maak een nieuwe verbinding met de MySQL-database met behulp van de bovenstaande parameters
$conn = new mysqli($host, $user, $pass, $dbname);

// Controleer of de verbinding succesvol was, anders geef een foutmelding weer
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);  // Stop de scriptuitvoering als de verbinding mislukt
}

// Functie om gebruikersinvoer veilig te maken door ongewenste tekens te verwijderen
function sanitize($data) {
    // Verwijder HTML-tags en voer een trim uit om extra spaties te verwijderen
    return htmlspecialchars(strip_tags(trim($data)));
}
?>
