<?php
require 'calculator.php';
require 'database.php';

$calc = new Calculator();
$db = new Database();
$expression = '';
$result = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $expression = $_POST['expression'] ?? '';
    $result = $calc->calculate($expression);
    $db->saveCalculation($expression, $result);
    $expression = $result; 
}

$history = $db->getCalculations();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Case 4: uitgebreide rekenmachine</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Case 4: uitgebreide rekenmachine</h2>

<div class="calculator">
    <form method="POST">
        <input type="text" name="expression" id="display" value="<?= htmlspecialchars($expression) ?>" readonly><br>

        <div class="row">
            <button type="button" onclick="press('C')">C</button>
            <button type="button" onclick="press('(')">(</button>
            <button type="button" onclick="press(')')">)</button>
            <button type="button" onclick="press('/')">/</button>
        </div>
        <div class="row">
            <button type="button" onclick="press('7')">7</button>
            <button type="button" onclick="press('8')">8</button>
            <button type="button" onclick="press('9')">9</button>
            <button type="button" onclick="press('*')">*</button>
        </div>
        <div class="row">
            <button type="button" onclick="press('4')">4</button>
            <button type="button" onclick="press('5')">5</button>
            <button type="button" onclick="press('6')">6</button>
            <button type="button" onclick="press('-')">-</button>
        </div>
        <div class="row">
            <button type="button" onclick="press('1')">1</button>
            <button type="button" onclick="press('2')">2</button>
            <button type="button" onclick="press('3')">3</button>
            <button type="button" onclick="press('+')">+</button>
        </div>
        <div class="row">
            <button type="button" onclick="press('0')">0</button>
            <button type="button" onclick="press('.')">.</button>
            <button type="button" onclick="press('%')">Mod</button>
            <button type="button" onclick="press('=')">=</button>
        </div>
        <div class="row">
            <button type="button" onclick="press('sqrt(')">√</button>
            <button type="button" onclick="press('^')">^</button>
        </div>

        <input type="submit" id="submit" style="display:none">
    </form>
</div>

<h3>Vorige berekeningen</h3>
<ul>
    <?php foreach ($history as $item): ?>
        <li><?= htmlspecialchars($item['expression']) ?> = <?= htmlspecialchars($item['result']) ?></li>
    <?php endforeach; ?>
</ul>

<script>
    function press(val) {
        const display = document.getElementById('display');
        if (val === 'C') {
            display.value = '';
        } else if (val === '=') {
            document.getElementById('submit').click();
        } else {
            display.value += val;
        }
    }
</script>

</body>
</html>