<?php

	$array = array();

	$file_handle = fopen("myfile", "r");
	while (!feof($file_handle)) {
		$line = fgets($file_handle);
		$array['number'] = $line;
	}
	fclose($file_handle);

	echo json_encode($array);

?>