<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>TCR Ideënbus</title>
    <link rel="stylesheet" href="style.css"> <!-- Link naar de opmaak van de pagina -->
</head>
<body>
    <h1>Deel jouw idee!</h1>

    <!-- Formulier waar mensen hun idee kunnen invullen -->
    <form action="verwerk.php" method="post">
        <!-- Naam is verplicht om in te vullen -->
        <label>Naam*:<br><input type="text" name="naam" required></label><br>
        <!-- E-mail is optioneel -->
        <label>E-mail:<br><input type="email" name="email"></label><br>
        <!-- Titel is verplicht -->
        <label>Titel*:<br><input type="text" name="titel" required></label><br>
        <!-- Het idee zelf is verplicht -->
        <label>Idee*:<br><textarea name="bericht" required></textarea></label><br>
        <button type="submit">Verstuur</button> <!-- Knop om op te sturen -->
    </form>

    <hr> <!-- Lijn tussen formulier en lijst -->

    <h2>📄Reeds ingediende ideeën</h2>
    
    <!-- Hier voegen we de code van toon.php toe -->
    <?php include 'toon.php'; ?>
</body>
</html>
