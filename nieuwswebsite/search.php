<?php require 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Zoekresultaten</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Zoekresultaten</h1>
  <nav>
    <a href="index.php">Home</a>
  </nav>
  <?php
  $q = $conn->real_escape_string($_GET['q']);
  $result = $conn->query("SELECT * FROM news WHERE title LIKE '%$q%' OR content LIKE '%$q%'");
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
          echo "<h2><a href='news.php?id={$row['id']}'>{$row['title']}</a></h2>";
          echo "<p>" . substr($row['content'], 0, 100) . "...</p>";
      }
  } else {
      echo "<p>Geen resultaten gevonden voor '<strong>$q</strong>'</p>";
  }
  ?>
</body>
</html>
