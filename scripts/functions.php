<?php
require_once ('constants.php');
require_once ('connect.php');
ini_set("log_errors", 1);
ini_set('display_errors', '1');
error_reporting(E_ALL);

function func(){

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
    sanitizes input data to prevent injection
    use with $dbh->quote(data) if using directly in a SQL call
*/
function testInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>
