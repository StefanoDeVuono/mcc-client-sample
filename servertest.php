<?php

if (isset($_GET["types"]))				{$types=$_GET["types"];}
	elseif (isset($_POST["types"]))		{$types=$_POST["types"];}
	if (!isset($types))			{$types='SHOW ALL CAMPAIGNS';}


?>