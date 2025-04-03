<?php
// Include de configuratie om toegang te krijgen tot de databaseverbinding (indien nodig)
include 'config.php';

// Zeg de sessie vaarwel door de sessie te beëindigen
session_destroy();

// Stuur de gebruiker door naar de loginpagina nadat de sessie is beëindigd
header("Location: login.php");
exit();  // Stop de uitvoering van het script na het doorsturen
?>
