<?php
include "functions.php";
$action = $_POST["action"];

if($action == "get_exams"){
  echo get_exams();
}
if($action == "test"){
  echo json_encode(array("test"=>"test"));
}
if($action == "login"){
  echo login($_POST["user"], $_POST["pass"]);
}
if($action == "logout"){
  echo logout();
}

?>
