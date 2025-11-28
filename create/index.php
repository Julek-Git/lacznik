<?php
session_start();

$PAGE_NAME = "Utwórz posta";

if (!isset($_SESSION["auth"]) || $_SESSION["auth"] !== true) {
  echo "<h1>You should login first</h1>";
  die();
}

$createError = "";

if (isset($_POST["content"])) {
  $servername = "localhost";
  $username = "root";
  $password = "root";
  $dbname = "lacznik";

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $posts = [];

  $clean["content"] = mysqli_real_escape_string($conn, $_POST["content"]);
  $clean["creator"] = mysqli_real_escape_string($conn, $_SESSION["email"]);

  if (strlen($clean["content"]) > 300) {
    $createError = "Za długi post";
  }

  $sql = "insert into POSTS(PST_TEXT, PST_CREATOR_MAIL) values ('{$clean["content"]}', '{$clean["creator"]}')";
  $conn->query($sql);
  header("Location: /");
  die();
}
?>

<!doctype html>
<html>
<head>
  <title>Łącznik - <?= $PAGE_NAME ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="/styles/global.css">
  <link rel="stylesheet" href="/styles/createpost.css">
</head>
<body>
<div id="app">
  <div id="status"><?= $createError ?></div>
  <form method="post">
    <textarea id="content" name="content" maxlength="300" placeholder="Wpisz treść posta"></textarea>
    <button type="submit">Opublikuj</button>
  </form>
</div>
</body>
</html>