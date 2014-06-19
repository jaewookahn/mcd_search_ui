<?php 
/**************************************
1. Query: q = the query ID's (*required)

Returns: coordinates for each ID
/**********************************/

require "../assets/php/conn.php";		// connection string

/* Process querystring */ 	
$query = " WHERE SUBJECT_ID = '"; 
$query.=str_replace(",","' OR SUBJECT_ID = '",$_GET['q']);
$query.="'";

/* End process querystring */
$sql = "SELECT SUBJECT_ID, LAT_DECIMAL, LAT_DEGREE, LAT_MIN, LAT_DIRECTION, LONG_DECIMAL, LONG_DEGREE, LONG_MIN, LONG_DIRECTION FROM getty_tgn.coordinates" . $query ;

/* excute MySQL query and build JSON */
$s = array(); 	// the array we will json encode

$res=MySQL_query($sql) or print('There was an error fetching coordinates');
while ($ms = MySQL_fetch_assoc($res)) { //build array that will be json encoded 
	array_push($s, $ms); // build array of results, allows for building json
}
empty($ms);	

$json = json_encode($s); // create the json
print $json; 			// and return the json

/* End PHP */
?>