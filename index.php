<?php
session_start();

$PAGE_NAME = "Główna";

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "lacznik";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$posts = [];

$sql = "select PST_TEXT as CONTENT, PST_CREATOR_MAIL as CREATOR from POSTS";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $post = [];
    $post["CREATOR"] = substr($row["CREATOR"], 0, strpos($row["CREATOR"], "@"));
    $post["CONTENT"] = $row["CONTENT"];
    array_push($posts, $post);
  }
}

?>

<!doctype html>
<html>
<head>
  <title>Łącznik - <?= $PAGE_NAME ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="/styles/global.css">
  <link rel="stylesheet" href="/styles/index.css">
</head>
<body>
  <div id="app">
    <div>
    <?php if (isset($_SESSION["auth"]) && $_SESSION["auth"] == true) { ?>
      <p>Zalogowano jako: <?= $_SESSION["email"] ?> </p>
      <a href="/logout">Wyloguj</a>
      <a href="/create">Udostępnij posta</a>
    <?php } else { ?>
      <a href="/login">Zaloguj</a>
    <?php } ?>
    </div>
    <div class="postlist">
      <?php foreach ($posts as $post) { ?>
        <div class="post">
          <h2 class="username">@<?= $post["CREATOR"] ?></h2>
          <p class="content"><?= $post["CONTENT"] ?></p>
        </div>
      <?php } ?>
    </div>
  </div>
</body>
</html>
