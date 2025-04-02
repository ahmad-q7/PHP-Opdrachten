<?php
// We tonen een titel bovenaan de pagina
echo "<h1>Insert Bier</h1>";

// Include de functies die we nodig hebben voor CRUD-operaties
require_once('functions.php');

// Haal de lijst van brouwers op die we later in de select-lijst zullen tonen
$brouwers = getBrouwers();

// Controleer of het formulier is verzonden door te kijken naar de 'btn_ins' knop in de POST-verzoeken
if(isset($_POST['btn_ins'])){
    // Als het formulier is verzonden, probeer dan het bier toe te voegen via de insertRecord functie
    if(insertRecord($_POST)) {
        // Als het bier succesvol is toegevoegd, laat een succesmelding zien en stuur de gebruiker door naar de indexpagina
        echo "<script>alert('Bier is toegevoegd')</script>";
        echo "<script>location.replace('index.php');</script>";
    } else {
        // Als het toevoegen van het bier niet is gelukt, laat dan een foutmelding zien
        echo '<script>alert("Bier is NIET toegevoegd")</script>';
    }
}
?>
<html>
    <body>
    <!-- Begin van het formulier om een nieuw bier toe te voegen -->
    <form method="post">
    
        <!-- Input veld voor de naam van het bier -->
        <label for="naam">Naam:</label>
        <input type="text" id="naam" name="naam" required><br>

        <!-- Input veld voor het soort bier -->
        <label for="soort">Soort:</label>
        <input type="text" id="soort" name="soort" required><br>

        <!-- Input veld voor de stijl van het bier -->
        <label for="stijl">Stijl:</label>
        <input type="text" id="stijl" name="stijl" required><br> 

        <!-- Input veld voor het alcoholpercentage van het bier -->
        <label for="alcohol">Alcohol %:</label>
        <input type="number" id="alcohol" name="alcohol" step="0.1" required><br>

        <!-- Dropdown select voor het kiezen van de brouwer van het bier -->
        <label for="brouwcode">Brouwer:</label>
        <select id="brouwcode" name="brouwcode" required>
            <?php 
            // Vul de dropdown met brouwers uit de database
            foreach($brouwers as $brouwer): ?>
                <option value="<?= $brouwer['brouwcode'] ?>">
                    <?= htmlspecialchars($brouwer['naam']) ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <!-- Submit knop om het formulier in te dienen -->
        <input type="submit" name="btn_ins" value="Toevoegen">
    </form>
        
    <br><br>
    <!-- Link naar de homepagina -->
    <a href='index.php'>Home</a>
    </body>
</html>
