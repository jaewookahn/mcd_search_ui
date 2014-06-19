<?php 
header("Content-type: text/html; charset=utf-8");
//connect to server
$link = MySQL_connect('localhost', 'phpuser', 'rushbldg') or die(mysql_error(1));
mysql_set_charset('utf8',$link);
?>