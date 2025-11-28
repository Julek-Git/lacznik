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

  <link rel="stylesheet" href="/styles/global.css">
  <link rel="stylesheet" href="/styles/login.css">
</head>
<body>
<div id="app">
  <div id="status"><?= $codeErr ?></div>
  <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
    <label for="code">Kod</label>
    <input type="number" id="code" name="code" min="100000" max="999999">
    <button type="submit">Wyślij</button>
  </form>
</div>
</body>
</html>

