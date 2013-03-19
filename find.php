<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');


$DB_server = 'localhost';
$DB_port = '3306';
$DB_user = 'cron';
$DB_pass = '1234';
$DB_database = 'asterisk';

$link=mysql_connect("$DB_server:$DB_port", "$DB_user", "$DB_pass");
mysql_select_db("$DB_database");

$stmt = "select status, count(distinct status) from vicidial_live_agents group by status";
//select extension,vicidial_live_agents.user,conf_exten,vicidial_live_agents.status,vicidial_live_agents.server_ip,UNIX_TIMESTAMP(last_call_time),UNIX_TIMESTAMP(last_call_finish),call_server_ip,vicidial_live_agents.campaign_id,vicidial_users.user_group,vicidial_users.full_name,vicidial_live_agents.comments,vicidial_live_agents.calls_today,vicidial_live_agents.callerid,lead_id,UNIX_TIMESTAMP(last_state_change),on_hook_agent,ring_callerid,agent_log_id from vicidial_live_agents,vicidial_users where vicidial_live_agents.user=vicidial_users.user and vicidial_live_agents.campaign_id IN('DemoRCS','SMADemo','test','') order by vicidial_live_agents.status,last_call_time;
// "select status, count(distinct status) from vicidial_live_agents group by status";
// select count(*) from vicidial_live_agents where (status = 'PAUSED')
$rslt = mysql_query($stmt, $link);
$rows = mysql_num_rows($rslt);

$arrayA = array();

$total_agents = 0;

for ($i=$rows; $i >= 0 ; $i--) { 
 	$tmpArray = mysql_fetch_assoc($rslt);
 	$arrayA[$tmpArray['status']] = $tmpArray['count(distinct status)'];
 	$total_agents = $total_agents + $tmpArray['count(distinct status)'];
 }
 
$arrayA['TOTAL'] = $total_agents;

 if (!array_key_exists('INCALL', $arrayA)) {
 	$arrayA['INCALL'] = 0;
 }
 if (!array_key_exists('PAUSED', $arrayA)) {
 	$arrayA['PAUSED'] = 0;
 }


// total active calls
$stmt = "select count(*) from vicidial_auto_calls";
$rslt = mysql_query($stmt, $link);
$active_calls = mysql_fetch_row($rslt);
$arrayA['ACTIVE'] =  $active_calls[0];

// dead calls
$dead_calls;
$stmt = "select count(*) from parked_channels";
$rslt = mysql_query($stmt, $link);
$parked_calls = mysql_fetch_row($rslt);
if ($parked_calls[0] == 0) {
	$stmt = "select count(distinct vicidial_live_agents.callerid) + count(distinct vicidial_auto_calls.callerid) as rowtotal from vicidial_live_agents, vicidial_auto_calls;";
	$rslt = mysql_query($stmt, $link);
	$dead_calls = mysql_fetch_row($rslt);
}
$arrayA['DEAD'] =  $dead_calls[0];

// calls ringing
$calls_ringing;
$stmt = "select count(*) from vicidial_auto_calls where status != 'LIVE' and status != 'IVR' and status != 'CLOSER'";
$rslt = mysql_query($stmt, $link);
$calls_ringing = mysql_fetch_row($rslt);
$arrayA['RINGING'] =  $calls_ringing[0];

// calls waiting
$calls_waiting;
$stmt = "select count(*) from vicidial_auto_calls where status = 'LIVE'";
$rslt = mysql_query($stmt, $link);
$calls_waiting = mysql_fetch_row($rslt);
$arrayA['WAITING'] =  $calls_waiting[0];

// agents dispo
$agents_dispo;
$stmt = "select count(*) from vicidial_live_agents where status = 'PAUSED'  and lead_id > 0 or status = 'READY' and lead_id > 0";
$rslt = mysql_query($stmt, $link);
$agents_dispo = mysql_fetch_row($rslt);
$arrayA['DISPO'] = $agents_dispo[0];

$arrayA['TIME'] = date("m/d/Y H:i:s");

echo json_encode($arrayA);

$arrayF = array();



