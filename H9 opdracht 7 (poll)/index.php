<?php
require 'config.php';  // Haal de database connectie erbij

// Check of iemand heeft gestemd (POST request) en of alle info er is
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['poll_id']) && isset($_POST['option_id'])) {
    // Update de stemmen in de database (+1 bij de gekozen optie)
    $stmt = $pdo->prepare("UPDATE options SET votes = votes + 1 WHERE id = ? AND poll_id = ?");
    $stmt->execute([$_POST['option_id'], $_POST['poll_id']]);
    header("Location: index.php");  // Stuur ze terug naar de homepage
    exit;  // Stop hier, we zijn klaar
}

// Haal alle polls op uit de database, gesorteerd van nieuw naar oud
$polls = $pdo->query("SELECT * FROM polls ORDER BY created_at DESC")->fetchAll();

// Voor elke poll, haal de opties erbij
foreach ($polls as &$poll) {
    $stmt = $pdo->prepare("SELECT * FROM options WHERE poll_id = ?");
    $stmt->execute([$poll['id']]);
    $poll['options'] = $stmt->fetchAll();  // Stop de opties in de poll array
    
    // Bereken totaal aantal stemmen voor deze poll
    $poll['total_votes'] = array_sum(array_column($poll['options'], 'votes'));
}
unset($poll);  // Maak de reference weer schoon
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <!-- Standaard HTML shit -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poll Systeem</title>
    <style>
        /* CSS voor de mooiigheid */
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .poll { margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 20px; }
        .poll h2 { margin-top: 0; }
        .option { margin: 8px 0; }
        .results { margin-top: 20px; background: #f5f5f5; padding: 15px; border-radius: 5px; }
        .result-item { margin: 5px 0; }
        .progress-bar { height: 20px; background: #e0e0e0; border-radius: 3px; margin-top: 5px; }
        .progress { height: 100%; background: #4CAF50; border-radius: 3px; }
        .admin-link { margin-top: 30px; }
    </style>
</head>
<body>
    <h1>Poll</h1>

    <?php foreach ($polls as $poll): ?>
        <div class="poll">
            <h2><?= htmlspecialchars($poll['question']) ?></h2>  <!-- Toon de poll vraag -->
            
            <form method="post">
                <input type="hidden" name="poll_id" value="<?= $poll['id'] ?>">
                <?php foreach ($poll['options'] as $option): ?>
                    <div class="option">
                        <!-- Radio buttons voor elke optie -->
                        <input type="radio" name="option_id" id="option_<?= $option['id'] ?>" value="<?= $option['id'] ?>">
                        <label for="option_<?= $option['id'] ?>"><?= htmlspecialchars($option['option_text']) ?></label>
                    </div>
                <?php endforeach; ?>
                <button type="submit">Submit</button>  <!-- Stem knop -->
            </form>
            
            <?php if ($poll['total_votes'] > 0): ?>
                <div class="results">
                    <h3>Tussenstand</h3>
                    <?php foreach ($poll['options'] as $option): 
                        $percentage = round(($option['votes'] / $poll['total_votes']) * 100, 2);
                    ?>
                        <div class="result-item">
                            <?= htmlspecialchars($option['option_text']) ?>: 
                            <?= $option['votes'] ?> stemmen (<?= $percentage ?>%)
                            <div class="progress-bar">
                                <div class="progress" style="width: <?= $percentage ?>%"></div>  <!-- Progress bar voor visualisatie -->
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <div class="admin-link">
        <a href="beheer.php">Vragen beheren</a>  <!-- Link naar admin panel -->
    </div>
</body>
</html>