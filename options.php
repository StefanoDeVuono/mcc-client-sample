<?php

include 'database.php';
// echo $allowed_campaigns.'<br>';
// "SELECT campaign_id from vicidial_campaigns A, vicidial_users B where A.active='Y' and B.user_group=A.user_group and B.user='5802' and B.pass='5802'";

// select campaigns that are active (where active="Y") and user_group=user_group or user_group="A"
// select user group

if (preg_match('/ALL/', $allowed_campaigns) == 1 ) { // for admin user
	$stmt = "SELECT campaign_id from vicidial_campaigns where active='Y'";
	$result = mysqli_query($db, $stmt);
	$select_campaigns = array();
	while ($row = mysqli_fetch_row($result)) {
		array_push($select_campaigns, $row[0]);
	}
   $stmt = "SELECT user_group from vicidial_user_groups";
	$result = mysqli_query($db, $stmt);
	$userGroups = array();
	while ($row = mysqli_fetch_row($result)) {
		array_push($userGroups, $row[0]);
		//print_r($row);
	}
} else {  // for non-admin users
	// JSalerno abc123 group: NJTRANSFERS
	//echo 'non-admin<br>';
	$stmt = "SELECT distinct campaign_id from vicidial_campaigns A, vicidial_users B where A.active='Y' and (B.user_group=A.user_group or A.user_group='---ALL---') and B.user='$PHP_AUTH_USER' and B.pass='$PHP_AUTH_PW'";
	$result = mysqli_query($db, $stmt);
	$select_campaigns = array();
	while ($row = mysqli_fetch_row($result)) {
		array_push($select_campaigns, $row[0]);
	}
	$stmt = "SELECT vicidial_user_groups.admin_viewable_groups from vicidial_user_groups, vicidial_users where (vicidial_user_groups.user_group=vicidial_users.user_group or vicidial_user_groups.) and vicidial_users.user='$PHP_AUTH_USER' and vicidial_users.pass='$PHP_AUTH_PW'";
	$userGroups = explode(' ', msquery($stmt, $db));
	if ( empty($userGroups) ) {
		$stmt = "SELECT user_group from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW'";
		$userGroups = msquery($stmt, $db);
	}
}

$stmt="select phone_login from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and active='Y';";
$getPhoneLogin = msquery($stmt, $db);


echo '{"selectCampaigns": ';
echo json_encode($select_campaigns);
echo ', "userGroups": ';
echo json_encode($userGroups);
echo ', "time": ';
echo json_encode(time());
echo ', "getPhoneLogin": ';
echo json_encode($getPhoneLogin);

if ( isset($_GET["setPhoneLogin"]) ) {
	$setPhoneLogin = $_GET["setPhoneLogin"];
	$stmt="SELECT server_ip from phones where login='$setPhoneLogin' and active = 'Y';";
	$server_ip = msquery($stmt, $db);
	echo ', "user": ';
	echo json_encode($PHP_AUTH_USER);
	echo ', "pass": ';
	echo json_encode($PHP_AUTH_PW);
	echo ', "server-ip": ';
	echo json_encode($server_ip);
	echo ', "setPhoneLogin": ';
	echo json_encode($setPhoneLogin);
}

echo '}';
?>