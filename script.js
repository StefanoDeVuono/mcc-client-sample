// insert date into DOM
var date = new Date();
var month = date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1;
var day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate();
var year = date.getYear() % 100;
$('#date').text(month + '/' + day + '/' + year + '  ' + date.toLocaleTimeString());
// get accurate date from server and update every second
$.get('./time.php', function(data) {
	var t = data * 1000;
	var begin = new Date(t);
	setInterval(function(){
		var month = begin.getMonth() + 1 < 10 ? '0' + (begin.getMonth() + 1) : begin.getMonth() + 1;
		var day = begin.getDate() < 10 ? '0' + begin.getDate() : begin.getDate();
		var year = begin.getYear() % 100;
		$('#date').text(month + '/' + day + '/' + year + '  ' + begin.toLocaleTimeString());
		begin.setTime(begin.valueOf() + 1000);
	}, 1000);
});

var delay = 2;

var oldDropped, deltaDropped;
droppedTrend();
function droppedTrend(){
	var newDropped = $('#dropped_no span').text();
	deltaDropped = oldDropped - newDropped;
	oldDropped = newDropped;
	if ( deltaDropped > 0 ) {
		// red up arrow
		$('#arrows').text(deltaDropped);
		$('#arrows').removeClass();
		$('#arrows').addClass('goingUp');
	} else if ( deltaDropped > 0) {
		$('#arrows').text(deltaDropped);
		$('#arrows').removeClass();
		$('#arrows').addClass('goingDown');
	} else {
		$('#arrows').text(deltaDropped);
		$('#arrows').removeClass();
		$('#arrows').addClass('neutral');
	}
}

// default sort field for sectionD
var dSorter = 'campaign';
var dOrder = "asc";
// listener to change sort field for sectionD
$('#callsWaitingTable th').on('click', function(){
	//$(this).addClass('clicked');
	dSorter = $(this).attr('id');

	if ( $(this).hasClass('headerSortDown') )
		dOrder = "desc";
	else if ( $(this).hasClass('headerSortUp') )
		dOrder = "asc"
});


// default sort field for sectionE
var eSorter = 'user';
var eOrder = "asc";
// listeners to change sort field for sectionE
$('#activeResourcesTable th').on('click', function(){
	//$(this).addClass('clicked');
	eSorter = $(this).attr('id');

	if ( $(this).hasClass('headerSortDown') )
		eOrder = "asc";
	else if ( $(this).hasClass('headerSortUp') )
		eOrder = "desc"

});

// ***********************************************************
// find for areas A-E, updates every "delay" number of seconds
if ( delay > 0 ) {
	function findAE(){
		setTimeout(ajaxFunc, delay * 1000); // end timeout
	}
	ajaxFunc(); // do lookup first time
	function ajaxFunc(){
		$.ajax({ url: "./find.php",
		data: {dSort: dSorter, dOrder: dOrder, eSort: eSorter, eOrder: eOrder},
		success: function(data){
			//Update your dashboard gauge
			setDataA(data);
			setDataC(data);
			setDataD(data);
			setDataE(data);
			droppedTrend();
			$("#callsWaitingTable").trigger("update");
			$("#activeResourcesTable").trigger("update");
			//Setup the next poll recursively
			findAE();
		}, dataType: "json"}); // end ajax function and object
	}
	(find)(); // auto run lookup after every deley seconds
} else if ( delay == 0 ) {
	(function findAE(){
		$.ajax({ url: "./find.php",
		data: {dSort: dSorter, dOrder: dOrder, eSort: eSorter, eOrder: eOrder},
		success: function(data){
			//Update your dashboard gauge
			setDataA(data);
			setDataC(data);
			setDataD(data);
			setDataE(data);
			$("#callsWaitingTable").trigger("update");
			$("#activeResourcesTable").trigger("update");
		}, dataType: "json", complete: find, timeout: 30000 }); // end ajax object
	})();
}

