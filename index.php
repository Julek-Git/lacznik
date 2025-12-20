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

$sql = "select PST_TEXT as CONTENT, PST_CREATOR_MAIL as CREATOR from POSTS order by PST_ID desc";
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
  <link rel="stylesheet" href="/styles/index.css?v=<?php echo time(); ?>">
</head>
<body>
  <div id="app">
    <div class="header">
      <img src="/images/zsl1.jpg" alt="logo ZSŁ" style="width: 150px">
      <div class="guziki">
        <?php if (isset($_SESSION["auth"]) && $_SESSION["auth"] == true) { ?>
          <h3><span style="font-weight:normal;">Zalogowano jako: </span><i><u> <?= $_SESSION["email"] ?> </u></i></h3>
          <div id="guziki">
            <a href="/create" id="post">Udostępnij posta</a>
            <a href="/logout" id="wyl">Wyloguj</a>
            <?php } else { ?>
              <a href="/login" id="zal">Zaloguj</a>
              <?php } ?>
          </div>
      </div>
    </div>
    <div class="postlist">
      <?php foreach ($posts as $post) { ?>
        <div class="post">
          <h2 class="username"><img src="/images/profilowe.png" alt="zdj_prof" style="width:35px; height:35px; margin-right: 10px"><span style="align-self: center">@ <?= $post["CREATOR"] ?></span></h2>
          <p class="content"><?= $post["CONTENT"] ?></p>
        </div>
      <?php } ?>
    </div>
    <footer><h3 id="stopa">"Łączność to przyszłość!!"</h3></footer>
  </div>
</body>
</html>
