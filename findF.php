<?php
include 'login.php'; 
// error_reporting(E_ALL);
// ini_set('display_errors', '1');


// $DB_server = 'localhost';
// $DB_port = '3306';
// $DB_user = 'cron';
// $DB_pass = '1234';
// $DB_database = 'asterisk';

// $link=mysql_connect("$DB_server:$DB_port", "$DB_user", "$DB_pass");
// mysql_select_db("$DB_database");


$arrayF = array();

// no. dropped
$stmt = "select sum(drops_today) from vicidial_campaign_stats where calls_today > 10;";
$arrayF['DROPPED'] = msquery($stmt, $db);

// no. answered
$stmt = "select sum(answers_today) from vicidial_campaign_stats where calls_today > 10;";
$arrayF['ANSWERED'] = msquery($stmt, $db);

// dropped %
// $drops_today / $answers_today
$arrayF['DROPPED_PCT'] = number_format(100 * $arrayF['DROPPED'] / $arrayF['ANSWERED'], 2);

// $agent_calls_today
$stmt = "select sum(agent_calls_today) from vicidial_campaign_stats where calls_today > 10;";
$arrayF['AGENT_CALLS_TODAY'] = msquery($stmt, $db);


// agent avg wait
// $agent_wait_today / $agent_calls_today
// $agent_wait_today;
$stmt = "select sum(agent_wait_today) from vicidial_campaign_stats where calls_today > 10;";
$arrayF['AGENT_AVG_WAIT'] = number_format(msquery($stmt, $db) / $arrayF['AGENT_CALLS_TODAY']);

// avg talk time
// $agent_custtalk_today / $agent_calls_today
// $agent_custtalk_today;
$stmt = "select sum(agent_custtalk_today) from vicidial_campaign_stats where calls_today > 10;";
$arrayF['AVG_TALK_TIME'] = number_format(msquery($stmt, $db) / $arrayF['AGENT_CALLS_TODAY']);

// total calls today
$stmt = "select sum(calls_today) from vicidial_campaign_stats where calls_today > 500;";
$arrayF['TOTAL_CALLS_TODAY'] = msquery($stmt, $db);

// avg wrap
$stmt = "select round((sum(dispo_sec)/(select sum(agent_calls_today) from vicidial_campaign_stats)),0) 
from vicidial_agent_log where event_time> now() -interval 14 hour;";
$arrayF['AVG_WRAP'] = msquery($stmt, $db);

// avg pause
// $agent_pause_today / $agent_calls_today
// $agent_pause_today
$stmt = "select sum(agent_pause_today) from vicidial_campaign_stats where calls_today > 10;";
$arrayF['AVG_PAUSE'] = number_format(msquery($stmt, $db) / $arrayF['AGENT_CALLS_TODAY']);

// avg agents
$stmt = "select avg(agents_average_onemin) from vicidial_campaign_stats where calls_today > -1 $LOGallowed_campaignsSQL;";
$arrayF['AVG_AGENTS'] = number_format(msquery($stmt, $db), 2);

// dialable leads
$stmt = "select sum(dialable_leads) from vicidial_campaign_stats where calls_today > -1 $LOGallowed_campaignsSQL;";
$arrayF['DIALABLE_LEADS'] = number_format(msquery($stmt, $db));
// dial method
//$stmt = "select C.dial_method from vicidial_campaign_stats S inner join vicidial_campaigns C on C.campaign_id=S.campaign_id where C.active='Y' and S.calls_today>10 having count()=(select count() from vicidial_campaign_stats S inner join vicidial_campaigns C on C.campaign_id=S.campaign_id where C.active='Y' and S.calls_today>10 order by C.dial_method desc limit 1);";
$stmt = "select min(dial_method) from vicidial_campaigns where active='Y' $LOGallowed_campaignsSQL;";
$arrayF['DIAL_METHOD'] = msquery($stmt, $db);

echo json_encode($arrayF);

?>