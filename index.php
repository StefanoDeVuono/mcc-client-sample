<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

header ("Content-type: text/html; charset=utf-8");

$NOW_TIME = date("m/d/Y H:i:s");

$agent_ready=0; // Agents Waiting
$agent_total=0; // Agents Logged In
$agent_incall=0; // Agents in Calls
// Agent Avg Wait
$agent_dead=0; // Agents in Dead Calls
$agent_paused=0; // Paused Agents
// Calls Ringing
// Calls Waiting for Agents
$agent_dispo=0; // Agents in Despo

?>

<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="style.css">
<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
<script src="script.js"></script>
<title>MCC</title>
</head>
<body>
	<div id="page">
		<div id="measure" style="position:absolute; width: 42px; height: 56px; top: 75px; left: 337px; background: green;opacity: .5;"></div>

		<section id="sectionF">
			<div id="logo"></div>
			<div class="sides" id="dropped">
				<a class="close" id="closeDropped"></a>
				Dropped %
				<h2>44.6<span class="pct">%</span></h2>
				<h4 id="dropped">Dropped - <span>4466</span></h4>
				<h4 id="answered">Answered - <span>10045</span></h4>
			</div>
			<div class="sides" id="agentAvgWait">
				<a class="close" id="closeAgentAvgWait"></a>
				Agent Avg Wait
				<h3>14:34</h3>
			</div>
			<div class="sides" id="avgTalkTime">
				<a class="close" id="closeAvgTalkTime"></a>
				Avg Talk Time
				<h3>34:33</h3>
			</div>
			<div class="sides" id="callsToday">
				<a class="close" id="closeCallsToday"></a>
				Calls Today
				<h3>43</h3>
			</div>
			<div class="sides" id="avgWarp">
				<a class="close" id="closeAvgWarp"></a>
				Avg Warp
				<h3>23</h3>
			</div>
			<div class="sides" id="avgPause">
				<a class="close" id="closeAvgPause"></a>
				Avg Pause
				<h3>1:45</h3>
			</div>
			<div class="sides" id="avgAgents">
				<a class="close" id="closeAvgAgents"></a>
				Avg Agents
				<h3>8</h3>
			</div>
			<div class="sides" id="dialableLeeds">
				<a class="close" id="closeDialableLeeds"></a>
				Dialable Leeds
				<h3>99,234</h3>
			</div>
			<div class="sides" id="dialMethod">
				<a class="close" id="closeDialMethod"></a>
				Dial Method
				<h3>Inbound</h3>
			</div>
			<div class="clear"></div>
		</section>


		<section id="sectionA">
			<header>
				<a id="reload" href="#"></a>
				<a class="pause" href="#"></a>
				<div id="date"><?php echo $NOW_TIME; ?></div>
				<a id="options">Options</a>
				<a id="settings">User Settings</a>
			</header>		

			<div id="agent_ready" class="col0">
						<div class="text">Agents Waiting</div>
						<div class="number">4</div>
						<div class="arrow up grey"></div>
						<div class="plus"></div>
						<div class="arrow down grey"></div>
			</div>
			<div id="sectionATable">
				<div id="agent_total" class="col1 row1">
					<div class="text">Agents Logged In</div>
					<div class="number">34</div>
				</div>
				<div id="agent_incall" class="col2 row1">
					<div class="text">Agents in Calls</div>
					<div class="number">14</div>
				</div>
				<div id="avg_wait" class="col3 row1">
					<div class="text">Avg Agent Wait</div>
					<div class="number">9</div>
				</div>
				<div id="agent_dead" class="col4 row1">
					<div class="text">Agents in Dead Calls</div>
					<div class="number">3</div>
				</div>
				<div id="agent_paused" class="col1 row2">
					<div class="text">Paused Agents</div>
					<div class="number">44</div>
				</div>
				<div id="out_ring" class="col2 row2">
					<div class="text">Calls Ringing</div>
					<div class="number">2</div>
				</div>
				<div id="out_live" class="col3 row2">
					<div class="text">Calls Waiting for Agents</div>
					<div class="number">22</div>
				</div>
				<div id="agent_dispo" class="col4 row2">
					<div class="text">Agents in Despo</div>
					<div class="number">22</div>
				</div>
			</div><!-- end sectionATable -->
		</section>

		<section id="sectionB">
			<div id="alertLogo"></div>
			<div id="noOfAlerts">3 Alerts</div>
			<label>Alert Settings</label>
			<button id="alertSettings1">Select option</button>
			<button id="alertSettings2">Select option</button>
			<button id="onOff">ON</button>
			<a class="close" href="#"></a>
		</section>

		<section id="sectionC">
			<div class="col0">
				<div><h2>Stats</h2></div>
				<nav>
					<button>Campaigns</button>
					<button>Carrier</button>
					<button>Ingroup</button>
					<button>Agent</butto>
				</nav>
			</div>
			<!-- <div id="answer" class="col1">Answer</div>
			<div id="busy" class="col2">Busy</div>
			<div id="cancel" class="col3">Cancel</div>
			<div id="congestion" class="col4">Congestion</div> -->
			<div id="upperRight">
				<button>All</button>
				<button>24hrs</button>
				<button>6hrs</button>
				<button>1hr</button>
				<button>15min</button>
				<button>5min</button>
				<button>1min</button>
				<a class="close" href="#"></a>
			</div>
		</section>

		<section id="sectionD">
			<a class="close" id="closeSectionD"></a>
			<header>
				<h2>Calls Waiting</h2>
				<button class="pause"></button>
				<label>Pause Calls</label>
				<div class="clear"></div>
			</header>
			<nav>
				<button>View</button>
				<form class="options">
					<div class="thing"><input type="checkbox" class="all">All</div>
					<div class="thing"><input type="checkbox" class="campaign">Campaign</div>
					<div class="thing"><input type="checkbox" class="phone">Phone</div>
					<div class="thing"><input type="checkbox" class="time">Time</div>
					<div class="thing"><input type="checkbox" class="callType">Call Type</div>
					<div class="thing"><input type="checkbox" class="priority">Priority</div>
				</form>
				<div class="clear"></div>
			</nav>
			<div id="callsWaitingTable">
				<button id="campaign" class="col1"><a class="sort"></a>Campaign<a class="close"></a></button>
				<button id="phone" class="col2"><a class="sort"></a>Phone<a class="close"></a></button>
				<button id="time" class="col3"><a class="sort"></a>Time<a class="close"></a></button>
				<button id="callType" class="col4"><a class="sort"></a>Call Type<a class="close"></a></button>
				<button id="priority" class="col5"><a class="sort"></a>Priority<a class="close"></a></button>

				<div class="col1"></div>
				<div class="col2"></div>
				<div class="col3"></div>
				<div class="col4"></div>
				<div class="col5"></div>
			</div>
		</section>

		<section id="sectionE">
			<a class="close" id="closeSectionE"></a>
			<header>
				<h2>Active Resources</h2>
			</header>
			<nav>
				<button>View</button>
				<form class="options">
					<div class="thing"><input type="checkbox" class="all">All</input></div>
					<div class="thing"><input type="checkbox" class="user">User</input></div>
					<div class="thing"><input type="checkbox" class="group">Group</input></div>
					<div class="thing"><input type="checkbox" class="status">Status</input></div>
					<div class="thing"><input type="checkbox" class="time">Time</input></div>
					<div class="thing"><input type="checkbox" class="phone">Phone</input></div>
					<div class="thing"><input type="checkbox" class="campaign">Campaign</input></div>
					<div class="thing"><input type="checkbox" class="calls">Calls</input></div>
				</form>
				<div class="clear"></div>
			</nav>
			<div id="activeResourcesTable">
				<button id="user" class="col1"><a class="sort"></a>User<a class="close"></a></button>
				<button id="group" class="col2"><a class="sort"></a>Group<a class="close"></a></button>
				<button id="status" class="col3"><a class="sort"></a>Status<a class="close"></a></button>
				<button id="time" class="col4"><a class="sort"></a>Time<a class="close"></a></button>
				<button id="phone" class="col5"><a class="sort"></a>Phone<a class="close"></a></button>
				<button id="campaign" class="col6"><a class="sort"></a>Campaign<a class="close"></a></button>
				<button id="calls" class="col7"><a class="sort"></a>Calls<a class="close"></a></button>
				<div class="clear"></div>
				<div class="col1"></div>
				<div class="col2"></div>
				<div class="col3"></div>
				<div class="col4"></div>
				<div class="col5"></div>
				<div class="col6"></div>
				<div class="col7"></div>
				<div class="col8">
					<a id="listen" href="#"></a><a id="speak" href="#"></a><a id="shout" href="#"></a>
				</div>
			</div>
			
			
		</section>

		<div class="clear"></div>
		
	</div>
</body>
</html>