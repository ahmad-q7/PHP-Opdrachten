<?php
require 'config.php';  // Database connectie

$errors = [];  // Hier komen alle foutmeldingen
$question = '';  // De poll vraag
$options = ['', '', '', ''];  // Standaard 4 lege opties

// Check of form is ingevuld
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = trim($_POST['question']);  // Haal de vraag op
    $options = array_map('trim', $_POST['options']);  // Haal alle opties op
    
    // Check of vraag is ingevuld
    if (empty($question)) {
        $errors[] = 'Vraag is verplicht';
    }
    
    // Filter lege opties eruit
    $valid_options = array_filter($options, function($opt) {
        return !empty($opt);
    });
    
    // Check of er minstens 2 opties zijn
    if (count($valid_options) < 2) {
        $errors[] = 'Minstens 2 opties zijn verplicht';
    }
    
    // Als geen errors, sla dan op
    if (empty($errors)) {
        // Sla de poll op
        $stmt = $pdo->prepare("INSERT INTO polls (question) VALUES (?)");
        $stmt->execute([$question]);
        $poll_id = $pdo->lastInsertId();  // Haal het ID van de nieuwe poll
        
        // Sla alle opties op
        $stmt = $pdo->prepare("INSERT INTO options (poll_id, option_text) VALUES (?, ?)");
        foreach ($valid_options as $option) {
            $stmt->execute([$poll_id, $option]);
        }
        
        header("Location: beheer.php");  // Ga terug naar beheer
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
    <title>Poll Toevoegen</title>
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
    <h1>Nieuwe poll toevoegen</h1>
    
    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?= $error ?></p>  <!-- Toon alle errors -->
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <form method="post">
        <div class="form-group">
            <label for="question">Vraag:</label>
            <input type="text" id="question" name="question" value="<?= htmlspecialchars($question) ?>" required>
        </div>
        
        <div class="form-group">
            <label>Opties (minimaal 2):</label>
            <?php for ($i = 0; $i < 4; $i++): ?>
                <input type="text" name="options[]" value="<?= htmlspecialchars($options[$i] ?? '') ?>" <?= $i < 2 ? 'required' : '' ?>>
            <?php endfor; ?>
            <button type="button" class="add-option" onclick="addOption()">+ Optie toevoegen</button>  <!-- JS voor meer opties -->
        </div>
        
        <button type="submit">Opslaan</button>
    </form>
    
    <script>
        let optionCount = 4;  // Beginnen met 4 opties
        
        function addOption() {
            const formGroup = document.querySelector('.form-group');
            const newInput = document.createElement('input');
            newInput.type = 'text';
            newInput.name = 'options[]';
            formGroup.insertBefore(newInput, document.querySelector('.add-option'));
            optionCount++;  // Verhoog de teller
        }
    </script>
    
    <p><a href="beheer.php">Terug naar beheer</a></p>
</body>
</html>