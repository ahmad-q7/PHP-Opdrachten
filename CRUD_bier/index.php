<!DOCTYPE html>
<html lang="nl">
<head>
    <!-- Definieer de karakterset van de pagina, zodat speciale tekens correct worden weergegeven -->
    <meta charset="UTF-8">
    
    <!-- Zorgt ervoor dat de pagina goed wordt weergegeven op mobiele apparaten -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Titel van de webpagina, die verschijnt op de tab in de browser -->
    <title>Crud Bieren</title>
    
    <!-- Koppel de externe CSS-stylesheet aan de pagina voor opmaak -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
    // Include de PHP-bestand 'functions.php' waarin de functies staan die we nodig hebben voor CRUD-operaties
    include 'functions.php';

    // Roep de functie 'crudMain' aan die de hoofdfunctionaliteit van de CRUD-applicatie uitvoert
    crudMain();
    ?>
</body>
</html>
