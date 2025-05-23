<?php
require 'config.php'; // Verbinding maken met de database

// Check of het formulier via POST is verstuurd
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Haal gegevens uit het formulier op
    $docent_id = $_POST['docent_id'];
    $datum = $_POST['datum'];
    $reden = $_POST['reden'];

    // Bereid een veilige SQL opdracht voor om gegevens toe te voegen
    $stmt = $conn->prepare("INSERT INTO ziekmeldingen (docent_id, datum, reden) VALUES (?, ?, ?)");
    
    // Voeg de gegevens toe aan de opdracht (i = integer, s = string)
    $stmt->bind_param("iss", $docent_id, $datum, $reden);
    
    // Voer de opdracht uit
    $stmt->execute();

    // Laat een bericht zien dat het gelukt is
    echo "Ziekmelding succesvol toegevoegd.";
}
?>
