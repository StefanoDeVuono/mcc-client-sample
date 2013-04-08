<html>
<style type="text/css">
/* tables */
table.tablesorter {
	font-family:arial;
	background-color: #CDCDCD;
	margin:10px 0pt 15px;
	font-size: 8pt;
	width: 100%;
	text-align: left;
}
table.tablesorter thead tr th, table.tablesorter tfoot tr th {
	background-color: #e6EEEE;
	border: 1px solid #FFF;
	font-size: 8pt;
	padding: 4px;
}
table.tablesorter thead tr .header {
	background-image: url(bg.gif);
	background-repeat: no-repeat;
	background-position: center right;
	cursor: pointer;
}
table.tablesorter tbody td {
	color: #3D3D3D;
	padding: 3px;
	background-color: #FFF;
	vertical-align: top;
}
table.tablesorter tbody tr.odd td {
	background-color:#F0F0F6;
}
table.tablesorter thead tr .headerSortUp {
	background-image: url(asc.gif);
}
table.tablesorter thead tr .headerSortDown {
	background-image: url(desc.gif);
}
table.tablesorter thead tr .headerSortDown, table.tablesorter thead tr .headerSortUp {
background-color: #8dbdd8;
}
</style>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="/jlocal/jquery-latest.js"></script> 
		<script type="text/javascript" src="/jlocal/jquery.tablesorter.js"></script> 
        <script type="text/javascript">
           $(document).ready(function() {		   
    // call the tablesorter plugin 
    $("table").tablesorter({  
	
    }); 
}); 
        </script>
<title>Agent Stats Report</title>
</head>
<?php
if ( isset($_GET["userid"]) ) {
	$userid = "where Name=".$_GET["userid"]."'";
} else {
	$userid = "";
}






$link = $mysql = mysqli_connect('192.168.100.59', 'cron', '1234', 'asteriskrcs', 3306) or die(mysql_error());
       
	echo "Agent Stats Report - This is a Daily Report";

$result = mysqli_query($link, "select * from agent_performance_report $userid;");
$totals2 = mysqli_query($link, "select sum(contacts)'contacts',sum(sales)'sales',sum(XFER)'XFER',sum(CBN)'CBN',sum(SCBN)'SCBN',sum(OBCalls)'OBCalls',sum(ManCalls)'ManCalls',sum(IBCalls)'IBCalls',
       SEC_TO_TIME(sum(TotalTime))'TotalTime',SEC_TO_TIME(sum(OffTime))'OffTime',SEC_TO_TIME(sum(TalkTime))'TalkTime',SEC_TO_TIME(sum(WaitTime))'WaitTime',
       SEC_TO_TIME(sum(WrapTime))'WrapTime',SEC_TO_TIME(sum(DeadTime))'DeadTime',SEC_TO_TIME(sum(ACW))'ACW'
               from agent_performance_report $userid;");
?>
<body>
<table class="tablesorter">
<thead>
<tr> 
<th>Name</th>
<th>User Group</th>
<th>Date</th>
<th>Contacts</th>
<th>Sales</th>
<th>XFER</th>
<th>CBN</th>
<th>SCBN</th>
<th>OB</th>
<th>Man</th>
<th>IB</th>
<th>Total Time</th>
<th>Off</th>
<th>Talk</th>
<th>Wait</th>
<th>Wrap</th>
<th>Dead</th>
<th>AfterCallWork</th>
</tr>
</thead>
<tbody> 
<?php
while($row = mysqli_fetch_array( $result )) {
echo "<tr><td>"; 
echo $row['Name'];
echo "</td><td>"; 
echo $row['UserGroup'];
echo "</td><td>";
echo $row['Date'];
echo "</td><td>"; 
echo $row['Contacts'];
echo "</td><td>"; 
echo $row['Sales'];
echo "</td><td>"; 
echo $row['XFER'];
echo "</td><td>"; 
echo $row['CBN'];
echo "</td><td>"; 
echo $row['SCBN'];
echo "</td><td>"; 
echo $row['OBCalls'];
echo "</td><td>"; 
echo $row['ManCalls'];
echo "</td><td>"; 
echo $row['IBCalls'];
echo "</td><td>"; 
echo $row['TotalTime'];
echo "</td><td>"; 
echo $row['OffTime'];
echo "</td><td>"; 
echo $row['TalkTime'];
echo "</td><td>"; 
echo $row['WaitTime'];
echo "</td><td>"; 
echo $row['WrapTime'];
echo "</td><td>"; 
echo $row['DeadTime'];
echo "</td><td>"; 
echo $row['ACW'];
echo "</td></tr>";}

?>
</tbody> 
<?php while($totals = mysqli_fetch_array( $totals2 )){
echo "<tfoot>";
echo "<tr><td>Name</td><td>UserGRoup</td><td>Date</td><td>"; 
echo $totals['contacts'];
echo "</td><td>";
echo $totals['sales'];
echo "</td><td>";
echo $totals['XFER'];
echo "</td><td>";
echo $totals['CBN'];
echo "</td><td>";
echo $totals['SCBN'];
echo "</td><td>";
echo $totals['OBCalls'];
echo "</td><td>";
echo $totals['ManCalls'];
echo "</td><td>";
echo $totals['IBCalls'];
echo "</td><td>";
echo $totals['TotalTime'];
echo "</td><td>";
echo $totals['OffTime'];
echo "</td><td>";
echo $totals['TalkTime'];
echo "</td><td>";
echo $totals['WaitTime'];
echo "</td><td>";
echo $totals['WrapTime'];
echo "</td><td>";
echo $totals['DeadTime'];
echo "</td><td>";
echo $totals['ACW'];
echo "</td></tr>";
echo "</td></tr>";}
echo "</tfoot>";
mysql_close()?>
</table>
</body>
</html>