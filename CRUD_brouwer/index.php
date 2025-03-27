<?php
require_once 'functions.php';  // Laad alle functies

$brouwers = getAllBrouwers();  // Pak alle brouwers uit de database
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD_Brouwer</title>
    <style>
        /* Basic styling voor de tabel */
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>CRUD_Brouwers</h1>
    <a href="add.php">Nieuwe Brouwer Toevoegen</a>  <!-- Link naar toevoegpagina -->
    
    <table>
        <tr>
            <th>Code</th>
            <th>Naam</th>
            <th>Land</th>
            <th>Acties</th>
        </tr>
        <?php foreach ($brouwers as $brouwer): ?>  <!-- Loop door alle brouwers -->
        <tr>
            <!-- Laat brouwer info zien, veilig tegen hackers -->
            <td><?= htmlspecialchars($brouwer['brouwcode']) ?></td>
            <td><?= htmlspecialchars($brouwer['naam']) ?></td>
            <td><?= htmlspecialchars($brouwer['land']) ?></td>
            <td>
                <!-- Links om te bewerken/verwijderen -->
                <a href="edit.php?id=<?= $brouwer['brouwcode'] ?>">Bewerken</a> |
                <a href="delete.php?id=<?= $brouwer['brouwcode'] ?>" onclick="return confirm('Zeker weten?')">Verwijderen</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>