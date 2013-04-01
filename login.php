<?php

 error_reporting(E_ALL);
 ini_set('display_errors', '1');

include('database.php');

// *************************************************
//	User Authorization
$PHP_AUTH_USER = 'Fon'; $PHP_AUTH_PW = 'Fon1234'; // remote testing
// $PHP_AUTH_USER = $_SERVER['PHP_AUTH_USER'];  $PHP_AUTH_PW = $_SERVER['PHP_AUTH_PW'];
// $PHP_AUTH_USER = preg_replace("/[^0-9a-zA-Z]/","",$PHP_AUTH_USER);
// $PHP_AUTH_PW = preg_replace("/[^0-9a-zA-Z]/","",$PHP_AUTH_PW);
// 	$stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level > 7 and active='Y';";
// $auth = msquery($stmt, $db);
// 	$stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level > 6 and active='Y' and view_reports='1';";
// $reports_auth = msquery($stmt, $db);

//  if ( (strlen($PHP_AUTH_USER)<2) or (strlen($PHP_AUTH_PW)<2) or ( ($auth < 1 ) and ($reports_auth < 1) ) ) {
// 	Header("WWW-Authenticate: Basic realm=\"VICI-PROJECTS\"");
// 	Header("HTTP/1.0 401 Unauthorized");
// 	echo "Invalid Username/Password.";
// 	exit;
// }

?>