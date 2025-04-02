<?php
// Include het bestand 'functions.php' waarin alle databasefunctionaliteit en CRUD-operaties staan
include 'functions.php';

// Controleer of er een 'biercode' parameter in de URL zit (via GET-verzoek)
if(isset($_GET['biercode'])) {  
    // Als de biercode is ingesteld, probeer dan het record met die biercode te verwijderen
    if(deleteRecord($_GET['biercode'])) {
        // Als het verwijderen succesvol was, wordt de gebruiker doorgestuurd naar de indexpagina
        header("Location: index.php");
        exit; // Zorg ervoor dat de uitvoering van de script stopt na de redirect
    } else {
        // Als het verwijderen niet succesvol was, geef een alert weer in de browser
        echo '<script>alert("Bier is NIET verwijderd")</script>';
    }
}
?>
