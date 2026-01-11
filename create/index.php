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

  <link rel="stylesheet" href="/styles/global.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="/styles/createpost.css?v=<?php echo time(); ?>">
</head>
<body>
  <div id="app">
    <div class="header">
      <a href="/"><img src="/images/logo_<?= rand(0, 1) ? "lancuch" : "nieskonczonosc" ?>.svg" alt="logo ZSŁ" style="width: 120px; height: 120px; margin: 10px;"></a>
      <div class="guziki">
        <?php if (isset($_SESSION["auth"]) && $_SESSION["auth"] == true) { ?>
          <h3><span style="font-weight:normal;">Zalogowano jako: </span><i><u> <?= $_SESSION["email"] ?> </u></i></h3>
          <div id="guziki">
            <a href="/create" id="post">Udostępnij posta</a>
            <a href="/logout" id="wyl">Wyloguj</a>
            <?php } else { ?>
              <div id="guziki"><a href="/login" id="zal">Zaloguj</a>
              <?php } ?>
          </div>
      </div>
    </div>
    <div id="status"><?= $createError ?></div>
    <main>
      <form method="post">
        <p>Podziel się swoimi przemyśleniami z innymi użytkownikami Łącznika i opublikuj swojego posta!</p>
        <textarea id="content" name="content" maxlength="300" placeholder="Wpisz treść posta"></textarea>
        <button type="submit">Opublikuj</button>
      </form>
    </main>
    <footer><h3 id="stopa">"Łączność to przyszłość!!"</h3></footer>
  </div>
</body>
</html>