// no. dropped
$stmt = "select sum(drops_today) from vicidial_campaign_stats where calls_today > 10;";

// no. answered
$stmt = "select sum(answers_today) from vicidial_campaign_stats where calls_today > 10;";

// dropped %
$drops_today / $answers_today

// agent avg wait
$stmt = "select sum(agent_wait_today) from vicidial_campaign_stats where calls_today > 10;";
$stmt = "select sum(agent_calls_today) from vicidial_campaign_stats where calls_today > 10;";
$agent_wait_today / $agent_calls_today

// avg talk time
$stmt = "select sum(agent_custtalk_today) from vicidial_campaign_stats where calls_today > 10;";
$stmt = "select sum(agent_calls_today) from vicidial_campaign_stats where calls_today > 10;";
$agent_custtalk_today / $agent_calls_today


// calls today
$stmt = "select sum(agent_calls_today) from vicidial_campaign_stats where calls_today > 10;";

// avg wrap
$stmt = "select round((sum(dispo_sec)/(select sum(agent_calls_today) from vicidial_campaign_stats)),0) 
from vicidial_agent_log where event_time> now() -interval 14 hour;";

// avg pause
$stmt = "select sum(agent_pause_today) from vicidial_campaign_stats where calls_today > 10;";
$stmt = "select sum(agent_calls_today) from vicidial_campaign_stats where calls_today > 10;";
$agent_pause_today / $agent_calls_today

// avg agents
$stmt = "select sum(agents_average_onemin * agent_calls_today) from vicidial_campaign_stats where calls_today > 10;"
$stmt = "select sum(agent_calls_today) from vicidial_campaign_stats where calls_today > 10"
$num / $den

/*
$PHP_AUTH_USER' and pass='$PHP_AUTH_PW
*/
// $PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
// $PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
// // eg 1101 / BigBuzz

// /*
// $LOGuser_group
// */
// $stmt="SELECT user_group from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW';";
// // eg ADMIN


// $LOGallowed_campaigns

// $stmt="SELECT allowed_campaigns from vicidial_user_groups where user_group='$LOGuser_group';";
// $rslt=mysql_query($stmt, $link);
// $row=mysql_fetch_row($rslt);
// // eg  '-ALL-CAMPAIGNS- DemoRCS Omni SMADemo test Training VTigerD -'

// /*
// $types
// */
// if (isset($_GET["types"]))				{$types=$_GET["types"];}
// 	elseif (isset($_POST["types"]))		{$types=$_POST["types"];}
// 	if (!isset($types))			{$types='SHOW ALL CAMPAIGNS';}

/*
$campaign_typeSQL 
*/
// $campaign_typeSQL='';
// if ($types == 'AUTO-DIAL ONLY')			{$campaign_typeSQL="and dial_method IN('RATIO','ADAPT_HARD_LIMIT','ADAPT_TAPERED','ADAPT_AVERAGE')";} 
// if ($types == 'MANUAL ONLY')			{$campaign_typeSQL="and dial_method IN('MANUAL','INBOUND_MAN')";} 
// if ($types == 'INBOUND ONLY')			{$campaign_typeSQL="and campaign_allow_inbound='Y'";} 


/* 
$groups
*/
// $stmt="select campaign_id from vicidial_campaigns where active='Y' $LOGallowed_campaignsSQL $campaign_typeSQL order by campaign_id;";
// $rslt=mysql_query($stmt, $link);
// if (!isset($DB))   {$DB=0;}
// if ($DB) {$MAIN.="$stmt\n";}
// $groups_to_print = mysql_num_rows($rslt);
// $i=0;
// while ($i < $groups_to_print)
// 	{
// 	$row=mysql_fetch_row($rslt);
// 	$groups[$i] =$row[0];
// 	$i++;
// 	}



// dialable leads
$stmt="select dialable_leads from vicidial_campaign_stats where campaign_id='" . mysql_real_escape_string($group) . "';";
$rslt=mysql_query($stmt, $link);
$row=mysql_fetch_row($rslt);

// dial method
$stmt="select dial_method from vicidial_campaigns where campaign_id='" . mysql_real_escape_string($group) . "';";


?>