<?php
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $link = "http://localhost/news.php?id=$id";
    echo "<form method='post'>
        <input type='email' name='email' placeholder='Email van vriend'>
        <button type='submit'>Verstuur</button>
    </form>";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $to = $_POST['email'];
        $subject = "Bekijk dit nieuws!";
        $msg = "Ik vond dit nieuwsbericht interessant: $link";
        mail($to, $subject, $msg);
        echo "Verzonden!";
    }
}
?>
