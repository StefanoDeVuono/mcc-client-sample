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

var delay = 10;

// ***********************************************************
// find for areas A-E, updates every "delay" number of seconds
// if ( delay > 0 ) {
// 	function ajaxFunc(){
// 		$.ajax({ url: "./find.php", success: function(data){
// 			//Update your dashboard gauge
// 			setData(data);
// 			console.log("result set a-e");
// 			//Setup the next poll recursively
// 			find();
// 		}, dataType: "json"}); // end ajax function and object
// 	}
// 	ajaxFunc();
// 	(function find(){
// 		setTimeout(ajaxFunc, delay * 1000); // end timeout
// 	})();
// } else if ( delay == 0 ) {
// 	(function find(){
// 		$.ajax({ url: "./find.php", success: function(data){
// 			//Update your dashboard gauge
// 			setData(data);

// 		}, dataType: "json", complete: find, timeout: 30000 }); // end ajax object
// 	})();
// }

// function setData(data) {
// 	$('#agent_ready .number').text(data['A']['READY']); // Agents Waiting
// 	$('#agent_total .number').text(data['A']['TOTAL']); // Agents Logged In - TOTAL
// 	$('#agent_incall .number').text(data['A']['INCALL']); // Agents in Calls - INCALL
// 	$('#out_total .number').text(data['A']['ACTIVE']); // current active calls - ACTIVE - total no of rows in vicidial_auto_calls
// 	$('#agent_dead .number').text(data['A']['DEAD']); // Agents in Dead Calls - DEAD -if the callerid field from vicidial_live_agents does not match callerid from vicidial_auto_calls and parked_channels has no rows
// 	$('#agent_paused .number').text(data['A']['PAUSED']); // Paused Agents - PAUSED
// 	$('#out_ring .number').text(data['A']['RINGING']); // Calls Ringing - RINGING - total no of row where status is not LIVE, IVR, CLOSER in vicidial_autocalls 
// 	$('#out_live .number').text(data['A']['WAITING']); // Calls Waiting for Agents - WAITING - total no of rows where status is "LIVE" in vicidial_auto_calls
// 	$('#agent_dispo .number').text(data['A']['DISPO']); // Agents in Despo - DISPO - no of agents who are PAUSED or READY and whose lead_id>1 in vicidial_live_agents

// 	var ansPct = data['C']['ANSWER'] || 0;
// 	var busPct = data['C']['BUSY'] || 0;
// 	var canPct = data['C']['CANCEL'] || 0;
// 	var conPct = data['C']['CONGESTION'] || 0;
// 	$('#answer .number').html(ansPct + '<span class="pct">%</span'); // Answer %
// 	$('#busy .number').html(busPct + '<span class="pct">%</span'); // Busy %
// 	$('#cancel .number').html(canPct + '<span class="pct">%</span'); // Cancel %
// 	$('#congestion .number').html(conPct + '<span class="pct">%</span'); // Congestion %


// 	var length = data['D'].length;
// 	if (length > 0) {
// 		$('#callsWaitingTable div.rows').html('<div class="col1">' + data['D'][0]['campaign_id'] + '</div>'
// 		+ '<div class="col2">' + data['D'][0]['phone_number'] + '</div>'
// 		+ '<div class="col3">' + data['D'][0]['phone_number'] + '</div>'
// 		+ '<div class="col4">' + data['D'][0]['call_type'] + '</div>'
// 		+ '<div class="col5">' + data['D'][0]['queue_priority'] + '</div>');
// 	}
// 	for (var i = 1; i < length; i++) {
// 		$('#callsWaitingTable div.rows').append('<div class="col1">' + data['D'][i]['campaign_id'] + '</div>'
// 		+ '<div class="col2">' + data['D'][i]['phone_number'] + '</div>'
// 		+ '<div class="col3">' + data['D'][i]['status'] + '</div>'
// 		+ '<div class="col4">' + data['D'][i]['call_type'] + '</div>'
// 		+ '<div class="col5">' + data['D'][i]['queue_priority'] + '</div>');
// 	}

