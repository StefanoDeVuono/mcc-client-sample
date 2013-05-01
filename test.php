<?php

$teststring = " Inbound LocalTouch Transfer -";
echo "$teststring<br>";
$array =  explode(' ', trim($teststring, ' -'));
print_r($array);
?>