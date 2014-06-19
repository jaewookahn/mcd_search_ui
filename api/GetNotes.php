<?php 
/**************************************
1. Query: q = the query ID's - comma separated

Returns: scope notes for each ID
/**********************************/

require "../assets/php/conn.php";		// connection string

/* Process querystring */ 	
$query = " WHERE conceptID = '"; 
$query.=str_replace(",","' OR conceptID = '",$_GET['q']);
$query.="'";
/* End process querystring */

$sql ="SELECT * from ISO.Note" . $query;

/* excute MySQL query and build JSON */
$s = array(); 	// the array we will json encode

$res=MySQL_query($sql) or print('There was an error fetching notes');

while ($ms = MySQL_fetch_assoc($res)) { //build array that will be json encoded 
	array_push($s, $ms); // build array of results, allows for building json
}
empty($ms);	

$json = json_encode($s); // create the json
print $json; 			// and return the json

/* End PHP */
?> 