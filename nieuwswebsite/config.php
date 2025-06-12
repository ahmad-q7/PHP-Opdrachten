<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'nieuwswebsite';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Databaseverbinding mislukt: " . $conn->connect_error);
}
?>
