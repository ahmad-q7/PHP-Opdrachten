<?php 
// Start de sessie zodat we toegang hebben tot de sessievariabelen
session_start();

// Include de configuratie om de databaseverbinding beschikbaar te maken
include 'config.php';

// Controleer of de gebruiker ingelogd is, anders doorsturen naar de inlogpagina
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();  // Stop de uitvoering van het script nadat de gebruiker wordt doorgestuurd
}

// Haal het bericht-ID op uit de URL (via GET), en zet het om naar een integer
$berichtId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Bereid de SQL-query voor om het bericht op te halen op basis van het bericht-ID
$stmt = $conn->prepare("SELECT * FROM berichten WHERE id = ?");
$stmt->bind_param("i", $berichtId);  // Bind het bericht-ID aan de query
$stmt->execute();  // Voer de query uit
$result = $stmt->get_result();  // Verkrijg het resultaat van de query

// Controleer of er een bericht is gevonden met het opgegeven ID
if ($result->num_rows === 0) {
    die("Bericht niet gevonden!");  // Stop de uitvoering en geef een foutmelding
}

// Haal de gegevens van het bericht op uit de database
$bericht = $result->fetch_assoc();

// Controleer of de ingelogde gebruiker het bericht kan bewerken:
// Alleen een admin of de gebruiker die het bericht heeft geplaatst kan het bewerken
if (!$_SESSION['is_admin'] && $_SESSION['user_id'] !== $bericht['gebruiker_id']) {
    header("Location: index.php");  // Doorsturen naar de indexpagina als de gebruiker geen rechten heeft
    exit();  // Stop de uitvoering van het script
}

$error = null;  // Variabele voor het opslaan van eventuele foutmeldingen
// Controleer of er een POST-aanvraag is (formulier verzonden) en dat het berichttekst-veld is ingevuld
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['berichttekst'])) {
    // Maak de ingevoerde tekst veilig door ongewenste tekens te verwijderen
    $nieuweTekst = sanitize($_POST['berichttekst']);
    
    // Bereid de SQL-query voor om het bericht te updaten
    $updateStmt = $conn->prepare("UPDATE berichten SET berichttekst = ? WHERE id = ?");
    $updateStmt->bind_param("si", $nieuweTekst, $berichtId);  // Bind de nieuwe tekst en het bericht-ID aan de query
    
    // Voer de update-query uit
    if ($updateStmt->execute()) {
        header("Location: index.php");  // Als de update succesvol is, stuur de gebruiker terug naar de indexpagina
        exit();  // Stop de uitvoering van het script
    } else {
        $error = "Er is een fout opgetreden bij het opslaan!";  // Zet een foutmelding als de update mislukt
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bewerk Bericht</title>
    <style>
        /* Stijl voor de container van het formulier */
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
        }
        /* Stijl voor de teruglink */
        .back-link {
            display: block;
            margin-bottom: 20px;
        }
        /* Stijl voor de textarea */
        textarea {
            width: 100%;
            height: 150px;
            padding: 10px;
            margin: 10px 0;
        }
        /* Stijl voor de foutmeldingen */
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Link om terug te gaan naar de indexpagina -->
        <a href="index.php" class="back-link">&larr; Terug naar gastenboek</a>
        <h2>Bericht bewerken</h2>
        
        <!-- Toon een foutmelding als er een fout is -->
        <?php if($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <!-- Formulier om het bericht te bewerken -->
        <form method="POST">
            <!-- Textarea voor de berichttekst, gevuld met de huidige tekst van het bericht -->
            <textarea name="berichttekst" required><?= htmlspecialchars($bericht['berichttekst']) ?></textarea>
            <div>
                <!-- Verzenden van het formulier -->
                <button type="submit">Opslaan</button>
                <!-- Link om de bewerking te annuleren en terug te gaan naar de indexpagina -->
                <a href="index.php">Annuleren</a>
            </div>
        </form>
    </div>
</body>
</html>
