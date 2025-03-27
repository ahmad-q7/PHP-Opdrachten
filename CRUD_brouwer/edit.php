<?php
require_once 'functions.php';

// Als er geen ID is, ga terug
if (!isset($_GET['id'])) {
    redirect('index.php');
}

$id = $_GET['id'];  // Pak het ID uit de URL
$brouwer = getBrouwerById($id);  // Haal de brouwer op

// Als brouwer niet bestaat, ga terug
if (!$brouwer) {
    redirect('index.php');
}

// Als formulier is verstuurd
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naam = $_POST['naam'];
    $land = $_POST['land'];
    
    // Probeer brouwer aan te passen
    if (updateBrouwer($id, $naam, $land)) {
        redirect('index.php');  // Gelukt? Ga terug
    } else {
        $error = "Oeps, er ging iets mis bij aanpassen!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Brouwer Bewerken</title>
</head>
<body>
    <h1>Brouwer Bewerken</h1>
    
    <?php if (isset($error)): ?>  <!-- Laat error zien als er een is -->
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    
    <form method="post">
        <div>
            <label for="naam">Naam:</label>
            <!-- Vul de velden met bestaande data -->
            <input type="text" id="naam" name="naam" value="<?= htmlspecialchars($brouwer['naam']) ?>" required>
        </div>
        <div>
            <label for="land">Land:</label>
            <input type="text" id="land" name="land" value="<?= htmlspecialchars($brouwer['land']) ?>" required>
        </div>
        <button type="submit">Opslaan</button>
    </form>
    <a href="index.php">Annuleren</a>  <!-- Terug naar overzicht -->
</body>
</html>