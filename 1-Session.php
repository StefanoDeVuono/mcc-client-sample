<?php



echo $_SERVER['PHP_AUTH_USER'];
echo "<br><br>";
echo $_SERVER['PHP_AUTH_PW'];
echo "<br><br>";
echo $_SERVER['PHP_SELF'];
echo "<br><br>";
echo getenv("QUERY_STRING");




?>