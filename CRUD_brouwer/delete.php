<?php
require_once 'functions.php';

// Als er geen ID is, ga terug
if (!isset($_GET['id'])) {
    redirect('index.php');
}

$id = $_GET['id'];  // Pak het ID uit de URL
$result = deleteBrouwer($id);  // Probeer te verwijderen

if ($result === true) {
    // Gelukt? Ga terug
    redirect('index.php');
} else {
    // Laat error zien in popup en ga terug
    echo "<script>alert('$result'); window.location.href='index.php';</script>";
}
?>