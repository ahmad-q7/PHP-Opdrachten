<?php
require_once 'config.php';  // Hier halen we de database instellingen op

// Functie om ALLE brouwers te pakken
function getAllBrouwers() {
    global $pdo;  // Pak de database connectie
    $stmt = $pdo->query("SELECT * FROM brouwer");  // Vraag alles aan uit de brouwer tabel
    return $stmt->fetchAll();  // Geef alle resultaten terug
}

// Functie om 1 specifieke brouwer te pakken via ID
function getBrouwerById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM brouwer WHERE brouwcode = ?");  // Veilige query
    $stmt->execute([$id]);  // Voer uit met het ID
    return $stmt->fetch();  // Geef 1 resultaat terug
}

// Functie om nieuwe brouwer toe te voegen
function createBrouwer($naam, $land) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO brouwer (naam, land) VALUES (?, ?)");  // Veilige query
    return $stmt->execute([$naam, $land]);  // Voer uit en geef true/false terug
}

// Functie om brouwer aan te passen
function updateBrouwer($id, $naam, $land) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE brouwer SET naam = ?, land = ? WHERE brouwcode = ?");
    return $stmt->execute([$naam, $land, $id]);  // Voer uit met nieuwe data
}

// Functie om brouwer te verwijderen
function deleteBrouwer($id) {
    global $pdo;
    
    // Eerst checken of er bieren van deze brouwer zijn
    $stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM bier WHERE brouwcode = ?");
    $stmt->execute([$id]);
    $result = $stmt->fetch();
    
    // Als er bieren zijn, mag je niet verwijderen
    if ($result['count'] > 0) {
        return "Deze brouwer kan niet worden verwijderd omdat hij in gebruik is";
    }
    
    // Anders verwijderen
    $stmt = $pdo->prepare("DELETE FROM brouwer WHERE brouwcode = ?");
    return $stmt->execute([$id]) ? true : false;  // Geef true terug als het gelukt is
}

// Functie om door te sturen naar andere pagina
function redirect($url) {
    header("Location: $url");  // Stuur door naar andere pagina
    exit();  // Stop met de rest van de code
}
?>