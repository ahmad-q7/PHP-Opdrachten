<?php
require 'config.php';  // Database connectie

// Check of poll ID is meegegeven
if (!isset($_GET['id'])) {
    header("Location: beheer.php");
    exit;
}

$poll_id = $_GET['id'];
// Haal de poll op
$poll = $pdo->prepare("SELECT * FROM polls WHERE id = ?");
$poll->execute([$poll_id]);
$poll = $poll->fetch();

// Als poll niet bestaat, terug naar beheer
if (!$poll) {
    header("Location: beheer.php");
    exit;
}

// Haal opties voor deze poll
$options = $pdo->prepare("SELECT * FROM options WHERE poll_id = ?");
$options->execute([$poll_id]);
$options = $options->fetchAll();

$errors = [];  // Voor foutmeldingen

// Check of form is ingevuld
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = trim($_POST['question']);  // Nieuwe vraag
    $new_options = array_map('trim', $_POST['options']);  // Nieuwe opties
    $existing_option_ids = $_POST['option_ids'];  // IDs van bestaande opties
    
    // Validatie
    if (empty($question)) {
        $errors[] = 'Vraag is verplicht';
    }
    
    // Filter lege opties
    $valid_options = array_filter($new_options, function($opt) {
        return !empty($opt);
    });
    
    if (count($valid_options) < 2) {
        $errors[] = 'Minstens 2 opties zijn verplicht';
    }
    
    if (empty($errors)) {
        // Update de poll vraag
        $stmt = $pdo->prepare("UPDATE polls SET question = ? WHERE id = ?");
        $stmt->execute([$question, $poll_id]);
        
        // Werk opties bij
        foreach ($existing_option_ids as $index => $option_id) {
            if (!empty($new_options[$index])) {
                if ($option_id > 0) {
                    // Bestaande optie updaten
                    $stmt = $pdo->prepare("UPDATE options SET option_text = ? WHERE id = ?");
                    $stmt->execute([$new_options[$index], $option_id]);
                } else {
                    // Nieuwe optie toevoegen
                    $stmt = $pdo->prepare("INSERT INTO options (poll_id, option_text) VALUES (?, ?)");
                    $stmt->execute([$poll_id, $new_options[$index]]);
                }
            } elseif ($option_id > 0) {
                // Optie verwijderen als leeg
                $stmt = $pdo->prepare("DELETE FROM options WHERE id = ?");
                $stmt->execute([$option_id]);
            }
        }
        
        header("Location: beheer.php");  // Terug naar beheer
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <!-- Standaard HTML -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poll Bewerken</title>
    <style>
        /* CSS voor formulier */
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .error { color: red; margin-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"] { width: 100%; padding: 8px; }
        button { padding: 8px 15px; }
        .add-option { margin-top: 10px; }
    </style>
</head>
<body>
    <h1>Poll bewerken</h1>
    
    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?= $error ?></p>  <!-- Toon errors -->
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <form method="post">
        <div class="form-group">
            <label for="question">Vraag:</label>
            <input type="text" id="question" name="question" value="<?= htmlspecialchars($poll['question']) ?>" required>
        </div>
        
        <div class="form-group">
            <label>Opties (minimaal 2):</label>
            <?php foreach ($options as $option): ?>
                <input type="hidden" name="option_ids[]" value="<?= $option['id'] ?>">  <!-- Verstopte ID -->
                <input type="text" name="options[]" value="<?= htmlspecialchars($option['option_text']) ?>" required>
            <?php endforeach; ?>
            <?php 
            // Voeg lege velden toe tot minimaal 4
            $remaining = 4 - count($options);
            for ($i = 0; $i < $remaining; $i++): ?>
                <input type="hidden" name="option_ids[]" value="0">  <!-- Nieuwe opties hebben ID 0 -->
                <input type="text" name="options[]" value="">
            <?php endfor; ?>
            <button type="button" class="add-option" onclick="addOption()">+ Optie toevoegen</button>  <!-- JS voor meer opties -->
        </div>
        
        <button type="submit">Opslaan</button>
    </form>
    
    <script>
        let optionCount = <?= count($options) + $remaining ?>;  // Huidig aantal opties
        
        function addOption() {
            const formGroup = document.querySelector('.form-group');
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'option_ids[]';
            hiddenInput.value = '0';  // Nieuwe optie ID
            
            const newInput = document.createElement('input');
            newInput.type = 'text';
            newInput.name = 'options[]';
            
            formGroup.insertBefore(hiddenInput, document.querySelector('.add-option'));
            formGroup.insertBefore(newInput, document.querySelector('.add-option'));
            optionCount++;  // Verhoog de teller
        }
    </script>
    
    <p><a href="beheer.php">Terug naar beheer</a></p>
</body>
</html>