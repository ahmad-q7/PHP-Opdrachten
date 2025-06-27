<?php
// Functie om speciale codes zoals [b]vet[/b] om te zetten in HTML (zodat het mooi wordt weergegeven)
function parseBBCode($text) {
    // [b]tekst[/b] → vetgedrukte tekst
    $text = preg_replace('/\[b\](.*?)\[\/b\]/i', '<strong>$1</strong>', $text);
    // [i]tekst[/i] → schuingedrukte tekst
    $text = preg_replace('/\[i\](.*?)\[\/i\]/i', '<em>$1</em>', $text);
    // [color=rood]tekst[/color] → gekleurde tekst
    $text = preg_replace('/\[color=(.*?)\](.*?)\[\/color\]/i', '<span style="color:$1">$2</span>', $text);
    // [size=20]tekst[/size] → tekst met grootte 20 pixels
    $text = preg_replace('/\[size=(\d+)\](.*?)\[\/size\]/i', '<span style="font-size:$1px">$2</span>', $text);

    // Vervang :) door smiley-afbeelding
    $text = str_replace(':)', '<img src="smileys/smile.png" alt=":)" />', $text);
    // Vervang :( door verdrietige smiley
    $text = str_replace(':(', '<img src="smileys/sad.png" alt=":(" />', $text);
    // Vervang :o door verraste smiley
    $text = str_replace(':o', '<img src="smileys/surprised.png" alt=":o" />', $text);

    // Zorg dat nieuwe regels zichtbaar blijven
    return nl2br($text);
}

// Maak verbinding met de database
$pdo = new PDO("mysql:host=localhost;dbname=ideeenbus", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Haal alle ideeën op, nieuwste bovenaan
$stmt = $pdo->query("SELECT * FROM ideeen ORDER BY datum DESC");

// Laat elk idee zien
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo '<div class="idea">';
    echo '<h3>' . htmlspecialchars($row['titel']) . '</h3>'; // Titel
    echo '<p><strong>Van:</strong> ' . htmlspecialchars($row['naam']) . ' <em>(' . $row['datum'] . ')</em></p>'; // Naam en datum
    echo '<p>' . parseBBCode($row['bericht']) . '</p>'; // Bericht met opmaak en smileys
    echo '</div>';
}
?>
