$.getJSON(url, function(data){

});


$.getJSON(url, function(data){
						if (data.results[0]) { // if entry is valid
							$embed = $(data.results[0]);
							// create object entries.
							dictionnaire($linkList, ++i);
						} else {
							window.requestFileSystem(window.TEMPORARY, 5*1024*1024, onInitFs);
						}
      			} //end json success function
      			// recurse getJSON
      	);


(function poll(){
    $.ajax({ url: "server", success: function(data){
        //Update your dashboard gauge
        $('')
$agent_ready=0; // Agents Waiting
$agent_total=0; // Agents Logged In
$agent_incall=0; // Agents in Calls
// Agent Avg Wait
$agent_dead=0; // Agents in Dead Calls
$agent_paused=0; // Paused Agents
// Calls Ringing
// Calls Waiting for Agents
$agent_dispo=0; // Agents in Despo

    }, dataType: "json", complete: poll, timeout: 30000 });
})();

/*****
	ui concerns: breaking connection
	timeouts
	should check to see data is valid

	what type of data will we be getting back?
	json, but we need a parsing function for the different data sets
	we need to get some sort of type out for the data



	MySQL database
	4 second timeout selectable timeout
	PHP all stuff is in there
	real time array included in different php file
	echo'd as an array from the PHP file

	colour coded parameters

	what platform will
	quad core lamp servers with caching e-accel
	P4 CPU 2GHz, 512 RAM, IE8, Windows XP

	needs to look different for different users
	eg if this particular cell >= this then colour code

	ViciDial - open source everything
	saved views - they look at which divs they want to make disappear and save preferences in php to mysl databse
	reset to original view


	php page currently has everything serves out what's needed, eg user options
	realtime_report.php

	it guys
	directors
	supervisors

	Asterisk Gateway Interface

	java 8 webphone included in div
	
	coach AJAX sends command

	back/forward nav with AJAX

	create existing functionality
 
	PHP/SQL statements ask for help if there are questions
	Shad is MySQL guy
	Noah is PHP guy

	mysql v2.mycallcloud.com:8896
	cron
	1234
	
	ssh v2.mycallcloud.com:8897
	fon
	fon
	web root at /srv/www/htdocs/vicidial

	http v2.mycallcloud.com:8898
	1101
	BigBuzz

	arrow arrow represents trend is it up or down from last trend
	up is red
	down is green
	"+4" is the increment from the last check

	realtime_something

	php redirect requests jar which stays server side
	webphone's size can be set by css

	web portion is seperate from app
	sip device attached to the system
	xlite is a phone

	coach - NVRMIND
	(play) -  listen
	barge - barge

	test piece done 3PM

	Noah - 720-620-4014

	upload in basecamp
	scp to htdocs

	AST_timeonVDADall.php
	echo "$NFB$agent_total$NFE agents logged in &nbsp; &nbsp; &nbsp; &nbsp; \n";
	2300 to end

	AGENTS waiting = $agent_ready
		things that increment $agent_ready are:
			if ( (eregi("READY",$status)) or (eregi("CLOSER",$status)) ) {$agent_ready++;  $agent_total++;}
			$status =			sprintf("%-6s", $Astatus[$i]);
			$Astatus[$i] =			$row[3];

	Noah - it's Fon

	$out_total // current active calls;
	$out_ring // calls ringing;
	$out_live // calls waiting for agents;
vicidial_users.user_group
vicidial_users.full_name

	extension
	vicidial_live_agents.user
	conf_exten
	vicidial_live_agents.status
	vicidial_live_agents.server_ip
	UNIX_TIMESTAMP(last_call_time)
	UNIX_TIMESTAMP(last_call_finish)
	call_server_ip
	vicidial_live_agents.campaign_id
	vicidial_users.user_group
	vicidial_users.full_name
	vicidial_live_agents.comments
	vicidial_live_agents.calls_today
	vicidial_live_agents.callerid
	lead_id
	UNIX_TIMESTAMP(last_state_change)
	on_hook_agent
	ring_callerid
	agent_log_id
	from vicidial_live_agents,vicidial_users where vicidial_live_agents.user=vicidial_users.user and vicidial_live_agents.campaign_id IN('DemoRCS','SMADemo','test','') order by vicidial_live_agents.status,last_call_time;
	
	DEAD is difference between last_call_time and last_call_finish

	DEAD is not working
	AGENTS LOGGED IN
	 - if extension starts with R/ do not count as total agent
	 - do not count if extension starts with IAX


30 different fields for alerts
	continue adding alerts as long as array is > 0

3 groups of alerts

SQL statements for List Performance & Agent Performance

user column in active resources should have click event to popup detailed agent info

adding two buttons campaigns under stats

#dropped div should refresh every 60 seconds


total agents
select count (*) from vicidial_live_agents (where extention not like 'R%' or extention not like 'IAX%');

At top should be "Agents Waiting" and NOT "Avg Agent Wait"

from vicidial_campaign_stats
DROPPED PERCENT = Dropped % = 					drops_answers_today_pct
DROPPED / ANSWERED = Dropped / ANSWERED = 	drops_today && answers_today
AGENT AVG WAIT = Agent Avg Wait = 				agent_wait_today / agent_calls_today
AVG CUSTIME = Avg Talk Time = 					agent_custtalk_today / agent_calls_today
CALLS TODAY = Calls Today = 						calls_today
 = Avg Warp (wrap) = 								(see find.php)
AVG PAUSE = Avg Pause = 							agent_pause_today / agent_calls_today
AVG AGENTS = Avg Agents = 							agents_average_onemin
DIALABLE LEADS = Dialable LEADS = 				dialable_leads
DIAL METHOD = Dial METHOD = 						


v5.mycallcloud.com,66.241.101.91 ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAAAgQDaXDu3vhzHRyRfMNJTfNvwyZDVe+Ux9aFpiWMpL7kKBxpDPPr6l5WSlhtwTRJ9PlChmS+i/2uo/SkpIg5iLYBh5x2hIGNxbIcxrSHukTgLPYu1Y7FhnVRFlJJjPlncVpjR/FOPT11wZH8uyBOG1rtxJSNhojJmCKuBWs9NRjQ2ww==

v5.mycallcloud.com:58888 - Administrator
user: Fon
pass: Fon1234

wrap is incall

/ Total Calls (Agents in calls + Dead) /
select count() from vicidial_live_agents where status='INCALL';

/ Agents in calls /
select count(*) from vicidial_live_agents A inner join vicidial_auto_calls C on C.callerid=A.callerid where A.status='INCALL';

/ Dead /
select (select count(*) from vicidial_live_agents where status='INCALL')-
(select count(*) from vicidial_live_agents A inner join vicidial_auto_calls C on C.callerid=A.callerid where A.status='INCALL') as 'DEAD';