<?php
include 'login.php'; 

// error_reporting(E_ALL);
// ini_set('display_errors', '1');

echo 'allowed_campaigns is '.$allowed_campaigns.'<br>';

echo "<br>new allowed campaigns<br>".$allowed_campaigns."<br><br><br>";

echo 'alowed_reports is '.$alowed_reports.'<br>';

echo '<br>LOGallowed_campaignsSQL is<br>'.$LOGallowed_campaignsSQL.'<br>';

echo '<br>whereLOGallowed_campaignsSQL is<br>'.$whereLOGallowed_campaignsSQL.'<br><br><br>';

echo "this is closer<br>".$closer_campaignsSQL."<br><br><br>";
 



// $arrayD = array();
// $i = 0; // arrayD index

// $stmt = "SELECT status,campaign_id,phone_number,server_ip,UNIX_TIMESTAMP(call_time),call_type,queue_priority,agent_only from vicidial_auto_calls where status NOT IN('XFER') and ( (call_type='IN' and campaign_id IN($closer_campaignsSQL)) or (call_type IN('OUT','OUTBALANCE') $LOGallowed_campaignsSQL) ) order by queue_priority desc,campaign_id,call_time";
// $rslt=mysqli_query($db, $stmt);
// while ($row = mysqli_fetch_assoc($rslt)) {
// 	$arrayD[$i] = $row;
// 	$i++;
// }

// echo json_encode($arrayD);

// part C
//  $arrayC = array();

//  $stmt = "select count(*) from vicidial_carrier_log;";
//  $totalStat = msquery($stmt, $db);

//  $i=0;
// // $timeTWENTYFOURhoursAGO = date("Y-m-d H:i:s", date("U") - 86400);
// $stmt = "SELECT dialstatus,COUNT(*) as count FROM vicidial_carrier_log GROUP BY dialstatus";
// $rslt=mysqli_query($db, $stmt);
// while ($row = mysqli_fetch_assoc($rslt)) {
//  	$arrayC[$row['dialstatus']] = number_format(100 * $row['count'] / $totalStat, 2);
//  	$i++;
//  }

echo json_encode($arrayC);


// where call_date >= \"$timeTWENTYFOURhoursAGO\"
 // $i = 0;
 // $rslt=mysqli_query($db, $stmt);
 // while ($row = mysqli_fetch_assoc($rslt)) {
	// $arrayC[$i] = $row;
	// $i++;
 // }












?>