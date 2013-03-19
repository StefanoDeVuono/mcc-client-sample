var delay = 0

if (delay == 0) {
	(function poll(){
		$.ajax({ url: "find.php", success: function(data){
			// how does it do the date? $NOW_TIME = date("m/d/Y H:i:s");

			//if (data) { // if the data set is not empty update the values
				//update time
				$('#date').text(data['TIME']);
				
				// control the colour of the arrows and update READY Agents Waiting
				var old_agent_ready = $('#agent_ready .number').text();
				var agent_ready = data['READY']; // increment
				$('#agent_ready .number').text(agent_ready); // READY Agents Waiting
				var delta_agent_ready = agent_ready - old_agent_ready;
				if (delta_agent_ready > 0) {
					$('#agent_ready .plus').text('+' + delta_agent_ready);
					$('.arrow.up').removeClass().addClass('arrow up red')
					$('.arrow.down').removeClass().addClass('arrow down green')
				} else if (delta_agent_ready < 0) {
					$('#agent_ready .plus').text(delta_agent_ready);
					$('.arrow.up').removeClass().addClass('arrow up green')
					$('.arrow.down').removeClass().addClass('arrow down red')
				}
				else if (delta_agent_ready == 0) {
					$('#agent_ready .plus').text(delta_agent_ready);
					$('.arrow.up').removeClass().addClass('arrow up grey')
					$('.arrow.down').removeClass().addClass('arrow down grey')
				}

				//Update your dashboard gauge with the rest of the values
				$('#agent_total .number').text(data['TOTAL']); // Agents Logged In - TOTAL
				$('#agent_incall .number').text(data['INCALL']); // Agents in Calls - INCALL
				$('#out_total .number').text(data['ACTIVE']); // current active calls - ACTIVE - total no of rows in vicidial_auto_calls
				$('#agent_dead .number').text(data['DEAD']); // Agents in Dead Calls - DEAD -if the callerid field from vicidial_live_agents does not match callerid from vicidial_auto_calls and parked_channels has no rows
				$('#agent_paused .number').text(data['PAUSED']); // Paused Agents - PAUSED
				$('#out_ring .number').text(data['RINGING']); // Calls Ringing - RINGING - total no of row where status is not LIVE, IVR, CLOSER in vicidial_autocalls 
				$('#out_live .number').text(data['WAITING']); // Calls Waiting for Agents - WAITING - total no of rows where status is "LIVE" in vicidial_auto_calls
				$('#agent_dispo .number').text(data['DISPO']); // Agents in Despo - DISPO - no of agents who are PAUSED or READY and whose lead_id>1 in vicidial_live_agents
			//} // end if data
		}, dataType: "json", complete: poll, timeout: 20000 });
	})();
}