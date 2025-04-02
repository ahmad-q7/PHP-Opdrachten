<?php
// De config.php wordt ingeladen zodat we de databaseverbinding kunnen gebruiken.
include_once "config.php";

// Functie om verbinding te maken met de database
function connectDb() {
    try {
        // Verbind met de MySQL database met behulp van PDO (PHP Data Objects).
        $conn = new PDO("mysql:host=".SERVERNAME.";dbname=".DATABASE, USERNAME, PASSWORD);
        
        // Stel attributen in voor foutafhandeling en standaard fetch-modus.
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Gooi een foutmelding bij problemen
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Haal gegevens als een associatief array op
        
        return $conn; // Geef de verbinding terug
    } catch(PDOException $e) {
        // Als de verbinding niet lukt, geef een foutmelding weer
        die("Connection failed: " . $e->getMessage());
    }
}

// Hoofdfunctie voor het beheren van CRUD-operaties (Create, Read, Update, Delete) voor bieren
function crudMain() {
    // Toon een koptekst en een link om een nieuw bier toe te voegen
    echo "<h1>Crud Bieren</h1>
          <nav>
              <a href='insert.php'>Toevoegen nieuw bier</a>
          </nav><br>";

    // Haal de bieren op uit de database
    $result = getData(CRUD_TABLE);
    
    // Print de data in een tabel
    printCrudTabel($result);
}

// Haal gegevens op uit de database voor de opgegeven tabel
function getData($table) {
    $conn = connectDb(); // Maak verbinding met de database
    
    // SQL-query om de bieren uit de database te halen met een LEFT JOIN om de naam van de brouwer op te halen
    $sql = "SELECT b.biercode, b.naam, b.soort, b.stijl, b.alcohol, 
                   b.brouwcode, br.naam as brouwernaam 
            FROM bier b 
            LEFT JOIN brouwer br ON b.brouwcode = br.brouwcode";

    try {
        // Bereid de query voor en voer deze uit
        $query = $conn->prepare($sql);
        $query->execute();
        
        // Haal alle resultaten op en geef deze terug
        return $query->fetchAll();
    } catch(PDOException $e) {
        // Als de query faalt, geef een foutmelding weer
        die("Query failed: " . $e->getMessage());
    }
}

// Haal alle brouwers op uit de database
function getBrouwers() {
    $conn = connectDb();
    
    // SQL-query om alle brouwers op te halen
    $sql = "SELECT brouwcode, naam FROM brouwer";
    $query = $conn->prepare($sql);
    $query->execute();
    
    return $query->fetchAll(); // Geef alle brouwers terug
}

// Haal een specifiek bier op uit de database op basis van de biercode
function getRecord($biercode) {
    $conn = connectDb();
    
    // SQL-query om een bier op te halen op basis van de biercode
    $sql = "SELECT * FROM bier WHERE biercode = :biercode";
    
    $query = $conn->prepare($sql);
    $query->execute([':biercode' => $biercode]); // Voer de query uit met de biercode
    
    return $query->fetch(); // Geef het resultaat terug
}

