<?php
// Inclusie van het bestand 'functions.php' waar alle database gerelateerde functies staan
require_once('functions.php');

// Controleer of het formulier is verzonden door te kijken of de knop 'btn_wzg' is aangeklikt
if(isset($_POST['btn_wzg'])) {
    // Als de update succesvol is, stuur de gebruiker terug naar de indexpagina
    if(updateRecord($_POST)) {
        header("Location: index.php");
        exit;
    } else {
        // Als de update mislukt is, geef een foutmelding via een JavaScript alert
        echo '<script>alert("Bier is NIET gewijzigd")</script>';
    }
}

// Controleer of er een biercode is meegegeven in de URL via GET-verzoek
if(isset($_GET['biercode'])) {  
    // Haal de biercode op uit de URL
    $biercode = $_GET['biercode'];
    
    // Haal de gegevens van het bier op dat overeenkomt met de biercode
    $row = getRecord($biercode);
    
    // Haal de lijst van brouwers op voor de select dropdown
    $brouwers = getBrouwers();
    
    // Als er geen bier gevonden is met deze biercode, stop dan het script en toon een foutmelding
    if(!$row) {
        die("Bier niet gevonden met biercode: " . $biercode);
    }
} else {
    // Als er geen biercode is opgegeven in de URL, stop dan het script en toon een foutmelding
    die("Geen biercode opgegeven");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Wijzig Bier</title>
    <!-- Koppel de externe stylesheet aan de pagina voor de opmaak -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Wijzig Bier</h1>
    
    <!-- Begin van het formulier voor het wijzigen van het bier -->
    <form method="post">
        <!-- Verberg de biercode in een hidden input, zodat het meegestuurd wordt bij het formulier -->
        <input type="hidden" name="biercode" value="<?= $row['biercode'] ?>">

        <!-- Veld voor de naam van het bier, met de bestaande naam als waarde -->
        <label for="naam">Naam:</label>
        <input type="text" id="naam" name="naam" value="<?= htmlspecialchars($row['naam']) ?>" required><br>

        <!-- Veld voor het soort bier, met het bestaande soort als waarde -->
        <label for="soort">Soort:</label>
        <input type="text" id="soort" name="soort" value="<?= htmlspecialchars($row['soort']) ?>" required><br>

        <!-- Veld voor de stijl van het bier, met de bestaande stijl als waarde -->
        <label for="stijl">Stijl:</label>
        <input type="text" id="stijl" name="stijl" value="<?= htmlspecialchars($row['stijl']) ?>" required><br>

        <!-- Veld voor het alcoholpercentage van het bier, met het bestaande percentage als waarde -->
        <label for="alcohol">Alcohol %:</label>
        <input type="number" id="alcohol" name="alcohol" step="0.1" value="<?= htmlspecialchars($row['alcohol']) ?>" required><br>

        <!-- Dropdown menu voor het kiezen van de brouwer van het bier -->
        <label for="brouwcode">Brouwer:</label>
        <select id="brouwcode" name="brouwcode" required>
            <?php 
            // Vul de dropdown met brouwers uit de database, markeer de geselecteerde brouwer als 'selected'
            foreach($brouwers as $brouwer): ?>
                <option value="<?= $brouwer['brouwcode'] ?>" 
                    <?= ($row['brouwcode'] == $brouwer['brouwcode']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($brouwer['naam']) ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <!-- Submit knop om de wijzigingen op te slaan -->
        <input type="submit" name="btn_wzg" value="Wijzigingen opslaan">
    </form>
    
    <br>
    <!-- Link om terug te keren naar de overzichtspagina -->
    <a href='index.php'>Terug naar overzicht</a>
</body>
</html>
