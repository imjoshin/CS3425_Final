<?php
require_once ('constants.php');
require_once ('connect.php');
ini_set("log_errors", 1);
ini_set('display_errors', '1');
ini_set("error_log", "/local/my_web_files/jocjohns/classdb/final/error.log");
error_reporting(E_ALL);
date_default_timezone_set("America/Detroit");

session_start();

function login($user, $pass){
  //find student
  $stmt = db_op("SELECT * FROM Student WHERE id = '" . test_input($user) . "' AND password = '" . test_input($pass) . "'");
  if($stmt->num_rows != 1){
    return json_encode(array("error"=>"Invalid user ID or password."));
  }
  //if user is found, get exams
  $_SESSION["user"] = $user;
  return get_exams();
}

function logout(){
  //simply destroy the session
  session_destroy();
  return json_encode(array());
}

function get_exams(){
  //if logged in
  if(isset($_SESSION["user"]) && strlen($_SESSION["user"]) > 0){
    $html = "<h1 id='header'>Available Exams</h1><br/>";

    //get exam name, instructor name, date created, points of taken exam, and total points
    $qstmt = db_op("SELECT Exam.name AS exam_name, Instructor.name AS instructor, Exam.date_created AS date, taken.points AS points, total.points AS total
                    FROM Exam
                    JOIN Instructor ON Exam.created_by = Instructor.id
                    JOIN
                    (
                    	SELECT exam_name, SUM(points) AS points FROM Question GROUP BY exam_name
                    ) AS total ON Exam.name = total.exam_name
                    LEFT JOIN (SELECT * FROM takes WHERE takes.s_id = '" . $_SESSION["user"] . "') taken ON Exam.name = taken.exam_name
                    ORDER BY date DESC");
    if(!$qstmt){
      return json_encode(array("error"=>"Unable to access database."));
    }

    $html .= "<div class='panel panel-default'>
                <table id='exam-table' class='table'>
                  <tr>
                    <th>Exam Name</th>
                    <th>Instructor</th>
                    <th>Score</th>
                    <th>Date Posted</th>
                  </tr>";
    //add each exam to the table
    while($row = $qstmt->fetch_array(MYSQLI_ASSOC)){
      $date = strtotime($row["date"]);
      $html .= "<tr class='" . ($row["points"] != null ? "closed" : "open") . "' data-exam='" . $row["exam_name"] . "'>
                    <td style='width:30%'>" . $row["exam_name"] . "</td>
                    <td style='width:30%'>" . $row["instructor"] . "</td>
                    <td style='width:20%'>" . ($row["points"] != null ? $row["points"] . "/" . $row["total"] : "Open") . "</td>
                    <td style='width:20%'>" . date("F d, Y", $date) . "</td>
                </tr>";
    }
    $html .= "</table></div>";
  }else{
    //not logged in
    $html = "<h3>You are not logged in!<br/>Please <a href='#login-modal' data-toggle='modal'>login</a> now.</h3>";
  }

  return json_encode(array("html"=>$html));
}

function get_questions($exam_name){
  $html = "<h1 id='header'>$exam_name</h1><br/>";

  $stmt = db_op("SELECT * FROM takes WHERE s_id = '" . $_SESSION["user"] . "' AND exam_name = '$exam_name'");

  //exam not taken
  if($stmt->num_rows != 1){
    //get all questions for this exam
    $qstmt = db_op("SELECT id, number, text, points FROM Question WHERE exam_name = '" . test_input($exam_name) . "' ORDER BY number ASC;");
    if(!$qstmt){
      return json_encode(array("error"=>"Unable to access database."));
    }

    //generate html for questions/answers
    while($qrow = $qstmt->fetch_array(MYSQLI_ASSOC)){
      $html .= "<div class='panel panel-default'>
                  <table class='question-table table'>";
      $html .= "<tr class='question'>
                  <td>" . $qrow["number"] . ". " . $qrow["text"] . "<label style='float:right'>" . $qrow["points"] . " pts</label></td>
                </tr>";
      $astmt = db_op("SELECT Answer.id, Answer.identifier, Answer.text
                      FROM Answer JOIN Question ON Answer.q_id = Question.id
                      WHERE Question.exam_name = '" . test_input($exam_name) . "' AND Answer.q_id = " . $qrow["id"] . " ORDER BY identifier ASC;");

      //get each answer
      while($arow = $astmt->fetch_array(MYSQLI_ASSOC)){
        $html .= "<tr><td><input data-id='" . $arow["id"] . "' type='radio' name='" . $qrow["id"] . "'> " . $arow["identifier"] . ". " . $arow["text"] . "</td></tr>";
      }
      $html .= "</table></div>";
    }

    $html .= "<button class='btn btn-success submit-btn'>Submit</button>";

  //exam taken, show results
  }else{
    $rstmt = db_op("SELECT takes.points, total.points AS total
                    FROM takes
                    JOIN
                    (
                    	SELECT exam_name, SUM(points) AS points FROM Question GROUP BY exam_name
                    ) AS total ON takes.exam_name = total.exam_name
                    WHERE takes.exam_name = 'Simple Math'");
    $rrow = $stmt->fetch_array(MYSQLI_ASSOC);

    $html = "<h1 id='header'>$exam_name</h1><br/>";
             //<h4>" . $rrow["points"] . "/" . $rrow["total"] . " (%)</h4><br/>";


    //get all questions for this exam
    $qstmt = db_op("SELECT id, number, text, points, correct_answer FROM Question WHERE exam_name = '" . test_input($exam_name) . "' ORDER BY number ASC;");
    if(!$qstmt){
      return json_encode(array("error"=>"Unable to access database."));
    }

    //generate html for questions/answers
    while($qrow = $qstmt->fetch_array(MYSQLI_ASSOC)){
      $html .= "<div class='panel panel-default'>
                  <table class='question-table table'>";

      //get answer information
      $stmt = db_op("SELECT student_answers.s_id, student_answers.q_id, student_answers.a_id, Answer.identifier
                     FROM student_answers join Answer on student_answers.a_id = Answer.id
                     WHERE student_answers.q_id = '" . $qrow["id"] . "'");
      $row = $stmt->fetch_array(MYSQLI_ASSOC);
      $student_correct = ($qrow["correct_answer"] == $row["identifier"]);

      //show question and points earned
      $html .= "<tr class='question'>
                  <td>" . $qrow["number"] . ". " . $qrow["text"] . "<label style='float:right'>" . ($student_correct ? $qrow["points"] : 0) . "/" . $qrow["points"] . " pts</label></td>
                </tr>";

      $astmt = db_op("SELECT Answer.id, Answer.identifier, Answer.text
                      FROM Answer JOIN Question ON Answer.q_id = Question.id
                      WHERE Question.exam_name = '" . test_input($exam_name) . "' AND Answer.q_id = " . $qrow["id"] . " ORDER BY identifier ASC;");

      //get each answer
      while($arow = $astmt->fetch_array(MYSQLI_ASSOC)){
        $class = "";
        if($arow["identifier"] == $qrow["correct_answer"]){
          $class = "correct";
        }else if($arow["id"] == $row["a_id"]){
          $class = "incorrect";
        }
        $html .= "<tr class='$class'><td>" . $arow["identifier"] . ". " . $arow["text"] . "</td></tr>";
      }
      $html .= "</table></div>";
    }
  }

  return json_encode(array("html"=>$html));
}

function submit_exam($answers, $exam_name){
  $points = 0;
  foreach($answers as $a){
    //separate q and a
    $answer = explode(",", $a);
    $q_id = $answer[0];
    $a_id = $answer[1];
    $stmt = db_op("SELECT Question.points, Answer.id as correct_id FROM Question
                   JOIN Answer ON Answer.identifier = Question.correct_answer AND Answer.q_id = Question.id
                   WHERE Question.id = $q_id");
    if(!$stmt){
      return json_encode(array("error"=>"Unable to access database."));
    }

    //create totals
    $row = $stmt->fetch_array(MYSQLI_ASSOC);
    if($a_id == $row["correct_id"]){
      $points += $row["points"];
    }
    //insert values
    $stmt = db_op("INSERT INTO student_answers VALUES (" . $_SESSION["user"] . ", $a_id, $q_id)");
    if(!$stmt){
      return json_encode(array("error"=>"Unable to submit."));
    }
  }
  //insert final score
  $stmt = db_op("INSERT INTO takes VALUES (" . $_SESSION["user"] . ", '$exam_name', $points)");

  $html = "<h4> You scored a $points.<br/>Please return to the <a href=''>exam page</a>.";
  return json_encode(array("html"=>$html));
}

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
