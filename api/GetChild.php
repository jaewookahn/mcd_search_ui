<?php
/**************************************
1. q = the query ID's - comma separated
2. numret = number of levels to traverse up

Returns: parents for each ID
/**********************************/

require "../assets/php/conn.php";		// connection string

$try="ga_300038217";
/* Process querystring */ 	
$query = " WHERE conceptA_ID = '"; 
$query.=str_replace(",","' OR conceptA_ID = '",$try);
$query.="'";
/* End process querystring */
$sql="select * FROM ISO.HierarchicalRelationship". $query;

print $sql. "<P>";

/* excute MySQL query and build JSON */
$s = array(); 	// the array we will json encode

$res=MySQL_query($sql) or print('There was an error fetching notes');

while ($ms = MySQL_fetch_assoc($res)) { //build array that will be json encoded 
	array_push($s, $ms); // build array of results, allows for building json
	
}
empty($ms);	


echo 'Child: ' . $s[0]['conceptB_ID'] . '<br>';
$json = json_encode($s); // create the json
print $json; 			// and return the json

/* End PHP */
?> 