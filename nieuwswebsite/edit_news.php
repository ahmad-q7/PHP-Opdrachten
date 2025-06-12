<?php
require 'config.php';
$id = (int)$_GET['id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $cat = (int)$_POST['category'];
    $conn->query("UPDATE news SET title='$title', content='$content', category_id=$cat WHERE id=$id");
    echo "Bewerkt!";
}
$news = $conn->query("SELECT * FROM news WHERE id=$id")->fetch_assoc();
$categories = $conn->query("SELECT * FROM categories");
?>
<form method="post">
    <input type="text" name="title" value="<?= $news['title'] ?>"><br>
    <textarea name="content"><?= $news['content'] ?></textarea><br>
    <select name="category">
        <?php while($row = $categories->fetch_assoc()): ?>
        <option value="<?= $row['id'] ?>" <?= $news['category_id']==$row['id']?'selected':'' ?>><?= $row['name'] ?></option>
        <?php endwhile; ?>
    </select><br>
    <button type="submit">Opslaan</button>
</form>
