<?php

// if (isset($_GET['phone']))				{$phone=$_GET['phone'];}
	// elseif (isset($_POST['phone']))		{$phone=$_POST['phone'];}
// if (isset($_GET['callerid']))			{$callerid=$_GET['callerid'];}
	// elseif (isset($_POST['callerid']))	{$callerid=$_POST['callerid'];}
// if (isset($_GET['extension']))			{$extension=$_GET['extension'];}
	// elseif (isset($_POST['extension']))	{$extension=$_POST['extension'];}

$phone=$_GET['phone'];
$extension=$_GET['extension'];
// $phone=$_POST['phone'];
// $extension=$_POST['extension'];

echo "You are coaching this extension:  $phone";

$socket = fsockopen("192.168.100.51","5038", $errno, $errstr, $timeout);
fputs($socket, "Action: Login\r\n");
fputs($socket, "UserName: cron\r\n");
fputs($socket, "Secret: 1234\r\n\r\n");

fputs($socket, "Action: Originate\r\n");
fputs($socket, "Channel: SIP/$extension\r\n");
fputs($socket, "Exten: 89$phone\r\n");
fputs($socket, "Context: vicidial-auto-external\r\n");
fputs($socket, "CallerID: $callerid\r\n");
fputs($socket, "Timeout: 30000\r\n");
fputs($socket, "Priority: 1\r\n\r\n");

fputs($socket, "Action: Logoff\r\n");
fputs($socket, "Synopsis: Logoff Manager\r\n");
fputs($socket, "Privilege: <none>\r\n");
fputs($socket, "Variables: NONE\r\n");

$wrets=fgets($socket,128);	

?>

</body>

</html>
