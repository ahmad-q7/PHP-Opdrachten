<?php
// Dit is de plek waar we de database connectie regelen, soort van de sleutel tot de data

$host = 'localhost';  // Hier woont de database, gewoon op je eigen pc
$db   = 'poll_systeem';  // Naam van de database, onze stembus
$user = 'root';  // Gebruikersnaam, de baas van de database
$pass = '';  // Wachtwoord, maar hier staat niks (gevaarlijk voor echte shit)
$charset = 'utf8mb4';  // Zo praten we tegen de database, met deze letters

// Maak de connectie string voor PDO (dat is de database helper)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opties voor de connectie, zodat alles smooth loopt
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // Gooi fouten in ons gezicht
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  // Geef data terug als associative array (handig)
    PDO::ATTR_EMULATE_PREPARES   => false,  // Echte prepared statements voor security
];

// Probeer verbinding te maken met de database
try {
    $pdo = new PDO($dsn, $user, $pass, $options);  // Hier gebeurt de magie
} catch (\PDOException $e) {
    // Als het fout gaat, schreeuw het van de daken
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>