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
	<title>Local Touch Report</title>
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
    </head>
    <body>

<html>

<?php
$link = $mysql = mysql_connect('192.168.100.59', 'cron', '1234', 'asteriskrcs') or die(mysql_error());
        mysql_select_db('asteriskrcs', $mysql);
	echo "Local Touch Report - this is a Daily Report<br>";	
	
$result = mysql_query("select Name,UserGroup,Date,LocalTouchIB,Xfers,round(Xfers/LocalTouchIB*100,1) 'LTXferRatio',XFERS_Sold,
(case when Xfers=0 then 0.0 else round(XFERS_Sold/Xfers*100,1) end) 'LTXfersSold',
       round(XFERS_Sold/LocalTouchIB*100,1) 'LTSoldRatio',ValidSource,Drops,round(Drops/LocalTouchIB*100,1) 'Drop%',AveHoldTime,Sales 
from agent_local_touch ;");

$result1 = mysql_query("select Date,sum(LocalTouchIB),sum(Xfers),round(sum(Xfers)/sum(LocalTouchIB)*100,1) 'LTXferRatio',sum(XFERS_Sold),
(case when sum(Xfers)=0 then 0.0 else round((sum(XFERS_Sold)/sum(Xfers))*100,1) end) 'LTXfersSold',
round(sum(XFERS_Sold)/sum(LocalTouchIB)*100,1) 'LTSoldRatio',sum(ValidSource),sum(Drops),round(sum(Drops)/sum(LocalTouchIB)*100,1) 'Drop%',
(sum(AveHoldTime)/count(*)),sum(Sales) 
from agent_local_touch;");

?>
<body>
 <table class="tablesorter">
	
 <thead>
<tr>
<th>Name</th>
<th>UserGroup</th>
<th>Date</th>
<th>LocalTouchIB</th>
<th>LTXfers</th>
<th>LTXferRatio</th>
<th>LTXfersSold</th>
<th>LTSoldRatio</th>
<th>ValidSource</th>
<th>Drops</th>
<th>Drop%</th>
<th>AveHoldTime</th>
<th>Sales</th>
</tr>
</thead>

<tbody> 
<?php
while($row = mysql_fetch_array( $result )) {
echo "<tr><td>"; 
echo $row['Name'];
echo "</td><td>"; 
echo $row['UserGroup'];
echo "</td><td>"; 
echo $row['Date'];
echo "</td><td>"; 
echo $row['LocalTouchIB'];
echo "</td><td>"; 
echo $row['Xfers'];
echo "</td><td>"; 
echo $row['LTXferRatio'];
echo "</td><td>"; 
echo $row['XFERS_Sold'];
echo "</td><td>"; 
echo $row['LTSoldRatio'];
echo "</td><td>"; 
echo $row['ValidSource'];
echo "</td><td>"; 
echo $row['Drops'];
echo "</td><td>"; 
echo $row['Drop%'];
echo "</td><td>"; 
echo $row['AveHoldTime'];
echo "</td><td>"; 
echo $row['Sales'];
echo "</td></tr>";}
?>

</tbody> 
<?php while($totals = mysql_fetch_array( $result1 )){
echo "<tfoot>";
echo "<tr><td>Totals: Name</td><td>User Group</td><td>Date</td><td>"; 
echo $totals['sum(LocalTouchIB)'];
echo "</td><td>";
echo $totals['sum(Xfers)'];
echo "</td><td>";
echo $totals['LTXferRatio'];
echo "</td><td>";
echo $totals['sum(XFERS_Sold)'];
echo "</td><td>";
echo $totals['LTSoldRatio'];
echo "</td><td>";
echo $totals['sum(ValidSource)'];
echo "</td><td>";
echo $totals['sum(Drops)'];
echo "</td><td>";
echo $totals['Drop%'];
echo "</td><td>";
echo $totals['sum(AveHoldTime)'];
echo "</td><td>";
echo $totals['sum(Sales)'];
echo "</td></tr>";}
echo "</tfoot>";
mysql_close()?>
</table>
</body>
</html>