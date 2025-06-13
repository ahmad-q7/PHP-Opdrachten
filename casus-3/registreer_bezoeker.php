<?php
$host = "localhost";
$dbname = "statistiekensysteem";
$username = "root"; 
$password = "";     

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    $land = "Nederland"; 
    $ip_adres = gethostbyname(gethostname());
    $provider = "Ziggo"; 
    $browser = $_SERVER['HTTP_USER_AGENT'];
    $datum_tijd = date("Y-m-d H:i:s");
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "Direct";

    $stmt = $pdo->prepare("INSERT INTO bezoekers (land, ip_adres, provider, browser, datum_tijd, referer)
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$land, $ip_adres, $provider, $browser, $datum_tijd, $referer]);

    echo "Bezoeker geregistreerd!";
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}
?>
