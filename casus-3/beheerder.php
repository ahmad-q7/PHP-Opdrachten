<?php
$host = "localhost";
$dbname = "statistiekensysteem";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    $landFilter = isset($_GET['land']) ? $_GET['land'] : '';
    $maandFilter = isset($_GET['maand']) ? $_GET['maand'] : '';

    $query = "SELECT * FROM bezoekers WHERE 1=1";
    $params = [];

    if ($landFilter !== '') {
        $query .= " AND land = ?";
        $params[] = $landFilter;
    }

    if ($maandFilter !== '') {
        $query .= " AND MONTH(datum_tijd) = ?";
        $params[] = $maandFilter;
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    $resultaten = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Fout bij ophalen: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Beheerdersinterface</title>
</head>
<body>
    <h1>Filter Bezoekers</h1>
    <form method="get">
        Land: <input type="text" name="land" value="<?= htmlspecialchars($landFilter) ?>">
        Maand (1-12): <input type="number" name="maand" min="1" max="12" value="<?= htmlspecialchars($maandFilter) ?>">
        <button type="submit">Filter</button>
    </form>

    <h2>Resultaten</h2>
    <table border="1">
        <tr>
            <th>ID</th><th>Land</th><th>IP</th><th>Provider</th><th>Browser</th><th>Datum/Tijd</th><th>Referer</th>
        </tr>
        <?php foreach ($resultaten as $row): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['land'] ?></td>
                <td><?= $row['ip_adres'] ?></td>
                <td><?= $row['provider'] ?></td>
                <td><?= $row['browser'] ?></td>
                <td><?= $row['datum_tijd'] ?></td>
                <td><?= $row['referer'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
