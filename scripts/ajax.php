<?php
include "functions.php";
$action = $_POST["action"];

if($action == "get_exams"){
  echo get_exams();
}
if($action == "get_questions"){
  echo get_questions($_POST["exam_name"]);
}
if($action == "test"){
  echo json_encode(array("test"=>"test"));
}
if($action == "submit_exam"){
  echo submit_exam($_POST["answers"], $_POST["exam_name"]);
}
if($action == "login"){
  echo login($_POST["user"], $_POST["pass"]);
}
if($action == "logout"){
  echo logout();
}

?>
