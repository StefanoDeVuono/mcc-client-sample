<?php
  if ( isset($_GET["userid"]))
    $userid = $_GET["userid"];

  ?>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>jQuery UI Tabs - Content via Ajax</title>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css" />
  <script>
  $(function() {
    $( "#tabs" ).tabs({
      beforeLoad: function( event, ui ) {
        ui.jqXHR.error(function() {
          ui.panel.html(
            "Couldn't load this tab. We'll try to fix this as soon as possible. " +
            "If this wouldn't be a demo." );
        });
      }
    });
  });
  </script>
</head>
<body>
 
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Tips</a></li>
    <li><a href="agents_stats_v2.php?userid=<?php echo $userid; ?>">Agent Performance</a></li>
    <li><a href="list_performance.php">List Performance</a></li>
  </ul>
  <div id="tabs-1">
    <p><b>TIP! Sort multiple columns simultaneously by holding down the shift key and clicking a second, third or even fourth column header!</b></p>
  </div>
</div>
 
 
</body>
</html>