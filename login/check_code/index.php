<?php
session_start();

$PAGE_NAME = "Potwierdź kod";

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

if (isset($_SESSION["auth"]) && $_SESSION["auth"] == true) {
  header("Location: /");
  die();
}

if (!isset($_SESSION["code"])) {
  header("Location: /login/");
  die();
}


$codeErr = "";
if (isset($_POST["code"])) {
  $code = test_input($_POST["code"]);
  if ($code == $_SESSION["code"]) {
    $_SESSION["auth"] = true;
    header("Location: /");
  } else {
    $_SESSION["code_counter"] = intval($_SESSION["code_counter"]) - 1;
    $codeErr = "Zły kod! Zostało ci " . $_SESSION["code_counter"] . " prób.";
  }

  if ($_SESSION["code_counter"] <= 0) {
    session_destroy();
    header("Location: /login/");
    die();
  }
}

?>

<!doctype html>
<html>
<head>
  <title>Łącznik - <?= $PAGE_NAME ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="/styles/global.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="/styles/login.css?v=<?php echo time(); ?>">
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
  <main>
    <form id="kod" method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
      <div>
        <div id="status"><?= $codeErr ?></div>
        <label for="code">Kod: </label>
        <input type="number" id="code" name="code" min="100000" max="999999" placeholder="Wpisz kod...">
      </div>
      <button type="submit">Wyślij</button>
  </form>
  </main>
  <footer><h3 id="stopa">"Łączność to przyszłość!!"</h3></footer>
</div>
</body>
</html>

