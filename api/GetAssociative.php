<?php
/**************************************
1. q = the query ID's - comma separated
2. numret = number of levels to traverse up

Returns: parents for each ID
/**********************************/

require "../assets/php/conn.php";		// connection string

/* Process querystring */ 	
$query = " WHERE conceptA_ID = '"; 
$query.=str_replace(",","' OR conceptA_ID = '",$_GET['q']);
$query.="'";


$query2 = " OR conceptB_ID = '"; 
$query2.=str_replace(",","' OR conceptB_ID = '",$_GET['q']);
$query2.="'";



/* End process querystring */


// select associationType as at from Asso 
$sql="select *,ISO.AssociationType.description FROM ISO.AssociativeRelationship 
LEFT JOIN ISO.AssociationType ON ISO.AssociativeRelationship.associationType = ISO.AssociationType.associationType" . $query . $query2;

print $sql. "<P>";

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