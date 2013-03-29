<?php


to find the call time 
/* STEP 1 */
subtract from
$call_time_S

$stmt="select UNIX_TIMESTAMP(last_state_change) from vicidial_live_agents,vicidial_users where vicidial_live_agents.user=vicidial_users.user $UgroupSQL $usergroupSQL $user_group_filter_SQL order by $orderSQL;";
if status != 'INCALL' || 'QUEUE' || 'PARK' || '3-WAY'

$threewaystmt="select UNIX_TIMESTAMP(last_call_time) from vicidial_live_agents where lead_id='$Alead_id[$i]' and status='INCALL' order by UNIX_TIMESTAMP(last_call_time) desc";
IF IT IS A THREE WAY CALL

OTHERWISE
$stmt="select UNIX_TIMESTAMP(last_call_time) from vicidial_live_agents,vicidial_users where vicidial_live_agents.user=vicidial_users.user $UgroupSQL $usergroupSQL $user_group_filter_SQL order by $orderSQL;";


$Alead_id
$stmt = "select lead_id from vicidial_live_agents,vicidial_users where vicidial_live_agents.user=vicidial_users.user $UgroupSQL $usergroupSQL $user_group_filter_SQL order by $orderSQL;";

/* STEP 2 */
//$UgroupSQL $usergroupSQL $user_group_filter_SQL


 !preg_match("/ALL-/",$LOGallowed_campaigns) ) {$UgroupSQL = " and vicidial_live_agents.campaign_id IN($group_SQL)";}
else if ( (eregi('ALL-ACTIVE',$group_string)) and (strlen($group_SQL) < 3) ) {$UgroupSQL = '';}
else {$UgroupSQL = " and vicidial_live_agents.campaign_id IN($group_SQL)";}

if (strlen($usergroup)<1) {$usergroupSQL = '';}
else {$usergroupSQL = " and user_group='" . mysql_real_escape_string($usergroup) . "'";}
echo "this is<br>".$usergroupSQL.'<br><br>';
if ( (eregi('ALL-GROUPS',$user_group_string)) and (strlen($user_group_SQL) < 3) ) {$user_group_filter_SQL = '';}
else {$user_group_filter_SQL = " and vicidial_users.user_group IN($user_group_SQL)";}
echo "usergroupSQL is ".$usergroupSQL."<br><br>";
echo "user_group_filter_SQL is ".$user_group_filter_SQL."<br><br>";

/* STEP 3 */
//$LOGallowed_campaigns
$stmt="SELECT allowed_campaigns,allowed_reports from vicidial_user_groups where user_group='$LOGuser_group';";

//$group_SQL
$stmt="select campaign_id,campaign_name from vicidial_campaigns where active='Y' $LOGallowed_campaignsSQL order by campaign_id;";
$rslt=mysql_query($stmt, $link);
if ($DB) {echo "$stmt\n";}
$groups_to_print = mysql_num_rows($rslt);
$i=0;
$LISTgroups[$i]='ALL-ACTIVE';
$i++;
$groups_to_print++;
while ($i < $groups_to_print)
	{
	$row=mysql_fetch_row($rslt);
	$LISTgroups[$i] =$row[0];
	$LISTnames[$i] =$row[1];
	$allactivecampaigns .= "'$LISTgroups[$i]',";
	$i++;
	}
$allactivecampaigns .= "''";

$i=0;
$group_string='|';
$group_ct = count($groups);
while($i < $group_ct)
	{
	if ( (preg_match("/ $groups[$i] /",$regexLOGallowed_campaigns)) or (preg_match("/ALL-/",$LOGallowed_campaigns)) )
		{
		$group_SQL .= "'$groups[$i]',";
		}

	$i++;
	}
$group_SQL = eregi_replace(",$",'',$group_SQL);
if ( (ereg("--NONE--",$group_string) ) or ($group_ct < 1) )
	{
	$all_active = 0;
	$group_SQL = "''";
	$group_SQLand = "and FALSE";
	$group_SQLwhere = "where FALSE";
	}
elseif ( eregi('ALL-ACTIVE',$group_string) )
	{
	$all_active = 1;
	$group_SQL = $allactivecampaigns;
	$group_SQLand = "and campaign_id IN($allactivecampaigns)";
	$group_SQLwhere = "where campaign_id IN($allactivecampaigns)";
	}
//$group_string