function setDataA(data) {
	$('#agent_ready .number').text(data['A']['READY']); // Agents Waiting
	$('#agent_total .number').text(data['A']['TOTAL']); // Agents Logged In - TOTAL
	$('#agent_incall .number').text(data['A']['INCALL']); // Agents in Calls - INCALL
	$('#out_total .number').text(data['A']['ACTIVE']); // current active calls - ACTIVE - total no of rows in vicidial_auto_calls
	$('#agent_dead .number').text(data['A']['DEAD']); // Agents in Dead Calls - DEAD -if the callerid field from vicidial_live_agents does not match callerid from vicidial_auto_calls and parked_channels has no rows
	$('#agent_paused .number').text(data['A']['PAUSED']); // Paused Agents - PAUSED
	$('#out_ring .number').text(data['A']['RINGING']); // Calls Ringing - RINGING - total no of row where status is not LIVE, IVR, CLOSER in vicidial_autocalls 
	$('#out_live .number').text(data['A']['WAITING']); // Calls Waiting for Agents - WAITING - total no of rows where status is "LIVE" in vicidial_auto_calls
	$('#agent_dispo .number').text(data['A']['DISPO']); // Agents in Despo - DISPO - no of agents who are PAUSED or READY and whose lead_id>1 in vicidial_live_agents

	$('#troubleman').text(data['statmentE']);

}

function setDataC(data) {
	var ansPct = data['C']['ANSWER'] || 0;
	var busPct = data['C']['BUSY'] || 0;
	var canPct = data['C']['CANCEL'] || 0;
	var conPct = data['C']['CONGESTION'] || 0;
	$('#answer .number').html(ansPct + '<span class="pct">%</span'); // Answer %
	$('#busy .number').html(busPct + '<span class="pct">%</span'); // Busy %
	$('#cancel .number').html(canPct + '<span class="pct">%</span'); // Cancel %
	$('#congestion .number').html(conPct + '<span class="pct">%</span'); // Congestion %
}

function setDataD(data) {
	var length = data['D'].length;
	var row = "";
	for (var i = 0; i < length; i++) {
		row += '<tr>'
		if ( $('#callsWaitingTable th.col1').is(':hidden') )
			row += '<td class="col1" style="display: none">' + data['D'][i]['campaign'] + '</td>';
		else
			row += '<td class="col1">' + data['D'][i]['campaign'] + '</td>';

		if ( $('#callsWaitingTable th.col2').is(':hidden') )
			row += '<td class="col2" style="display: none">' + data['D'][i]['phone'] + '</td>';
		else
			row += '<td class="col2">' + data['D'][i]['phone'] + '</td>';

		if ( $('#callsWaitingTable th.col3').is(':hidden') )
			row += '<td class="col3" style="display: none">' + data['D'][i]['time'] + '</td>';
		else
			row += '<td class="col3">' + data['D'][i]['time'] + '</td>';

		if ( $('#callsWaitingTable th.col4').is(':hidden') )
			row += '<td class="col4" style="display: none">' + data['D'][i]['callType'] + '</td>';
		else
			row += '<td class="col4">' + data['D'][i]['callType'] + '</td>';

		if ( $('#callsWaitingTable th.col5').is(':hidden') )
			row += '<td class="col5" style="display: none">' + data['D'][i]['priority'] + '</td>';
		else
			row += '<td class="col5">' + data['D'][i]['priority'] + '</td>';
		row += '</tr>'
	}
	
	$('#callsWaitingTable tbody.rows').html(row);
	resizeColumns('#callsWaitingTable', 666);
}

