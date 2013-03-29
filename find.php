<?php
include 'database.php'; 

// error_reporting(E_ALL);
// ini_set('display_errors', '1');

// groups
$stmt = "select campaign_id from vicidial_campaigns where active='Y' $LOGallowed_campaignsSQL order by campaign_id;";
//echo 'alowed_reports is '.$alowed_reports.'<br>';


$arrayA = array();

// agents waiting
$stmt = "select count(*) from vicidial_live_agents where (extension not like 'R%' and extension not like 'IAX%') and (status='READY' or status='CLOSER')";
$arrayA['READY'] = msquery($stmt, $db);

// Agents Logged In
$stmt = "select count(*) from vicidial_live_agents where extension not like 'R/%' and extension not like 'IAX2/%'";
$arrayA['TOTAL'] = msquery($stmt, $db);
if ($arrayA['TOTAL'] == null ) {
	$arrayA['TOTAL'] = 0;
}

// Agents in Calls
$stmt = "select count(*) from vicidial_live_agents A inner join vicidial_auto_calls C on C.callerid=A.callerid where A.status='INCALL';";
$arrayA['INCALL'] = msquery($stmt, $db);
if ($arrayA['INCALL'] == null ) {
	$arrayA['INCALL'] = 0;
}

// total active calls
$stmt = "select count(*) from vicidial_auto_calls where status NOT IN('XFER') and ";
$arrayA['ACTIVE'] = msquery($stmt, $db);
if ($arrayA['ACTIVE'] == null ) {
	$arrayA['ACTIVE'] = 0;
}
//  select count(*) from vicidial_auto_calls where status NOT IN('XFER') and ( (call_type='IN' and campaign_id IN ('isaBlastInbound')) or (call_type IN ('OUT','OUTBALANCE') and campaign_id IN('1928','1999','299','437','7373','7374','7375','7376','7377','7771','7777','7778','7779','7780','901','BDLAW','BPA','Energy_R','Gormley','IMS','ISA','isaBlast','msg','Protecti','Rago','TextToSp','')) )


// select count(*) from vicidial_auto_calls where status NOT IN('XFER') and ( (call_type='IN' and campaign_id IN ('isaBlastInbound')) or (call_type IN ('OUT','OUTBALANCE') and campaign_id IN('1928','1999','299','437','7373','7374','7375','7376','7377','7771','7777','7778','7779','7780','901','BDLAW','BPA','Energy_R','Gormley','IMS','ISA','isaBlast','msg','Protecti','Rago','TextToSp','')) )
// 

// dead calls
$stmt = "select (select count(*) from vicidial_live_agents where status='INCALL')-(select count(*) from vicidial_live_agents A inner join vicidial_auto_calls C on C.callerid=A.callerid where A.status='INCALL') as 'DEAD';";
$arrayA['DEAD'] = msquery($stmt, $db);
if ($arrayA['DEAD'] == null ) {
	$arrayA['DEAD'] = 0;
}

// paused agents
$stmt = "select count(*) from vicidial_live_agents where (extension not like 'R%' and extension not like 'IAX%') and (status='PAUSED')";
$arrayA['PAUSED'] = msquery($stmt, $db);
if ($arrayA['PAUSED'] == null ) {
	$arrayA['PAUSED'] = 0;
}

// calls ringing
$calls_ringing;
$stmt = "select count(*) from vicidial_auto_calls where status != 'LIVE' and status != 'IVR' and status != 'CLOSER'";
$arrayA['RINGING'] = msquery($stmt, $db);

// calls waiting
$calls_waiting;
$stmt = "select count(*) from vicidial_auto_calls where status = 'LIVE'";
$arrayA['WAITING'] = msquery($stmt, $db);

// agents dispo
$agents_dispo;
$stmt = "select count(*) from vicidial_live_agents where status = 'PAUSED'  and lead_id > 0 or status = 'READY' and lead_id > 0";
$arrayA['DISPO'] = msquery($stmt, $db);
if ($arrayA['DISPO'] == null ) {
	$arrayA['DISPO'] = 0;
}

// tableC
$timeTHREEhoursAGO = date("Y-m-d H:i:s", date("U") - 10800);
$arrayC = array();

$stmt = "select count(*) from vicidial_carrier_log where call_date >= \"$timeTHREEhoursAGO\";";
$totalStat = msquery($stmt, $db);

$i=0;
$stmt = "SELECT dialstatus,COUNT(*) as count FROM vicidial_carrier_log where call_date >= \"$timeTHREEhoursAGO\" GROUP BY dialstatus";
$rslt=mysqli_query($db, $stmt);
while ($row = mysqli_fetch_assoc($rslt)) {
 	$arrayC[$row['dialstatus']] = number_format(100 * $row['count'] / $totalStat, 2);
 	$i++;
}

// tableD
$arrayD = array();
$i = 0; // arrayD index

$stmt = "SELECT status,campaign_id,phone_number,server_ip,UNIX_TIMESTAMP(call_time),call_type,queue_priority,agent_only from vicidial_auto_calls where status NOT IN('XFER') and ( (call_type='IN' and campaign_id IN($closer_campaignsSQL)) or (call_type IN('OUT','OUTBALANCE') $LOGallowed_campaignsSQL) ) order by queue_priority desc,campaign_id,call_time";
$rslt=mysqli_query($db, $stmt);
while ($row = mysqli_fetch_assoc($rslt)) {
	$arrayD[$i] = $row;
	$i++;
}


// tableE
$arrayE = array();
$i = 0; // arrayD index
function timeFormat($time) {
	$sec = $time % 100;
	$min = (int) ($time / 100);
	$hour = (int) ($min / 100);
	if ($hour < 1) {
		$min = $min;
	} else {
		$min = $min % 100;
		$min = $min + $hour * 60;
	}
	return $min.':'.$sec;
}
$stmt = "select vicidial_users.full_name, vicidial_users.user_group, vicidial_live_agents.status, UNIX_TIMESTAMP(last_update_time) - UNIX_TIMESTAMP(last_call_time) as 'time', vicidial_live_agents.extension, vicidial_live_agents.campaign_id, vicidial_live_agents.calls_today from vicidial_live_agents,vicidial_users where vicidial_live_agents.user=vicidial_users.user $LOGallowed_campaignsSQL;";
$rslt=mysqli_query($db, $stmt);
while ($row = mysqli_fetch_assoc($rslt)) {
	$row['time'] = gmdate('G:i:s', $row['time']); 
	$row['extension'] = preg_replace('/IAX2\/|SIP\//', '', $row['extension']);
	$arrayE[$i] = $row;
	$i++;
}

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