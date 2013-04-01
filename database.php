<?php

 error_reporting(E_ALL);
 ini_set('display_errors', '1');

// $DB_server = 'localhost';
// $DB_port = '3306';
// $DB_user = 'cron';
// $DB_pass = '1234';
// $DB_database = 'asterisk';

// $db=mysql_connect("$DB_server:$DB_port", "$DB_user", "$DB_pass");
// mysql_select_db("$DB_database");

// connect from local
shell_exec("ssh -f -L 3307:192.168.100.20:3306 fon@v5.mycallcloud.com sleep 60 >> logfile"); // V5 remotely
$db = mysqli_connect('127.0.0.1', 'fon', 'fon', 'asterisk', 3307); // V5 remotely


// if ( get_class($db) == 'mysqli' ) {
	function msquery($stmt, $db) {
		$rslt = mysqli_query($db, $stmt);
		if ($rslt) { 
			$array = mysqli_fetch_array($rslt);
			return $array[0];
		}
	} 
// } else {
// 	function msquery($stmt, $db) {
// 		$rslt = mysql_query($stmt, $db);
// 		if ($rslt) { 
// 			$array = mysql_fetch_row($rslt);
// 			return $array[0];
// 		}
// 	}
// }

// *************************************************
//	User Authorization
$PHP_AUTH_USER = 'Fon'; $PHP_AUTH_PW = 'Fon1234';
// $PHP_AUTH_USER = $_SERVER['PHP_AUTH_USER']; $PHP_AUTH_PW = $_SERVER['PHP_AUTH_PW'];
// $PHP_AUTH_USER = preg_replace("/[^0-9a-zA-Z]/","",$PHP_AUTH_USER);
// $PHP_AUTH_PW = preg_replace("/[^0-9a-zA-Z]/","",$PHP_AUTH_PW);

// allowed campaigns for user
$stmt = "SELECT allowed_campaigns from vicidial_user_groups A inner join vicidial_users C  where A.user_group=C.user_group and C.user='$PHP_AUTH_USER' and C.pass='$PHP_AUTH_PW' and C.user_level > 6 and C.view_reports='1' and C.active='Y'";
$allowed_campaigns = msquery($stmt, $db);
// echo 'allowed_campaigns is '.$allowed_campaigns.'<br>';
$allowed_campaigns = "'".str_replace(" ", "', '", $allowed_campaigns)."'";
// echo "<br>new allowed campaigns<br>".$allowed_campaigns."<br><br><br>";

// allowed reports for user
$stmt = "SELECT allowed_reports from vicidial_user_groups A inner join vicidial_users C  where A.user_group=C.user_group and C.user='$PHP_AUTH_USER' and C.pass='$PHP_AUTH_PW' and C.user_level > 6 and C.view_reports='1' and C.active='Y'";
$alowed_reports = msquery($stmt, $db);
// echo 'alowed_reports is '.$alowed_reports.'<br>';

// if not all campaigns are allowed, make a custom SQL string
$LOGallowed_campaignsSQL='';
$whereLOGallowed_campaignsSQL='';
if ( !(preg_match("/(?i)-ALL/",$allowed_campaigns)) ) {
	$LOGallowed_campaignsSQL = "and campaign_id IN($allowed_campaigns)";
	// echo '<br>LOGallowed_campaignsSQL is<br>'.$allowed_campaigns.'<br>';
	$whereLOGallowed_campaignsSQL = "where campaign_id IN($allowed_campaigns)";
	// echo '<br>whereLOGallowed_campaignsSQL is<br>'.$whereLOGallowed_campaignsSQL.'<br><br><br>';
}



// info for calls waiting
$stmt = "select closer_campaigns from vicidial_campaigns where active='Y' $LOGallowed_campaignsSQL;";
$rslt=mysqli_query($db, $stmt);
$arrayT = mysqli_fetch_array($rslt);
$closer_campaignsSQL = preg_replace("/[\s]+/", "', '", $arrayT[0]);
$closer_campaignsSQL = "'".$closer_campaignsSQL."'";
//echo "this is closer<br>".$closer_campaignsSQL."<br><br><br>";
?>