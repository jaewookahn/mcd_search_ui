<?php 
/**************************************
1. Query: q = the query ID's - comma separated

Returns: scope notes for each ID
/**********************************/

require "../assets/php/conn.php";		// connection string
 
	$sql ="SELECT * from ISO.Note WHERE conceptID='" . $_GET['q'] . "' LIMIT 1";
	$r=MySQL_query($sql);
	while ($notes = MySQL_fetch_assoc($r)) { // print the note(s)
		echo $notes['text'] . " (" . $notes['noteType'] . ")<br />";	
	}
	
?> 