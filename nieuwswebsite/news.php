<?php
require 'config.php';
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Nieuws</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <nav>
    <a href="index.php"> Home</a>
    <?php if (isset($_SESSION['user'])): ?>
      | <a href="logout.php"> Uitloggen (<?= $_SESSION['username'] ?>)</a>
    <?php else: ?>
      | <a href="login.php">Inloggen</a>
    <?php endif; ?>
  </nav>

<?php
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $conn->query("UPDATE news SET views = views + 1 WHERE id = $id");
    $result = $conn->query("SELECT n.*, u.username FROM news n LEFT JOIN users u ON n.user_id = u.id WHERE n.id = $id");
    $news = $result->fetch_assoc();
    echo "<h1>{$news['title']}</h1>";
    echo "<p>{$news['content']}</p>";
    echo "<p><strong>Bekeken:</strong> {$news['views']} keer</p>";
    echo "<p><strong>Gepubliceerd door:</strong> " . ($news['username'] ?? 'Onbekend') . "</p>";
    echo "<a href='tip_friend.php?id=$id'>Tip een vriend</a>";

    echo "<h2>Reacties</h2>";
    $comments = $conn->query("SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.news_id = $id ORDER BY c.created_at DESC");
    while ($c = $comments->fetch_assoc()) {
        echo "<p><strong>{$c['username']}:</strong> {$c['content']}</p>";
    }

    if (isset($_SESSION['user'])) {
        echo "
        <form method='post' autocomplete='off'>
            <textarea name='content' placeholder='Reactie'></textarea>
            <button type='submit'>Plaats reactie</button>
        </form>";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user'];
            $content = $conn->real_escape_string($_POST['content']);
            $conn->query("INSERT INTO comments(news_id, content, user_id) VALUES ($id, '$content', $user_id)");
            header("Location: news.php?id=$id");
        }
    } else {
        echo "<p>Log in om een reactie te plaatsen.</p>";
    }

} elseif (isset($_GET['cat'])) {
    $catId = (int)$_GET['cat'];
    $result = $conn->query("SELECT * FROM news WHERE category_id = $catId ORDER BY created_at DESC");
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<h2><a href='news.php?id={$row['id']}'>{$row['title']}</a></h2>";
            echo "<p>" . substr($row['content'], 0, 150) . "...</p>";
        }
    } else {
        echo "<p>Geen nieuws in deze categorie.</p>";
    }

} else {
    echo "<p>Geen categorie of nieuws-id opgegeven.</p>";
}
?>
</body>
</html>