function setDataE(data) {
	var length = data['E'].length;

	var row = "";
	col8 = "";
	for (var i = 0; i < length; i++) {
		row += '<tr>'
		if ( $('#activeResourcesTable th.col1').is(':hidden') )
			row += '<td class="col col" style="display: none">' + data['E'][i]['user'] + '</td>';
		else
			row += '<td class="col col1">' + data['E'][i]['user'] + '</td>';
		
		if ( $('#activeResourcesTable th.col2').is(':hidden') )
			row += '<td class="col col2" style="display: none">' + data['E'][i]['group'] + '</td>';
		else
			row += '<td class="col col2">' + data['E'][i]['group'] + '</td>';

		if ( $('#activeResourcesTable th.col3').is(':hidden') )
			row += '<td class="col col3" style="display: none">' + data['E'][i]['status'] + '</td>';
		else
			row += '<td class="col col3">' + data['E'][i]['status'] + '</td>';

		if ( $('#activeResourcesTable th.col4').is(':hidden') )
			row += '<td class="col col4" style="display: none">' + data['E'][i]['time'] + '</td>';
		else
			row += '<td class="col col4">' + data['E'][i]['time'] + '</td>';

		if ( $('#activeResourcesTable th.col5').is(':hidden') )
			row += '<td class="col col5" style="display: none">' + data['E'][i]['phone'] + '</td>';
		else
			row += '<td class="col col5">' + data['E'][i]['phone'] + '</td>';

		if ( $('#activeResourcesTable th.col6').is(':hidden') )
			row += '<td class="col col6" style="display: none">' + data['E'][i]['campaign'] + '</td>';
		else
			row += '<td class="col col6">' + data['E'][i]['campaign'] + '</td>';

		if ( $('#activeResourcesTable th.col7').is(':hidden') )
			row += '<td class="col col7" style="display: none">' + data['E'][i]['calls'] + '</td>';
		else
			row += '<td class="col col7">' + data['E'][i]['calls'] + '</td>';
		row += '</tr>';
	}
	$('#activeResourcesTable tbody.rows').html(row);
	resizeColumns('#activeResourcesTable', 666);
}


// ******************************************************************
// define function to find by the minute aggregate info for section F
function findF(){
	setTimeout(findFajax, 60000);
};

// execute findF at load
findFajax();

// execute findF at 1 minute intervals
(findF)();

// ajax function used in findF
function findFajax(){
	$.ajax({ url: "./findF.php", success: function(data){
			setDataF(data);
			droppedTrend();
			findF();
		}, dataType: "json"});
}
function setDataF(data){
	$('#dropped_no span').text(data['DROPPED']); // Total Dropped Calls today
	$('#answered span').text(data['ANSWERED']); // Total Answered Calls today
	$('#dropped h2 span.pct_no').text(data['DROPPED_PCT']); // Percent of Dropped calls today
	$('#agentAvgWait h3').text(data['AGENT_AVG_WAIT']); // A
	$('#avgTalkTime h3').text(data['AVG_TALK_TIME']); //
	$('#callsToday h3').text(data['TOTAL_CALLS_TODAY']); // Number of Agents' calls today
	$('#avgWrap h3').text(data['AVG_WRAP']); // Average Wrap
	$('#avgPause h3').text(data['AVG_PAUSE']); // Average Pause
	$('#avgAgents h3').text(data['AVG_AGENTS']); // Average Pause
	$('#dialableLeeds h3').text(data['DIALABLE_LEADS']); // Average Pause
	$('#dialMethod h3').text(data['DIAL_METHOD']); // Average Pause
}
// ***********************************************************
// Pause buttons

pause = function(){
	console.log('pause');
}
$('#sectionA header').on('click', 'a.pause', function(e){
	e.preventDefault();
	storeA = setDataA;
	storeC = setDataC;
	storeD = setDataD;
	storeE = setDataE;
	storeF = setDataF;
	setDataA = pause;
	setDataC = pause;
	setDataD = pause;
	setDataE = pause;
	setDataF = pause;
	$(this).removeClass('pause');
	$(this).addClass('play');
});

$('#sectionA header').on('click', 'a.play', function(e){
	console.log('play');
	e.preventDefault();
	setDataA = storeA;
	setDataC = storeC;
	setDataD = storeD;
	setDataE = storeE;
	setDataF = storeF;
	ajaxFunc();
	findFajax();
	$(this).removeClass('play');
	$(this).addClass('pause');
});
// section D pauser
$('#sectionD .pause').on('click', 'div.minipause', function(e){
	e.preventDefault();
	storeD = setDataD;
	setDataD = pause;
	$(this).removeClass('minipause');
	$(this).addClass('miniplay');
	$('#sectionD .pause .pauselabel').text('Play');
});

$('#sectionD .pause').on('click', 'div.miniplay', function(e){
	e.preventDefault();
	setDataD = storeD;
	ajaxFunc();
	$(this).removeClass('miniplay');
	$(this).addClass('minipause');
	$('#sectionD .pause .pauselabel').text('Pause');
});


// ***********************************************************
// Close buttons

