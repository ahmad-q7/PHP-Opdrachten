<?php
require_once 'functions.php';  // Laad alle functies

// Als het formulier is verstuurd
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naam = $_POST['naam'];  // Pak de naam uit het formulier
    $land = $_POST['land'];  // Pak het land uit het formulier
    
    // Probeer brouwer toe te voegen
    if (createBrouwer($naam, $land)) {
        redirect('index.php');  // Gelukt? Ga terug naar overzicht
    } else {
        $error = "Oeps, er ging iets mis bij toevoegen!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Nieuwe Brouwer Toevoegen</title>
</head>
<body>
    <h1>Nieuwe Brouwer Toevoegen</h1>
    
    <?php if (isset($error)): ?>  <!-- Laat error zien als er een is -->
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    
    <form method="post">  <!-- Formulier om nieuwe brouwer toe te voegen -->
        <div>
            <label for="naam">Naam:</label>
            <input type="text" id="naam" name="naam" required>
        </div>
        <div>
            <label for="land">Land:</label>
            <input type="text" id="land" name="land" required>
        </div>
        <button type="submit">Opslaan</button>
    </form>
    <a href="index.php">Annuleren</a>  <!-- Terug naar overzicht -->
</body>
</html>