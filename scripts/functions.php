<?php
require_once ('constants.php');
require_once ('connect.php');
ini_set("log_errors", 1);
ini_set('display_errors', '1');
error_reporting(E_ALL);
date_default_timezone_set("America/Detroit");

session_start();

function login($user, $pass){
  $user = preg_replace('/[^\w]/', '', $user);
  $pass = preg_replace('/[^\w]/', '', $pass);
  $stmt = db_op("SELECT * FROM Student WHERE id = '$user' AND password = '$pass'");
  if($stmt->num_rows != 1){
    return json_encode(array("error"=>"Invalid user ID or password."));
  }
  $_SESSION["user"] = $user;
  return get_exams();
}

function logout(){
  session_destroy();
  return json_encode(array());
}

function get_exams(){
  if(isset($_SESSION["user"]) && strlen($_SESSION["user"]) > 0){
    $qstmt = db_op("SELECT Exam.name AS exam_name, Instructor.name AS instructor, Exam.date_created AS date, taken.points AS points, total.points AS total
                    FROM Exam
                    JOIN Instructor ON Exam.created_by = Instructor.id
                    JOIN
                    (
                    	SELECT exam_name, SUM(points) AS points FROM Question GROUP BY exam_name
                    ) AS total ON Exam.name = total.exam_name
                    LEFT JOIN (SELECT * FROM takes WHERE takes.s_id = '1234') taken ON Exam.name = taken.exam_name
                    ORDER BY date DESC");
    if(!$qstmt){
      return json_encode(array("error"=>"Unable to access database."));
    }

    $html = "<div class='panel panel-default'>
              <table id='exam-table' class='table'>";
    while($row = $qstmt->fetch_array(MYSQLI_ASSOC)){
      $date = strtotime($row["date"]);
      $html .= "<tr class='" . ($row["points"] != null ? "closed" : "open") . "'>
                    <td style='width:30%'>" . $row["exam_name"] . "</td>
                    <td style='width:30%'>" . $row["instructor"] . "</td>
                    <td style='width:20%'>" . ($row["points"] != null ? $row["points"] : "~") . "/" . $row["total"] . "</td>
                    <td style='width:20%'>" . date("F d, Y", $date) . "</td>
                </tr>";
    }

    $html .= "</table></div>";
  }else{
    $html = "<h3>You are not logged in!<br/>Please <a href='#login-modal' data-toggle='modal'>login</a> now.</h3>";
  }

  return json_encode(array("html"=>$html));
}

function get_questions($exam_name){
  $qstmt = db_op("SELECT number, text, points FROM Question WHERE exam_name = '" . test_input($exam_name) . "' ORDER BY number ASC;");
  if(!$qstmt){
    return json_encode(array("error"=>"Unable to access database."));
  }

  $html = "";
  while($qrow = $qstmt->fetch_array(MYSQLI_ASSOC)){
    $astmt = db_op("SELECT Answer.identifier, Answer.text FROM Answer JOIN Question ON Answer.q_id = Question.id WHERE Question.exam_name = '" . test_input($exam_name) . "' AND Answer.q_id = " . $qrow["number"] . " ORDER BY identifier ASC;");
    $html .= $qrow["number"] . " " . $qrow["text"] . "<br/>";
    while($arow = $qstmt->fetch_array(MYSQLI_ASSOC)){
      $html .= $arow["identifier"] . " " . $arow["text"] . "<br/>";
    }
  }
  return json_encode(array("html"=>$html));
}

/*
FUTURE USE:

function login($username, $pass){
  $username = preg_replace('/[^\w]/', '', $username);
  $pass = preg_replace('/[^\w]/', '', $pass);
  $stmt = db_op("select address from users where username = '$username' and pass = '$pass'");
  if($stmt->num_rows != 1){
    return json_encode(array("error"=>"Invalid username or password."));
  }
  $row = $stmt->fetch_array(MYSQLI_ASSOC);
  $_SESSION["address"] = $row["address"];
  $_SESSION["user"] = $username;
  return json_encode(array());
}
*/

/*
  creates a connection and statement with the given query
*/
function db_op($query){
  $dbConn = db_connect();
  $stmt = $dbConn->query($query);
  return $stmt;
}

/*
  sanitizes input data to prevent injection
  use with $dbh->quote(data) if using directly in a SQL call
*/
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>
