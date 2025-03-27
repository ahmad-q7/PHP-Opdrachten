<?php
// Hier zetten we alle instellingen voor de database (de plek waar alle data staat)
$host = 'localhost';  // Dit is de server, meestal gewoon 'localhost'
$db   = 'bieren';     // Naam van je database (bierencollectie)
$user = 'root';       // Gebruikersnaam voor de database (standaard 'root')
$pass = '';           // Wachtwoord (vaak leeg bij localhost)
$charset = 'utf8mb4'; // Zorgt dat alle tekens goed worden weergegeven (emoji's ook)

// Dit is de connectiestring voor de database
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Hier zetten we extra opties voor de connectie
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // Laat fouten zien als errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // Geeft data terug als array
    PDO::ATTR_EMULATE_PREPARES   => false,                   // Echte security tegen hackers
];

try {
    // Probeer verbinding te maken met de database
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Als het misgaat, laat dan de foutmelding zien
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>