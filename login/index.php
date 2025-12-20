<?php
session_start();

$PAGE_NAME = "Zaloguj";

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

$emailErr = "";
if (isset($_POST["email"])) {
  session_unset();
  $email = test_input($_POST["email"]);
  $domain = substr(strrchr($email, "@"), 1);

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $emailErr = "Zły format maila.";
  } elseif (strcasecmp($domain, "uczen.tl.krakow.pl") !== 0) {
    $emailErr = "Nie jesteś uczniem Łączności!";
  }

  if ($email != "" && $emailErr == "") {
    $_SESSION["email"] = $email;
    $_SESSION["code"] = rand(100000, 999999);
    $_SESSION["code_counter"] = 3;
    mail($email, "Auth Code", "Your Auth Code: " .  $_SESSION["code"]);
    error_log("Auth Code: " . $_SESSION["code"]);
    header("Location: /login/check_code/");
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
  <link rel="stylesheet" href="/styles/login.css?v=<?php echo time(); ?>">
</head>
<body>
  <div class="tlo"></div>
  <div id="app">
    <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
      <div>
        <div id="status"><?= $emailErr ?></div>
        <label for="email">Email: </label>
        <input type="email" id="email" name="email" placeholder="Wpisz swój email...">
      </div>
        <button type="submit">Wyślij</button>
    </form>
  </div>
</body>
</html>
