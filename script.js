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

var sortOrder = "";
	if ( $(this).hasClass('desc') ) {
		// change it to ascending
		$(this).removeClass('desc');
		$(this).addClass('asc')
		sortOrder = 'asc';
	} else {
		// change it to descending
		$(this).removeClass('asc');
		$(this).addClass('desc')
		sortOrder = 'desc';
	}

// default sort field for sectionD
var dSorter = 'campaign';
// listener to change sort field for sectionD
$('#callsWaitingTable button').on('click', function(){
	//$(this).addClass('clicked');
	dSorter = $(this).attr('id');

	// run ajax once to re-sort
	$.ajax({ url: "./find.php",
		data: {dSort: dSorter, dOrder: dOrder},
		success: function(data){
			//Update your dashboard gauge
			setDataD(data);
		}, dataType: "json"});
});
var dOrder = "asc";
// listener to change sort order for sectionD
$('#callsWaitingTable button').on('click', 'a.sort', function(){
	$(this).parent().trigger('click');
	if ( $(this).hasClass('desc') ) {
		// change it to ascending
		$(this).removeClass('desc');
		$(this).addClass('asc')
		dOrder = 'asc';
	} else {
		// change it to descending
		$(this).removeClass('asc');
		$(this).addClass('desc')
		dOrder = 'desc';
	}
});

// default sort field for sectionE
var eSorter = 'user';

// listeners to change sort field for sectionD
$('#activeResourcesTable button').on('click', function(){
	//$(this).addClass('clicked');
	eSorter = $(this).attr('id');

	// run ajax once to re-sort
	$.ajax({ url: "./find.php",
		data: {eSort: eSorter, eOrder: eOrder},
		success: function(data){
			//Update your dashboard gauge
			setDataE(data);
		}, dataType: "json"});
});
var eOrder = "asc";
// listener to change sort order for sectionD
$('#activeResourcesTable button').on('click', 'a.sort', function(){
	$(this).parent().trigger('click');
	if ( $(this).hasClass('desc') ) {
		// change it to ascending
		$(this).removeClass('desc');
		$(this).addClass('asc')
		eOrder = 'asc';
	} else {
		// change it to descending
		$(this).removeClass('asc');
		$(this).addClass('desc')
		eOrder = 'desc';
	}
});

// ***********************************************************
// find for areas A-E, updates every "delay" number of seconds
if ( delay > 0 ) {
	function find(){
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
			//Setup the next poll recursively
			find();
		}, dataType: "json"}); // end ajax function and object
	}
	(find)(); // auto run lookup after every deley seconds
} else if ( delay == 0 ) {
	(function find(){
		$.ajax({ url: "./find.php",
		data: {dSort: dSorter, dOrder: dOrder, eSort: eSorter, eOrder: eOrder},
		success: function(data){
			//Update your dashboard gauge
			setDataA(data);
			setDataC(data);
			setDataD(data);
			setDataE(data);

		}, dataType: "json", complete: find, timeout: 30000 }); // end ajax object
	})();
}


//echo $_GET["key1"];

// when you click on it run sort function with section you want to sort
// $('#callsWaitingTable button').on('click', 'a.sort', function(){

// })

// function ajaxSort(section){
// 	$.ajax({ url: "./find.php", success: function(data){
// 		if (section)
// 	}})
// }



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
		row += '<div class="col col1">' + data['D'][i]['campaign'] + '</div>';
		row += '<div class="col col2">' + data['D'][i]['phone'] + '</div>';
		row += '<div class="col col3">' + data['D'][i]['time'] + '</div>';
		row += '<div class="col col4">' + data['D'][i]['callType'] + '</div>';
		row += '<div class="col col5">' + data['D'][i]['priority'] + '</div>';

		if ( $('#callsWaitingTable button.col1').is(':hidden') ) {
			$('div.col1').hide();
		}
		if ( $('#callsWaitingTable button.col2').is(':hidden') ) {
			$('div.col2').hide();
		}
		if ( $('#callsWaitingTable button.col3').is(':hidden') ) {
			$('div.col3').hide();
		}
		if ( $('#callsWaitingTable button.col4').is(':hidden') ) {
			$('div.col4').hide();
		}
		if ( $('#callsWaitingTable button.col5').is(':hidden') ) {
			$('div.col5').hide();
		}
	}
	
	$('#callsWaitingTable div.rows').html(row);
	columnCheckD();
}

