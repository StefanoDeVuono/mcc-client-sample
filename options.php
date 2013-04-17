<?php

include 'database.php';

if (preg_match('/ALL/', $allowed_campaigns) == 1 ) {
	$stmt = "SELECT campaign_id from vicidial_campaigns where active='Y'";
	$result = mysqli_query($db, $stmt);
	$select_campaigns = [];
	while ($row = mysqli_fetch_row($result)) {
        array_push($select_campaigns, $row[0]);
    }
}

echo '{"selectCampaigns": ';
echo json_encode($select_campaigns);
echo ', "time": ';
echo json_encode(time());
echo '}';
?>