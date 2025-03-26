<?php
// Verbinding maken met de database
$host = "localhost";   // De database server (bij XAMPP is dit 'localhost')
$user = "root";        // Standaard gebruikersnaam in XAMPP
$pass = "";            // Standaard wachtwoord in XAMPP (leeg)
$dbname = "gastenboek"; // De naam van de database

$conn = new mysqli($host, $user, $pass, $dbname);

// Controleer of de verbinding is gelukt
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

// Controleer of het formulier is verstuurd voor een nieuw bericht
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["naam"]) && isset($_POST["bericht"])) {
    // Verkregen invoer veilig maken om SQL-injecties te voorkomen
    $naam = $conn->real_escape_string($_POST["naam"]);
    $bericht = $conn->real_escape_string($_POST["bericht"]);

    // Zorg dat naam en bericht niet leeg zijn
    if (!empty($naam) && !empty($bericht)) {
        // Voeg het bericht toe aan de database
        $sql = "INSERT INTO berichten (naam, bericht) VALUES ('$naam', '$bericht')";
        $conn->query($sql);
    }
}

// Controleer of een verwijderknop is ingedrukt
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["verwijder"])) {
    $id = intval($_POST["verwijder"]); // Zet het id om naar een geheel getal
    $sql = "DELETE FROM berichten WHERE id = $id"; // Verwijder het bericht met dit id
    $conn->query($sql);
}

// Haal alle berichten op uit de database, gesorteerd op datum (nieuwste eerst)
$sql = "SELECT * FROM berichten ORDER BY datumtijd DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gastenboek</title>
</head>
<body>
    <h2>Gastenboek</h2>

    <!-- Formulier om een nieuw bericht toe te voegen -->
    <form method="POST">
        <label>Naam:</label><br>
        <input type="text" name="naam" required><br><br> <!-- Eén regel tekstvak voor de naam -->

        <label>Bericht:</label><br>
        <textarea name="bericht" required></textarea><br><br> <!-- Groot tekstvak voor het bericht -->

        <input type="submit" value="Opslaan"> <!-- Knop om het formulier te verzenden -->
    </form>

    <hr>

    <!-- Hier worden de berichten weergegeven -->
    <h3>Berichten:</h3>
    <?php
    if ($result->num_rows > 0) {
        // Loop door alle berichten heen en toon ze
        while ($row = $result->fetch_assoc()) {
            echo "<p><strong>" . htmlspecialchars($row["naam"]) . "</strong> - " . $row["datumtijd"] . "<br>" . 
                 nl2br(htmlspecialchars($row["bericht"])) . "</p>";

            // Formulier voor de verwijderknop
            echo "<form method='POST' style='display:inline;'>
                    <input type='hidden' name='verwijder' value='" . $row["id"] . "'>
                    <input type='submit' value='Verwijderen' onclick='return confirm(\"Weet je zeker dat je dit bericht wilt verwijderen?\");'>
                  </form>
                  <hr>";
        }
    } else {
        // Als er nog geen berichten zijn
        echo "<p>Geen berichten gevonden.</p>";
    }
    
    // Sluit de databaseverbinding
    $conn->close();
    ?>
</body>
</html>