function setDataE(data) {
	var length = data['E'].length;

	var row = "";
	col8 = "";
	for (var i = 0; i < length; i++) {
		if ( $('#activeResourcesTable button.col1').is(':hidden') )
			row += '<div class="col col" style="display: none">' + data['E'][i]['user'] + '</div>';
		else
			row += '<div class="col col1">' + data['E'][i]['user'] + '</div>';
		
		if ( $('#activeResourcesTable button.col2').is(':hidden') )
			row += '<div class="col col2" style="display: none">' + data['E'][i]['group'] + '</div>';
		else
			row += '<div class="col col2">' + data['E'][i]['group'] + '</div>';

		if ( $('#activeResourcesTable button.col3').is(':hidden') )
			row += '<div class="col col3" style="display: none">' + data['E'][i]['status'] + '</div>';
		else
			row += '<div class="col col3">' + data['E'][i]['status'] + '</div>';

		if ( $('#activeResourcesTable button.col4').is(':hidden') )
			row += '<div class="col col4" style="display: none">' + data['E'][i]['time'] + '</div>';
		else
			row += '<div class="col col4">' + data['E'][i]['time'] + '</div>';

		if ( $('#activeResourcesTable button.col5').is(':hidden') )
			row += '<div class="col col5" style="display: none">' + data['E'][i]['phone'] + '</div>';
		else
			row += '<div class="col col5">' + data['E'][i]['phone'] + '</div>';

		if ( $('#activeResourcesTable button.col6').is(':hidden') )
			row += '<div class="col col6" style="display: none">' + data['E'][i]['campaign'] + '</div>';
		else
			row += '<div class="col col6">' + data['E'][i]['campaign'] + '</div>';

		if ( $('#activeResourcesTable button.col7').is(':hidden') )
			row += '<div class="col col7" style="display: none">' + data['E'][i]['calls'] + '</div>';
		else
			row += '<div class="col col7">' + data['E'][i]['calls'] + '</div>';
		//row += col8;
	}
	$('#activeResourcesTable span.rows').html(row);
	columnCheckE();
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

			findF();
		}, dataType: "json"});
}

// ***********************************************************
// Pause buttons
$('header a.pause').on('click', function(){
	storeDelay = delay;
	unPause = find;
	delay = 3600;
	find = 'pause';
	unPauseF = findF;
	findF = 'pause';
	$(this).removeClass('pause');
	$(this).addClass('play');
});

$('header a.play').on('click', function(){
	delay = storeDelay;
	find = unPause;
	findF = unPauseF;
	$(this).removeClass('play');
	$(this).addClass('pause');
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
// section D close buttons
checkBoxCheckerD();
$('#callsWaitingTable .col').on('click', 'a.close', function(){
	var classes = $(this).parent().attr('class').replace('col ','');
	//console.log(classes);
	$('#callsWaitingTable .'+ classes).hide();
	columnCheckD();
	checkBoxCheckerD();
})

// section D radio buttons (checkboxes)
// if this checks it eg IT'S UNCHECKED
$('#sectionD .options').on('click', 'input:checked', function(){
	var classes = $(this).attr('class');
	classes = $('#callsWaitingTable #' + classes).attr('class').replace('col ','');
	console.log($('#callsWaitingTable .' + classes));
	$('#callsWaitingTable .' + classes).show();
});
// if this unchecks it eg IT'S CHECKED
$('#sectionD .options').on('click', 'input:not(:checked)', function(){
	var classes = $(this).attr('class');
	classes = $('#callsWaitingTable #' + classes).attr('class').replace('col ','');
	console.log($('#callsWaitingTable .' + classes));
	$('#callsWaitingTable .' + classes).hide();
});

//for visible buttons make sure check boxes are checked.
function checkBoxCheckerD() {
	$('#sectionD .options input').prop('checked', false);
	var visibleButtons = $('#callsWaitingTable button:visible')
	var length = visibleButtons.length
	for (var i = 0; i < length; i++) {
		var id = $(visibleButtons[i]).attr('id');
		$('#sectionD .options input.' + id).prop('checked', true);
	}
}
function columnCheckD() {// closing columns
	var length = $('#callsWaitingTable button:visible').length;
	var pct = 100 / length
	$('#callsWaitingTable .col').css('width', pct + '%');
}

checkBoxes();


// section E close buttons
$('#activeResourcesTable .col').on('click', 'a.close', function(){
	var classes = $(this).parent().attr('class').replace('col ','');
	console.log(classes);
	$('#activeResourcesTable .'+ classes).hide();
	columnCheckE();
	checkBoxes();
	var id = $(this).parent().attr('id');
	$('#sectionE .' + id).prop('checked',false);
});

// section E radio buttons (checkboxes)
// if this checks it eg IT'S UNCHECKED show column
$('#sectionE .options').on('click', 'input:checked', function(){
	var classes = $(this).attr('class');
	classes = $('#activeResourcesTable #' + classes).attr('class').replace('col ','');
	console.log($('#activeResourcesTable .' + classes));
	$('#activeResourcesTable .' + classes).show();
	columnCheckE();
});
// if this unchecks it eg IT'S CHECKED
$('#sectionE .options').on('click', 'input:not(:checked)', function(){
	var classes = $(this).attr('class');
	classes = $('#activeResourcesTable #' + classes).attr('class').replace('col ','');
	console.log($('#activeResourcesTable .' + classes));
	$('#activeResourcesTable .' + classes).hide();
	columnCheckE();
});


//for visible buttons make sure check boxes are checked.
function checkBoxes() {
	var visibleButtons = $('#activeResourcesTable button:visible')
	var length = visibleButtons.length;
	for (var i = 0; i < length; i++) {
		var id = $(visibleButtons[i]).attr('id');
		$('#sectionE .options input.' + id).prop('checked', true);
	}
}
var tableWidth = 666;
// closing columns
function columnCheckE() {
	var length = $('#activeResourcesTable button:visible').length;
	$('#activeResourcesTable .col').css('width', tableWidth / length + 'px');
	greyRows(length);
}

greyRows(7);

function greyRows(width) {
	var length = $('#activeResourcesTable div.col').length;
	var twiceWidth = 7 * 2;
	for (var i = 1; i < length; i++) {
		$('#activeResourcesTable div.col').slice(14*i-7, 7*i).css('background', 'grey');
	}
}
