<?php require 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Nieuws Categorieën</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Welkom bij de Nieuwswebsite</h1>
  <nav>
    <a href="index.php"> Home</a> |
    <a href="add_news.php"> Nieuws toevoegen</a>
  </nav>
  <form method="get" action="search.php" autocomplete="off">
    <input type="text" name="q" placeholder="Zoek nieuws..." autocomplete="off">
    <button type="submit">Zoeken</button>
  </form>
  <ul>
  <?php
    $result = $conn->query("SELECT * FROM categories");
    while($row = $result->fetch_assoc()):
  ?>
    <li><a href="news.php?cat=<?= $row['id'] ?>"><?= $row['name'] ?></a></li>
  <?php endwhile; ?>
  </ul>
</body>
</html>
