delay = 30;

if (delay == 0) {
	(function poll(){
			$.ajax({ url: "find.php", success: function(data){
				//stuff
				$('article').text(data['number']);
			}, dataType: "json", complete: poll, timeout: 20000 });
	})();
} else if (delay == 30) {
	(function poll(){
	   setTimeout(function(){
	      $.ajax({ url: "find.php", success: function(data){
	        $('article').text(data['number']);
	        poll();
	      }, dataType: "json"});
	  }, 30000);
	})();
}