// Functie om de CRUD-tabel te printen met de opgehaalde data
function printCrudTabel($result) {
    if(empty($result)) {
        // Als er geen bieren zijn, geef een bericht weer
        echo "Geen bieren gevonden";
        return;
    }

    // Begin de tabel
    $table = "<table border='1' cellpadding='5' cellspacing='0'>";
    $headers = array_keys($result[0]); // Haal de kolomnamen uit de eerste rij
    
    // Voeg de tabelkop toe
    $table .= "<tr>";
    foreach($headers as $header) {
        if(!in_array($header, ['brouwcode', PRIMARY_KEY])) { // We willen de brouwcode en biercode niet tonen in de tabel
            $table .= "<th>" . ucfirst($header) . "</th>";
        }
    }
    $table .= "<th colspan='2'>Actie</th></tr>";

    // Voeg de gegevens van elk bier toe in de tabel
    foreach ($result as $row) {
        $table .= "<tr>";
        foreach ($row as $key => $cell) {
            if(!in_array($key, ['brouwcode', PRIMARY_KEY])) { // We willen de brouwcode en biercode niet tonen
                $table .= "<td>" . htmlspecialchars($cell) . "</td>"; // Veilig de inhoud afdrukken
            }
        }
        
        // Voeg de wijzig- en verwijderknoppen toe
        $table .= "<td>
            <form method='get' action='update.php'>  <!-- Changed to method='get' -->
                <input type='hidden' name='biercode' value='" . $row['biercode'] . "'>
                <button type='submit'>Wijzig</button>
            </form>
        </td>";

        $table .= "<td>
            <form method='get' action='delete.php'>  <!-- Changed to method='get' -->
                <input type='hidden' name='biercode' value='" . $row['biercode'] . "'>
                <button type='submit'>Verwijder</button>
            </form>
        </td>";

        $table .= "</tr>";
    }
    $table .= "</table>";
    echo $table; // Toon de tabel
}

// Functie om een record in de database bij te werken
function updateRecord($row) {
    $conn = connectDb();
    
    // SQL-query om een bier bij te werken
    $sql = "UPDATE bier SET 
                naam = :naam, 
                soort = :soort, 
                stijl = :stijl,
                alcohol = :alcohol,
                brouwcode = :brouwcode
            WHERE biercode = :biercode";

    try {
        $stmt = $conn->prepare($sql);
        return $stmt->execute([  // Voer de update uit
            ':naam' => $row['naam'],
            ':soort' => $row['soort'],
            ':stijl' => $row['stijl'],
            ':alcohol' => $row['alcohol'],
            ':brouwcode' => $row['brouwcode'],
            ':biercode' => $row['biercode']
        ]);
    } catch(PDOException $e) {
        // Als de update faalt, geef een foutmelding weer
        die("Update failed: " . $e->getMessage());
    }
}

// Functie om een nieuw bier toe te voegen
function insertRecord($post) {
    $conn = connectDb();
    
    // Controleer of alle verplichte velden zijn ingevuld
    $required_fields = ['naam', 'soort', 'stijl', 'alcohol', 'brouwcode'];
    foreach ($required_fields as $field) {
        if (!isset($post[$field]) || empty(trim($post[$field]))) {
            die("$field is vereist en mag niet leeg zijn");
        }
    }

    // SQL-query om een nieuw bier toe te voegen
    $sql = "INSERT INTO bier (naam, soort, stijl, alcohol, brouwcode)
            VALUES (:naam, :soort, :stijl, :alcohol, :brouwcode)";

    try {
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([  // Voeg het nieuwe bier toe
            ':naam' => trim($post['naam']),
            ':soort' => trim($post['soort']),
            ':stijl' => trim($post['stijl']),
            ':alcohol' => (float)$post['alcohol'],
            ':brouwcode' => (int)$post['brouwcode']
        ]);
        
        // Als het toevoegen lukt, redirect naar de hoofdpagina
        if ($result) {
            header("Location: index.php");
            exit;
        } else {
            die("Toevoegen van bier is mislukt");
        }
    } catch(PDOException $e) {
        // Als er een fout optreedt, geef de foutmelding weer
        die("Database fout: " . $e->getMessage());
    }
}

// Functie om een bier uit de database te verwijderen
function deleteRecord($biercode) {
    $conn = connectDb();
    
    // SQL-query om een bier te verwijderen op basis van de biercode
    $sql = "DELETE FROM bier WHERE biercode = :biercode";
    
    try {
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':biercode' => $biercode]); // Voer de delete-query uit
    } catch(PDOException $e) {
        // Als de delete faalt, geef een foutmelding weer
        die("Delete failed: " . $e->getMessage());
    }
}
?>
