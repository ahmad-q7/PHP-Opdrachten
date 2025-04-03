<?php
// Include de configuratie om toegang te krijgen tot de databaseverbinding
include 'config.php';

// Controleer of de gebruiker is ingelogd, anders doorsturen naar de loginpagina
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Stuur niet-ingelogde gebruikers naar de loginpagina
    exit();  // Stop de verdere uitvoering van het script
}

// Controleer of het formulier is ingediend via POST en of de 'id' parameter aanwezig is
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    // Verkrijg de bericht-ID en zet deze om naar een integer
    $berichtId = (int)$_POST['id'];

    // Als de gebruiker geen admin is, controleer dan of de gebruiker het bericht heeft geplaatst
    if (!$_SESSION['is_admin']) {
        // Haal de eigenaar-ID van het bericht op uit de database
        $stmt = $conn->prepare("SELECT gebruiker_id FROM berichten WHERE id = ?");
        $stmt->bind_param("i", $berichtId);  // Bind de bericht-ID aan de query
        $stmt->execute();  // Voer de query uit
        $eigenaarId = $stmt->get_result()->fetch_assoc()['gebruiker_id'];  // Verkrijg de eigenaar van het bericht
        
        // Als de ingelogde gebruiker niet de eigenaar is van het bericht, stuur dan terug naar de index
        if ($_SESSION['user_id'] != $eigenaarId) {
            header("Location: index.php");  // Gebruiker heeft geen rechten om het bericht te verwijderen
            exit();  // Stop de verdere uitvoering van het script
        }
    }

    // Als de gebruiker admin is, of de eigenaar van het bericht, voer dan de verwijderactie uit
    $stmt = $conn->prepare("DELETE FROM berichten WHERE id = ?");
    $stmt->bind_param("i", $berichtId);  // Bind de bericht-ID aan de query
    $stmt->execute();  // Voer de delete query uit
}

// Na het verwijderen van het bericht, stuur de gebruiker terug naar de indexpagina
header("Location: index.php");
exit();  // Stop de uitvoering van het script na de doorverwijzing
?>