// 	var length = data['E'].length;
// 	if (length > 0) {
// 		$('#activeResourcesTable span.rows').html('<div class="col1">' + data['E'][0]['full_name'] + '</div>'
// 		+ '<div class="col2">' + data['E'][0]['user_group'] + '</div>'
// 		+ '<div class="col3">' + data['E'][0]['status'] + '</div>'
// 		+ '<div class="col4">' + data['E'][0]['time'] + '</div>'
// 		+ '<div class="col5">' + data['E'][0]['extension'] + '</div>'
// 		+ '<div class="col6">' + data['E'][0]['campaign_id'] + '</div>'
// 		+ '<div class="col7">' + data['E'][0]['calls_today'] + '</div>'
// 		+ '<div class="col8"><a id="listen" href="#"></a><a id="speak" href="#"></a><a id="shout" href="#"></a></div>');
// 	}
// 	for (var i = 1; i < length; i++) {
// 		$('#activeResourcesTable span.rows').append('<div class="col1">' + data['E'][i]['full_name'] + '</div>'
// 		+ '<div class="col2">' + data['E'][i]['user_group'] + '</div>'
// 		+ '<div class="col3">' + data['E'][i]['status'] + '</div>'
// 		+ '<div class="col4">' + data['E'][i]['time'] + '</div>'
// 		+ '<div class="col5">' + data['E'][i]['extension'] + '</div>'
// 		+ '<div class="col6">' + data['E'][i]['campaign_id'] + '</div>'
// 		+ '<div class="col7">' + data['E'][i]['calls_today'] + '</div>'
// 		+ '<div class="col8"><a id="listen" href="#"></a><a id="speak" href="#"></a><a id="shout" href="#"></a></div>');
// 	}
// }


// // ******************************************************************
// // define function to find by the minute aggregate info for section F
// function findF(){
// 	setTimeout('findFajax(findF)', 60000);
// }

// // ajax function used in findF
// function findFajax(func){
// 	$.ajax({ url: "./findF.php", success: function(data){
// 			$('#dropped_no span').text(data['DROPPED']); // Total Dropped Calls today
// 			$('#answered span').text(data['ANSWERED']); // Total Answered Calls today
// 			$('#dropped h2').html(data['DROPPED_PCT'] + '<span class="pct">%</span'); // Percent of Dropped calls today
// 			$('#agentAvgWait h3').text(data['AGENT_AVG_WAIT']); // A
// 			$('#avgTalkTime h3').text(data['AVG_TALK_TIME']); //
// 			$('#callsToday h3').text(data['TOTAL_CALLS_TODAY']); // Number of Agents' calls today
// 			$('#avgWrap h3').text(data['AVG_WRAP']); // Average Wrap
// 			$('#avgPause h3').text(data['AVG_PAUSE']); // Average Pause
// 			$('#avgAgents h3').text(data['AVG_AGENTS']); // Average Pause
// 			$('#dialableLeeds h3').text(data['DIALABLE_LEADS']); // Average Pause
// 			$('#dialMethod h3').text(data['DIAL_METHOD']); // Average Pause

			
// 		}, dataType: "json"});
// // }

// // execute findF at load
// findFajax();

// // execute findF at 1 minute intervals
// (findF)();

//pause
$('header a.pause').on('click', function(){
	unPause = find;
	find = 'pause';
	unPauseF = findF;
	findF = 'pause';
	$(this).removeClass('pause');
	$(this).addClass('play');
});

$('header a.play').on('click', function(){
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
	console.log(parent);
	$(parent).addClass('closed');
	var button = $(this);
	console.log(button);
	var timer = 400;
	// $parent.removeClass('open');
	$(parent + ' .closable').slideUp(timer, function(){
		button.removeClass('close');
		button.addClass('open');
	});
	$(parent + ' header').animate({'margin-top': '8px'}, timer);

});

$('section').on('click', 'a.open',function(e){
	e.preventDefault();
	parent = '#' + $(this).parent().attr('id');
	console.log(parent);
	$(parent).removeClass('closed');
	var button = $(this);
	console.log(button);
	var timer = 400;
	// $parent.removeClass('open');
	$(parent + ' .closable').slideDown(timer, function(){
		button.removeClass('open');
		button.addClass('close');
	});
	var marginTop = $(parent + ' header').css('margin-top');
	$(parent + ' header').animate({'margin-top': '20px'}, timer);
});