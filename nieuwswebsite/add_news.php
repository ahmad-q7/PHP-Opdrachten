<?php
require 'auth_session.php';
require 'config.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Nieuws Toevoegen</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Nieuws Toevoegen</h1>
  <nav>
    <a href="index.php">Home</a> |
    <a href="logout.php">Uitloggen</a>
  </nav>
  <?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $title = $conn->real_escape_string($_POST['title']);
      $content = $conn->real_escape_string($_POST['content']);
      $cat = (int)$_POST['category'];
      $user_id = $_SESSION['user'];
      $conn->query("INSERT INTO news (category_id, title, content, user_id) VALUES ($cat, '$title', '$content', $user_id)");
      echo "<p>Nieuws toegevoegd!</p>";
  }
  $result = $conn->query("SELECT * FROM categories");
  ?>
  <form method="post" autocomplete="off">
    <input type="text" name="title" placeholder="Titel" required autocomplete="off">
    <textarea name="content" placeholder="Inhoud" required autocomplete="off"></textarea>
    <select name="category">
      <?php while($row = $result->fetch_assoc()): ?>
        <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
      <?php endwhile; ?>
    </select>
    <button type="submit">Toevoegen</button>
  </form>
</body>
</html>
