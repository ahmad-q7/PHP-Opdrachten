<?php
require 'config.php';
$id = (int)$_GET['id'];
$conn->query("DELETE FROM news WHERE id = $id");
echo "Verwijderd!";
?>
