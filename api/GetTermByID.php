<?php 
/**************************************
1. q = the query term (*required)
2. start = the first result returned (optional)
3. numret = number of results to return (optional)

Returns: variant terms
/**********************************/

require "../assets/php/conn.php";		// connection string

/* Process querystring */

$query=$_GET['q']; 	// the text query

/* End process querystring */

$sql ="SELECT * from ISO.ThesaurusTerm WHERE identifier ='" . $query . "'";

/* excute MySQL query and build JSON */

$res = MySQL_query($sql) or DIE('There was an error with the query');       
$s = array(); 	// the array we will json encode
while ($ms = MySQL_fetch_assoc($res)):  //build array that will be json encoded
	array_push($s, $ms); // build array of results, allows for building json
endwhile;
empty($ms);	

$json = json_encode($s); // create the json
print (json_encode($s)); 			// and return the json
 
 
/* End PHP */
?>
