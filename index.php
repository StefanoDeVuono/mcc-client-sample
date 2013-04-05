<?php

// error_reporting(E_ALL);
// ini_set('display_errors', '1');

session_start();

header ("Content-type: text/html; charset=utf-8");

$agent_ready=0; // Agents Waiting
$agent_total=0; // Agents Logged In
$agent_incall=0; // Agents in Calls
// Agent Avg Wait
$agent_dead=0; // Agents in Dead Calls
$agent_paused=0; // Paused Agents
// Calls Ringing
// Calls Waiting for Agents
$agent_dispo=0; // Agents in Despo

//MySQL Database Connect
include('login.php');

?>

<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="style.css">
<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
<script src="./jquery.tablesorter.min.js"></script>
<script async src="script.js"></script>
<title>MCC</title>
</head>
<body>
	<?php //echo session_id(); ?>
	<div id="background">
		<div><span></span></div>
	</div>
	<div id="page">

		<section id="sectionF">
			<div id="logo"></div>
			<div class="sides" id="dropped">
				<a href="#" class="close" id="closeDropped"></a>
				<header>Dropped %</header>
				<div class="closable">
					<h2>
						<span class="pct_no"></span>
						<span class="pct">%</span>
						<div id="arrows" class="neutral">-</div>
					</h2>
					<h4 id="dropped_no">Dropped - <span></span></h4>
					<h4 id="answered">Answered - <span></span></h4>
				</div>
			</div>
			<div class="sides" id="agentAvgWait">
				<a href="#" class="close" id="closeAgentAvgWait"></a>
				<header>Agent Avg Wait</header>
				<div class="closable">
					<h3></h3>
				</div>
			</div>
			<div class="sides" id="avgTalkTime">
				<a href="#" class="close" id="closeAvgTalkTime"></a>
				<header>Avg Talk Time</header>
				<div class="closable">
					<h3></h3>
				</div>
			</div>
			<div class="sides" id="callsToday">
				<a href="#" class="close" id="closeCallsToday"></a>
				<header>Calls Today</header>
				<div class="closable">
					<h3></h3>
				</div>
			</div>
			<div class="sides" id="avgWrap">
				<a href="#" class="close" id="closeAvgWarp"></a>
				<header>Avg Wrap</header>
				<div class="closable">
					<h3></h3>
				</div>
			</div>
			<div class="sides" id="avgPause">
				<a href="#" class="close" id="closeAvgPause"></a>
				<header>Avg Pause</header>
				<div class="closable">
					<h3></h3>
				</div>
			</div>
			<div class="sides" id="avgAgents">
				<a href="#" class="close" id="closeAvgAgents"></a>
				<header>Avg Agents</header>
				<div class="closable">
					<h3></h3>
				</div>
			</div>
			<div class="sides" id="dialableLeeds">
				<a href="#" class="close" id="closeDialableLeeds"></a>
				<header>Dialable Leeds</header>
				<div class="closable">
					<h3></h3>
				</div>
			</div>
			<div class="sides" id="dialMethod">
				<a href="#" class="close" id="closeDialMethod"></a>
				<header>Dial Method</header>
				<div class="closable">
					<h3></h3>
				</div>
			</div>
			<a href="tabs.php">
				<div class="sides" id="reports">
					<a href="#" class="close" id="closeReports"></a>
					<header>View</header>
					<div class="closable">
						<h3>Reports</h3>
					</div>
				</div>
			</a>
			<div class="clear"></div>
		</section>


		<section id="sectionA">
			<header>
				<a id="reload" href="#"></a>
				<a class="pause" href="#"></a>
				<div id="date"></div>
				<a id="options">Options</a>
				<a id="settings">User Settings</a>
			</header>		

			<div id="agent_ready" class="col0">
						<div class="text">Agents Waiting</div>
						<div class="number"></div>
						<div class="arrow up grey"></div>
						<div class="plus"></div>
						<div class="arrow down grey"></div>
			</div>
			<div id="sectionATable">
				<div id="agent_total" class="col1 row1">
					<div class="text">Agents Logged In</div>
					<div class="number"></div>
				</div>
				<div id="agent_incall" class="col2 row1">
					<div class="text">Agents in Calls</div>
					<div class="number"></div>
				</div>
				<div id="out_total" class="col3 row1">
					<div class="text">Current Active Calls</div>
					<div class="number"></div>
				</div>
				<div id="agent_dead" class="col4 row1">
					<div class="text">Agents in Dead Calls</div>
					<div class="number"></div>
				</div>
				<div id="agent_paused" class="col1 row2">
					<div class="text">Paused Agents</div>
					<div class="number"></div>
				</div>
				<div id="out_ring" class="col2 row2">
					<div class="text">Calls Ringing</div>
					<div class="number"></div>
				</div>
				<div id="out_live" class="col3 row2">
					<div class="text">Calls Waiting for Agents</div>
					<div class="number"></div>
				</div>
				<div id="agent_dispo" class="col4 row2">
					<div class="text">Agents in Dispo</div>
					<div class="number"></div>
				</div>
			</div><!-- end sectionATable -->
		</section>


		<section id="sectionB">
			<div class="closable">
				<div id="alertLogo"></div>
				<header>Alert Settings</header>
				<label>3 Alerts</label>
				<button id="alertSettings1">Select option</button>
				<button id="alertSettings2">Select option</button>
				<div id="onOff"></div>
			</div>
			<a class="close" href="#"></a>
		</section><!-- end sectionB -->

		<section id="sectionC">
			<header><h2>Stats</h2></header>
			<div class="closable">
				<div class="col0">
					
					<nav>
						<div class="button">Campaigns</div>
						<div class="button">Carrier</div>
						<div class="button">Ingroup</div>
						<div class="button">Agent</div>
					</nav>
				</div>
				<div id="answer" class="col1"><div class="number"><span class="pct">%</span></div>Answer</div>
				<div id="busy" class="col2"><div class="number"><span class="pct">%</span></div>Busy</div>
				<div id="cancel" class="col3"><div class="number"><span class="pct">%</span></div>Cancel</div>
				<div id="congestion" class="col4"><div class="number"><span class="pct">%</span></div>Congestion</div>
				<div id="upperRight">
					<button>All</button>
					<button>24hrs</button>
					<button>6hrs</button>
					<button>1hr</button>
					<button>15min</button>
					<button>5min</button>
					<button>1min</button>
					
				</div>
			</div>
			<a class="close" href="#"></a>
		</section><!-- end sectionC -->

		<section id="sectionD">
			<a class="close" id="closeSectionD" href="#"></a>
			<header>
				<h2>Calls Waiting</h2>
			</header>
			<div class="closable">
					
				<div class="pause">
					<a href="#"><div class="minipause"></div></a>
					<div class="pauselabel">Pause</div>
				</div>
				
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
				<table id="callsWaitingTable" class="tablesorter">
				<thead>
					<th id="campaign" class="col1"><a class="sort"></a>Campaign<a class="close" href="#"></a></th>
					<th id="phone" class="col2"><a class="sort"></a>Phone<a class="close" href="#"></a></th>
					<th id="time" class="col3"><a class="sort"></a>Time<a class="close" href="#"></a></th>
					<th id="callType" class="col4"><a class="sort"></a>Call Type<a class="close" href="#"></a></th>
					<th id="priority" class="col5"><a class="sort"></a>Priority<a class="close" href="#"></a></th>
				</thead>
					<tbody class="rows"></tbody>
				</table><!-- end callsWaitingTable -->
			</div><!-- end closable -->
		</section><!-- end sectionD -->

		<section id="sectionE">
			<a class="close" id="closeSectionE" href="#"></a>
			<header>
				<h2>Active Resources</h2>
			</header>
			<div class="closable">
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
				<table id="activeResourcesTable" class="tablesorter">
				<thead>
					<th id="user" class="col1"><a class="sort"></a>User<a class="close" href="#"></a></th>
					<th id="group" class="col2"><a class="sort"></a>Group<a class="close" href="#"></a></th>
					<th id="status" class="col3"><a class="sort"></a>Status<a class="close" href="#"></a></th>
					<th id="time" class="col4"><a class="sort"></a>Time<a class="close" href="#"></a></th>
					<th id="phone" class="col5"><a class="sort"></a>Phone<a class="close" href="#"></a></th>
					<th id="campaign" class="col6"><a class="sort"></a>Campaign<a class="close" href="#"></a></th>
					<th id="calls" class="col7"><a class="sort"></a>Calls<a class="close" href="#"></a></th>
				</thead>
					<!-- <div class="clear"></div> -->
					<tbody class="rows">

					</tbody>
				</table>
			</div>
		</section><!-- end sectionE -->

		<div class="clear"></div>
		<div id="troubleman"></div>
	</div>
</body>

</html>