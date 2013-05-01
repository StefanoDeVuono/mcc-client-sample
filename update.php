<?php
include 'database.php'; 

// error_reporting(E_ALL);
// ini_set('display_errors', '1');
//echo $allowed_campaigns;

//$_GET["selectCampaigns"];
//$_GET["selectUserGroups"];


// updates
// add in group temporarily
//$stmt = "update vicidial_live_agents set closer_campaigns = concat(SUBSTRING_INDEX(closer_campaigns, ' -', 1),' Inbound',' -') where user = 'mporte'"
// add in group permanently
//$stmt = "update vicidial_users set closer_campaigns = concat(SUBSTRING_INDEX(closer_campaigns, ' -', 1),' Inbound',' -') where user = 'mporte'"
//mysqli_query($db, $stmt);
// remove temporarily
//$stmt = "update vicidial_live_agents set closer_campaigns = replace (closer_campaigns,'Inbound ','') where user = 'mporte';"
//mysqli_query($db, $stmt);
// remove permanently
//$stmt = "update vicidial_users set closer_campaigns = replace (closer_campaigns,'Inbound ','') where user = 'mporte';"
//mysqli_query($db, $stmt);

//new

echo '{';
echo '"A": ';
echo json_encode($arrayA);
echo ', "C":';
echo json_encode($arrayC);
echo ', "D":';
echo json_encode($arrayD);
echo ', "E":';
echo json_encode($arrayE);
//echo ', "trouble":';
//echo '"'.$LOGallowed_campaignsSQL.'     '.$PHP_AUTH_USER.'     '.$PHP_AUTH_PW.'     '.$allowed_campaigns.'     '.$allowed_reports.'"';
echo '}';

?>