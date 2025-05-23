<?php
require 'config.php'; // Verbinding maken met de database

// Haal alle docenten op uit de database om in het formulier te tonen
$docenten = $conn->query("SELECT id, naam FROM docenten");
?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <title>Ziekmelding</title>
  <!-- Verwijzing naar het externe CSS bestand -->
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-container">
  <h2>Ziekmelding</h2>

  <!-- Formulier om een ziekmelding toe te voegen -->
  <form action="insert_melding.php" method="post">

    <label for="docent_id">Docent:</label>
    <select name="docent_id" required>
      <option value="">-- kies --</option>
      <?php while ($row = $docenten->fetch_assoc()): ?>
        <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['naam']) ?></option>
      <?php endwhile; ?>
    </select>

    <label for="datum">Datum:</label>
    <input type="date" name="datum" required>

    <label for="reden">Reden:</label>
    <textarea name="reden" required></textarea>

    <button type="submit">Versturen</button>
  </form>
</div>

</body>
</html>
