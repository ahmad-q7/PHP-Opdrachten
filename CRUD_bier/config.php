<?php
// Auteur: AHMAD SALEH

// We definiëren de naam van de database die we gaan gebruiken. 
// Dit is de naam van de database waarin we gegevens willen opslaan.
define("DATABASE", "bieren");

// Hier stellen we de naam in van de server waar de database draait. 
// "localhost" betekent dat de database op dezelfde computer draait als het script.
define("SERVERNAME", "localhost");

// Dit is de gebruikersnaam die wordt gebruikt om verbinding te maken met de database.
// Vaak is dit "root" in lokale ontwikkelomgevingen.
define("USERNAME", "root");

// Dit is het wachtwoord dat hoort bij de gebruikersnaam om verbinding te maken met de database.
// In dit geval is er geen wachtwoord ingesteld, dus het is een lege string ("").
define("PASSWORD", "");

// Hier definieer je de naam van de tabel die je gebruikt in je database voor de CRUD-bewerkingen.
// CRUD staat voor Create, Read, Update, Delete (maken, lezen, bijwerken, verwijderen).
define("CRUD_TABLE", "brouwer");

// Dit is de primaire sleutel van de tabel. Een primaire sleutel is een uniek kenmerk dat 
// elke rij in de tabel identificeert. In dit geval gebruiken we "biercode" als unieke identifier.
define("PRIMARY_KEY", "biercode");

?>
