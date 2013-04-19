<?php

include 'database.php';

if (preg_match('/ALL/', $allowed_campaigns) == 1 ) {
	$stmt = "SELECT campaign_id from vicidial_campaigns where active='Y'";
	$result = mysqli_query($db, $stmt);
	$select_campaigns = array();
	while ($row = mysqli_fetch_row($result)) {
        array_push($select_campaigns, $row[0]);
    }
}
$stmt = "SELECT vicidial_user_groups.admin_viewable_groups from vicidial_user_groups, vicidial_users where vicidial_user_groups.user_group= vicidial_users.user_group and vicidial_users.user='$PHP_AUTH_USER' and vicidial_users.pass='$PHP_AUTH_PW'";
//echo $stmt;
$userGroups = msquery($stmt, $db);
if (is_null($userGroups)) { //for admin user
	$stmt = "SELECT user_group from vicidial_user_groups";
	$result = mysqli_query($db, $stmt);
	$userGroups = array();
	while ($row = mysqli_fetch_row($result)) {
        array_push($userGroups, $row[0]);
        //print_r($row);
    }
} else { // for non-admin users
	$userGroups = explode(' ', $userGroups);
}


echo '{"selectCampaigns": ';
echo json_encode($select_campaigns);
echo ', "userGroups": ';
echo json_encode($userGroups);
echo ', "time": ';
echo json_encode(time());
echo '}';
?>