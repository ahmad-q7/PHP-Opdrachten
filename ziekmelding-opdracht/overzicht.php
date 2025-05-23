<?php
require 'config.php'; // Verbinding maken met de database

// Haal de zoekterm op als die er is, anders leeg
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Bereid een veilige SQL opdracht voor met een zoekfilter
$stmt = $conn->prepare("
    SELECT z.id, d.naam, z.datum, z.reden 
    FROM ziekmeldingen z
    JOIN docenten d ON z.docent_id = d.id
    WHERE d.naam LIKE ?
    ORDER BY z.datum DESC
");

// Voeg het zoekwoord toe met % voor 'zoek in tekst'
$like = "%$search%";
$stmt->bind_param("s", $like);

// Voer de opdracht uit
$stmt->execute();

// Pak het resultaat
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <title>Overzicht Ziekmeldingen</title>
  <!-- Verwijzing naar het externe CSS bestand -->
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
  <h2>Overzicht Ziekmeldingen</h2>

  <!-- Zoekformulier -->
  <form method="get" action="">
    <input type="text" name="search" placeholder="Zoek op docent..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Zoeken</button>
  </form>

  <!-- Tabel met resultaten -->
  <table>
    <tr>
      <th>Docent</th>
      <th>Datum</th>
      <th>Reden</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['naam']) ?></td>
      <td><?= htmlspecialchars($row['datum']) ?></td>
      <td><?= nl2br(htmlspecialchars($row['reden'])) ?></td>
    </tr>
    <?php endwhile; ?>
  </table>
</div>

</body>
</html>
