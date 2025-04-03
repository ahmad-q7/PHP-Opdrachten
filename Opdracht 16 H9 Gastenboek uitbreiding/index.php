<?php 
// Start de sessie zodat we toegang hebben tot de sessievariabelen zoals 'user_id' en 'gebruikersnaam'
session_start();

// Include de configuratie om toegang te krijgen tot de databaseverbinding
include 'config.php';

// Controleer of de gebruiker ingelogd is, anders doorsturen naar de loginpagina
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();  // Stop de uitvoering van het script na doorsturen
}

// Als het formulier is ingediend, verwerk dan het bericht
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bericht'])) {
    // Maak de ingevoerde tekst veilig door ongewenste tekens te verwijderen
    $bericht = sanitize($_POST['bericht']);
    
    // Bereid de SQL-query voor om het nieuwe bericht in de database in te voegen
    $stmt = $conn->prepare("INSERT INTO berichten (gebruiker_id, berichttekst) VALUES (?, ?)");
    // Bind de gegevens van de gebruiker en het bericht aan de query
    $stmt->bind_param("is", $_SESSION['user_id'], $bericht);
    $stmt->execute();  // Voer de query uit om het bericht in te voegen
}

// Bereid de SQL-query voor om alle berichten op te halen, inclusief gebruikersnaam
$stmt = $conn->prepare("SELECT 
                        b.id, 
                        b.berichttekst, 
                        b.aanmaakdatum, 
                        u.id AS gebruiker_id,
                        u.gebruikersnaam 
                    FROM berichten b
                    JOIN gebruikers u ON b.gebruiker_id = u.id
                    ORDER BY b.aanmaakdatum DESC");
$stmt->execute();  // Voer de query uit om berichten op te halen
$result = $stmt->get_result();  // Verkrijg het resultaat van de query
$berichten = $result->fetch_all(MYSQLI_ASSOC);  // Zet het resultaat om in een array
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gastenboek</title>
    <style>
        /* Stijl voor de container van de pagina */
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        /* Stijl voor individuele berichten */
        .bericht {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        /* Stijl voor de knoppen (Bewerken, Verwijderen) */
        .knoppen {
            margin-top: 10px;
        }
        /* Algemene stijl voor knoppen */
        button {
            padding: 5px 15px;
            margin-right: 5px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 3px;
        }
        /* Hover-effect voor knoppen */
        button:hover {
            background-color: #45a049;
        }
        /* Stijl voor de tekstarea waar het bericht wordt ingevoerd */
        textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Welkomstbericht voor de ingelogde gebruiker -->
        <h2>Welkom, <?= htmlspecialchars($_SESSION['gebruikersnaam'] ?? 'Gast') ?></h2>
        
        <!-- Toon een indicatie dat de gebruiker als admin is ingelogd -->
        <?php if($_SESSION['is_admin'] ?? false): ?>
            <p style="color: #d32f2f;"><strong>Admin Modus</strong></p>
        <?php endif; ?>
        
        <div style="margin-bottom: 20px;">
            <!-- Link om uit te loggen -->
            <a href="logout.php">Uitloggen</a>
        </div>

        <!-- Formulier om een nieuw bericht toe te voegen -->
        <h3>Plaats een nieuw bericht:</h3>
        <form method="POST">
            <!-- Textarea om het bericht in te voeren -->
            <textarea name="bericht" required placeholder="Schrijf je bericht..."></textarea><br>
            <!-- Knop om het bericht in te dienen -->
            <button type="submit">Bericht plaatsen</button>
        </form>

        <!-- Toon alle berichten -->
        <h3>Alle berichten:</h3>
        <?php if(count($berichten) > 0): ?>
            <?php foreach($berichten as $bericht): ?>
                <div class="bericht">
                    <!-- Toon de gebruikersnaam en de aanmaakdatum van het bericht -->
                    <strong><?= htmlspecialchars($bericht['gebruikersnaam']) ?></strong>
                    <small>(<?= date('d-m-Y H:i', strtotime($bericht['aanmaakdatum'])) ?>)</small>
                    <!-- Toon de berichttekst, waarbij nieuwe regels worden omgezet naar <br> -->
                    <p><?= nl2br(htmlspecialchars($bericht['berichttekst'])) ?></p>
                    
                    <!-- Toon de knoppen voor bewerken en verwijderen als de gebruiker admin is of de eigenaar van het bericht -->
                    <?php if($_SESSION['is_admin'] || $_SESSION['user_id'] == $bericht['gebruiker_id']): ?>
                        <div class="knoppen">
                            <!-- Formulier om naar de bewerkingspagina te gaan -->
                            <form action="bewerk.php" method="GET" style="display: inline-block;">
                                <input type="hidden" name="id" value="<?= $bericht['id'] ?>">
                                <button type="submit">Bewerken</button>
                            </form>
                            
                            <!-- Formulier om het bericht te verwijderen -->
                            <form action="verwijder.php" method="POST" style="display: inline-block;">
                                <input type="hidden" name="id" value="<?= $bericht['id'] ?>">
                                <!-- Bevestigingspop-up bij het verwijderen -->
                                <button type="submit" onclick="return confirm('Weet je zeker dat je dit bericht wilt verwijderen?')">
                                    Verwijderen
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Bericht als er geen berichten zijn -->
            <p>Er zijn nog geen berichten geplaatst.</p>
        <?php endif; ?>
    </div>
</body>
</html>
