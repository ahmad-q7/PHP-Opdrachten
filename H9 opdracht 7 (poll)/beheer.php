<?php
require 'config.php';  // Database connectie

// Check of iemand een poll wil deleten
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM polls WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: beheer.php");  // Refresh de pagina
    exit;
}

// Haal alle polls op
$polls = $pdo->query("SELECT * FROM polls ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <!-- Standaard HTML -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poll Beheer</title>
    <style>
        /* CSS voor admin panel */
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .poll-list { margin-top: 20px; }
        .poll-item { padding: 10px; border-bottom: 1px solid #eee; }
        .actions { margin-top: 5px; }
        .actions a { margin-right: 10px; }
        .add-link { margin-top: 20px; display: inline-block; }
    </style>
</head>
<body>
    <h1>Vragen beheren</h1>
    
    <div class="poll-list">
        <?php foreach ($polls as $poll): ?>
            <div class="poll-item">
                <h3><?= htmlspecialchars($poll['question']) ?></h3>
                <div class="actions">
                    <!-- Links om polls te bewerken of deleten -->
                    <a href="bewerk.php?id=<?= $poll['id'] ?>">Bewerken</a>
                    <a href="beheer.php?delete=<?= $poll['id'] ?>" onclick="return confirm('Weet je zeker dat je deze poll wilt verwijderen?')">Verwijderen</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <a href="toevoegen.php" class="add-link">Nieuwe poll toevoegen</a>
    <br>
    <a href="index.php">Terug naar polls</a>
</body>
</html>