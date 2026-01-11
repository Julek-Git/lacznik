<?php
session_start();
session_destroy();
header("Location: /");
echo "You should be redirected";
die();