<?php
include 'database.php'; 

// error_reporting(E_ALL);
// ini_set('display_errors', '1');
//echo $allowed_campaigns;

//$_GET["selectCampaigns"];
//$_GET["selectUserGroups"];

// groups
$stmt = "select campaign_id from vicidial_campaigns where active='Y' $LOGallowed_campaignsSQL order by campaign_id;";
$alowed_reports = array();
$result = mysqli_query($db, $stmt);
// while ($row = mysqli_fetch_row($result)) {
//         print_r($row);
//     }

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
$dOrder = "";
if ( isset($_GET["dSort"]) && isset($_GET["dOrder"]) ) {
	$dOrder = "order by ".$_GET["dSort"].' '.$_GET["dOrder"];
}
$stmt = "SELECT status,campaign_id as 'campaign',phone_number as 'phone',server_ip,UNIX_TIMESTAMP(call_time) as 'time',call_type as 'callType',queue_priority as 'priority',agent_only from vicidial_auto_calls where status NOT IN('XFER') and ( (call_type='IN' and campaign_id IN($closer_campaignsSQL)) or (call_type IN('OUT','OUTBALANCE') $LOGallowed_campaignsSQL) ) $dOrder";
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
$eOrder = "";
if ( isset($_GET["eSort"]) && isset($_GET["eOrder"]) ) {
	if ($_GET["eSort"] == 'status') {
		$_GET["eSort"] = "CAST(status as char)";
	}
	$eOrder = "order by ".$_GET["eSort"].' '.$_GET["eOrder"];
}
// $test = "show tables like 'realtime';";
// if ( msquery($test, $db) == 'realtime' ) {
	$stmt = "select vicidial_users.user as 'userid', vicidial_users.full_name as 'user', vicidial_users.user_group as 'group', vicidial_live_agents.status as 'status', vicidial_live_agents.conf_exten as 'session-id', vicidial_live_agents.comments as 'type', UNIX_TIMESTAMP(last_update_time) - UNIX_TIMESTAMP(last_call_time) as 'time', SUBSTRING_INDEX(vicidial_live_agents.extension, '/', -1) AS 'extension', vicidial_live_agents.campaign_id as 'campaign', vicidial_live_agents.calls_today as 'calls', vicidial_live_agents.extension as 'station', (case when realtime.contacts>0 then realtime.contacts else 0 end) 'contacts', (case when realtime.successes>0 then realtime.successes else 0 end) 'successes', (case when realtime.transfers>0 then realtime.transfers else 0 end) 'transfers', vicidial_campaigns.closer_campaigns as 'in-group' from vicidial_live_agents,vicidial_users,realtime,vicidial_campaigns where vicidial_live_agents.user=vicidial_users.user and vicidial_live_agents.user=realtime.userid and vicidial_live_agents.campaign_id=vicidial_campaigns.campaign_id group by vicidial_live_agents.user,realtime.userid $eOrder;";

//} else {
//	$stmt = "select vicidial_users.user as 'userid', vicidial_users.full_name as 'user', vicidial_users.user_group as 'group', vicidial_live_agents.status as 'status', vicidial_live_agents.conf_exten as 'session-id', vicidial_live_agents.comments as 'type', UNIX_TIMESTAMP(last_update_time) - UNIX_TIMESTAMP(last_call_time) as 'time', SUBSTRING_INDEX(vicidial_live_agents.extension, '/', -1) AS 'phone', vicidial_live_agents.campaign_id as 'campaign', vicidial_live_agents.calls_today as 'calls', vicidial_live_agents.extension as 'station', (case when B.contacts>0 then B.contacts else 0 end) 'contacts', (case when C.sales>0 then C.sales else 0 end) 'successes', (case when D.transfers>0 then D.transfers else 0 end) 'Transfers' from vicidial_live_agents,vicidial_users LEFT JOIN (select B.user,case when count(*)=0 then 0 else count(*) end 'contacts' from vicidial_agent_log B	where event_time > DATE_FORMAT(now(), \"%Y-%m-%d\") and (status in (select distinct status from vicidial_campaign_statuses where customer_contact='Y') or status in (select distinct status from vicidial_statuses where customer_contact='Y')) group by B.user) B on (vicidial_users.user=B.user) LEFT JOIN (select C.user,case when count(*)>0 then count(*) else 0 end 'sales' from vicidial_agent_log C where event_time > now() -interval 12 hour and (status in (select distinct status from vicidial_campaign_statuses where sale='Y') or status  in (select distinct status from vicidial_statuses where sale='Y'))  group by C.user) C on (vicidial_users.user=C.user ) LEFT JOIN (select D.user,case when count(*)>0 then count(*) else 0 end 'transfers' from vicidial_agent_log D where event_time > now() -interval 12 hour and (status in ('XFER'))  group by D.user) D on (vicidial_users.user=D.user ) where vicidial_live_agents.user=vicidial_users.user $eOrder;";
//}
// $stmt = "select vicidial_users.user as 'userid', vicidial_users.full_name as 'user', vicidial_users.user_group as 'group', vicidial_live_agents.status as 'status', UNIX_TIMESTAMP(vicidial_live_agents.last_update_time) - UNIX_TIMESTAMP(vicidial_live_agents.last_call_time) as 'time', vicidial_live_agents.extension as 'phone', vicidial_live_agents.campaign_id as 'campaign', vicidial_live_agents.calls_today as 'calls', vicidial_live_agents.extension as 'station', vicidial_auto_calls.campaign_id from vicidial_live_agents,vicidial_users, vicidial_auto_calls where vicidial_live_agents.user=vicidial_users.user and vicidial_live_agents.callerid = vicidial_auto_calls.callerid $LOGallowed_campaignsSQL $eOrder;";
// echo $eOrder;
// echo 'stmt is '.$stmt;
$rslt=mysqli_query($db, $stmt);
while ($row = mysqli_fetch_assoc($rslt)) {
	$row['time'] = gmdate('G:i:s', $row['time']); 
	$row['type'] = ( $row['type'] == null ? "&nbsp;" : $row['type'] );
	$row['in-group'] = explode(' ', trim($row['in-group'], ' -'));
	$arrayE[$i] = $row;
	$i++;
}

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

//new test

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