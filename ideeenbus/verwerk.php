<?php
// Functie om scheldwoorden te vervangen met ***
function filterScheldwoorden($text) {
    $scheldwoorden = ['klootzak', 'eikel']; // Woorden die niet zijn toegestaan
    foreach ($scheldwoorden as $woord) {
        $text = str_ireplace($woord, '***', $text); // Vervang elk fout woord
    }
    return $text;
}

// Maak verbinding met de database
$pdo = new PDO("mysql:host=localhost;dbname=ideeenbus", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Controleer of het formulier is verstuurd
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Haal de gegevens op uit het formulier
    $naam = htmlspecialchars($_POST['naam']);
    $email = htmlspecialchars($_POST['email'] ?? '');
    $titel = htmlspecialchars($_POST['titel']);
    $bericht = htmlspecialchars($_POST['bericht']);

    // Verwijder scheldwoorden uit het bericht
    $bericht = filterScheldwoorden($bericht);

    // Sla het idee op in de database
    $stmt = $pdo->prepare("INSERT INTO ideeen (naam, email, titel, bericht) VALUES (?, ?, ?, ?)");
    $stmt->execute([$naam, $email, $titel, $bericht]);

    // Stuur de gebruiker terug naar het formulier
    header("Location: index.php");
    exit;
}
?>
