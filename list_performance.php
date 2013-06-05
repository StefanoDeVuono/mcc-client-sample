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
	<title>List Performance Report</title>
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
        <title>List Performance Report</title>
    </head>
    <body>

<html>

<?php
$link = $mysql = mysql_connect('192.168.100.59', 'cron', '1234', 'asteriskrcs') or die(mysql_error());
        mysql_select_db('asteriskrcs', $mysql);
	echo "List Performance Report - this is a Daily Report <br>";	

$result = mysql_query("select ChildCampaign 'List',Groups 'Group',sum(TotalCalls) 'Total Calls',sum(SalesMade) 'Sales',
	CAST(FORMAT(IFNULL(sum(SalesMade)/sum(ReachedBuyer)*100,0),2) as DECIMAL(5,0)) 'Sale-Cont%',
	CAST(FORMAT(IFNULL(sum(SalesMade)/sum(TotalCalls)*100,0),2) as DECIMAL(5,2)) 'Sale-Call%',
	CAST(FORMAT(IFNULL(sum(TotalCalls)/sum(SalesMade),0),2) as DECIMAL(5,0)) 'Calls-Sale',
	sum(Transfer) 'Transfers',CAST(FORMAT(IFNULL(sum(Transfer)/sum(TotalCalls)*100,0),2) as DECIMAL(5,0)) 'Transfer Rate',
	sum(ReachedBuyer) 'Contacts',CAST(FORMAT(IFNULL(sum(ReachedBuyer)/sum(TotalCalls)*100,0),2) as DECIMAL(5,0)) 'Contact%'
	from a_rcs_campaign_list_count_week group by ChildCampaign,Groups having sum(TotalCalls) > 20
	order by sum(SalesMade) desc,ChildCampaign;");

$totals2 = mysql_query("select sum(TotalCalls) 'Total Calls',sum(SalesMade) 'Sales',
      CAST(FORMAT(IFNULL(sum(SalesMade)/sum(ReachedBuyer)*100,0),2) as DECIMAL(5,0)) 'Sale-Cont%',
      CAST(FORMAT(IFNULL(sum(SalesMade)/sum(TotalCalls)*100,0),2) as DECIMAL(5,2)) 'Sale-Call%',
      CAST(FORMAT(IFNULL(sum(TotalCalls)/sum(SalesMade),0),2) as DECIMAL(5,0)) 'Calls-Sale',
      sum(Transfer) 'Transfers',CAST(FORMAT(IFNULL(sum(Transfer)/sum(TotalCalls)*100,0),2) as DECIMAL(5,0)) 'Transfer Rate',
      sum(ReachedBuyer) 'Contacts',CAST(FORMAT(IFNULL(sum(ReachedBuyer)/sum(TotalCalls)*100,0),2) as DECIMAL(5,0)) 'Contact%'
      from a_rcs_campaign_list_count_week having sum(TotalCalls) > 20
      order by sum(SalesMade) desc,ChildCampaign;");	

?>
<body>
 <table class="tablesorter">
	
 <thead>
<tr>
<th>List</th>
<th>User Group</th>
<th>Total Calls</th>
<th>Sales</th>
<th>Sale-Cont%</th>
<th>Sale-Call%</th>
<th>Calls-Sale</th>
<th>Transfers</th>
<th>Transfer Rate</th>
<th>Contacts</th>
<th>Contact%</th>
</tr>
</thead>

<tbody> 
<?php
while($row = mysql_fetch_array( $result )) {
echo "<tr><td>"; 
echo $row['List'];
echo "</td><td>"; 
echo $row['Group'];
echo "</td><td>"; 
echo $row['Total Calls'];
echo "</td><td>"; 
echo $row['Sales'];
echo "</td><td>"; 
echo $row['Sale-Cont%'];
echo "</td><td>"; 
echo $row['Sale-Call%'];
echo "</td><td>"; 
echo $row['Calls-Sale'];
echo "</td><td>"; 
echo $row['Transfers'];
echo "</td><td>"; 
echo $row['Transfer Rate'];
echo "</td><td>"; 
echo $row['Contacts'];
echo "</td><td>"; 
echo $row['Contact%'];
echo "</td></tr>";}
?>

</tbody> 
<?php while($totals = mysql_fetch_array( $totals2 )){
echo "<tfoot>";
echo "<tr><td>Totals: List</td><td>User Group</td><td>"; 
echo $totals['Total Calls'];
echo "</td><td>";
echo $totals['Sales'];
echo "</td><td>";
echo $totals['Sale-Cont%'];
echo "</td><td>";
echo $totals['Sale-Call%'];
echo "</td><td>";
echo $totals['Calls-Sale'];
echo "</td><td>";
echo $totals['Transfers'];
echo "</td><td>";
echo $totals['Transfer Rate'];
echo "</td><td>";
echo $totals['Contacts'];
echo "</td><td>";
echo $totals['Contact%'];
echo "</td></tr>";
echo "</td></tr>";}
echo "</tfoot>";
mysql_close()?>
</table>
</body>
</html>