$('section').on('click', 'a.close',function(e){
	e.preventDefault();
	parent = '#' + $(this).parent().attr('id');
	
	if ( $(parent).is('section') || $(parent).is('div') ) {
		console.log(parent);
		$(parent).addClass('closed');
		var button = $(this);
		//console.log(parent);
		var timer = 400;
		// $parent.removeClass('open');
		$(parent + ' .closable').slideUp(timer, function(){
			button.removeClass('close');
			button.addClass('open');
		});
		$(parent + ' header').animate({'margin-top': '8px'}, timer);
	}
});

$('section').on('click', 'a.open',function(e){
	e.preventDefault();
	parent = '#' + $(this).parent().attr('id');
	if ( $(parent).is('section') || $(parent).is('div') ) {
		console.log(parent);
		$(parent).removeClass('closed');
		var button = $(this);
		//console.log(button);
		var timer = 400;
		// $parent.removeClass('open');
		$(parent + ' .closable').slideDown(timer, function(){
			button.removeClass('open');
			button.addClass('close');
		});
		var marginTop = $(parent + ' header').css('margin-top');
		$(parent + ' header').animate({'margin-top': '20px'}, timer);
	}
});

// *********************************************************
// section D & E special functions
function resizeColumns(tableId, tableWidth){
	//find no of visible headers
	var length = $(tableId + ' th:visible').length
	$(tableId + ' th, ' + tableId + ' td').css('width', (tableWidth / length) + 'px');
	// redo table corners
}

function checkBoxes(tableId) {
	var visibleButtons = $(tableId + ' th:visible');
	var length = visibleButtons.length;
	var grandparentId = '#' + $(tableId).parent().parent().attr('id');
	$(grandparentId + ' .options input').prop('checked', false);
	for (var i = 0; i < length; i++) {
		var id = $(visibleButtons[i]).attr('id');
		$(grandparentId + ' .options input.' + id).prop('checked', true);
	}
}

// *********************************************************
// section D close buttons
$('#callsWaitingTable th').on('click', 'a.close', function(e){
	// prevent propogation
	e.preventDefault();
	e.stopPropagation();
	var classes = $(e.target.parentNode).attr('class').split(' ')[0];
	if ( $('#callsWaitingTable th:visible').length > 1 )
		$('#callsWaitingTable .' + classes).hide();
	// turn off checkbox
	checkBoxes('#callsWaitingTable');
	// re-size columns
	resizeColumns('#callsWaitingTable', 666);
});

$('#sectionD .options').on('click', 'input:checked', function(){
	var checkClass = $(this).attr('class');
	var colClass = $('#callsWaitingTable #' + checkClass).attr('class').split(' ')[0];
	console.log($('#callsWaitingTable .' + colClass));
	$('#callsWaitingTable .' + colClass).show();
	resizeColumns('#callsWaitingTable', 666);
});
// if this unchecks it eg IT'S CHECKED
$('#sectionD .options').on('click', 'input:not(:checked)', function(){
	var checkClass = $(this).attr('class');
	$('#callsWaitingTable th#' + checkClass + ' a').click();
});

// section E close buttons
$('#activeResourcesTable th').on('click', 'a.close', function(e){
	// prevent propogation
	e.preventDefault();
	e.stopPropagation();
	var classes = $(e.target.parentNode).attr('class').split(' ')[0];
	if ( $('#activeResourcesTable th:visible').length > 1 )
		$('#activeResourcesTable .' + classes).hide();
	// turn off checkbox
	checkBoxes('#activeResourcesTable');
	// re-size columns
	resizeColumns('#activeResourcesTable', 666);
});

// event listenters for section E checkboxes
$('#sectionE .options').on('click', 'input:checked', function(){
	var checkClass = $(this).attr('class');
	var colClass = $('#activeResourcesTable #' + checkClass).attr('class').split(' ')[0];
	console.log($('#activeResourcesTable .' + colClass));
	$('#activeResourcesTable .' + colClass).show();
	resizeColumns('#activeResourcesTable', 666);
});
// if this unchecks it eg IT'S CHECKED
$('#sectionE .options').on('click', 'input:not(:checked)', function(){
	var checkClass = $(this).attr('class');
	$('#activeResourcesTable th#' + checkClass + ' a').click();
});

$(document).ready(function(){ 
	$("#activeResourcesTable").tablesorter();
	$("#callsWaitingTable").tablesorter();
	checkBoxes('#callsWaitingTable');
	checkBoxes('#activeResourcesTable');
});
