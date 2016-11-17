<?php
include "functions.php";
$action = $_POST["action"];

if (isset($_SESSION["user"]) && $_SESSION["user"] != "") $user = $_SESSION["user"];
else $user = "";

if($action == "func"){
  echo func();
}


?>
