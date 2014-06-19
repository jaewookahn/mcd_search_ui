<?php 
/**************************************
1. q = the query term (*required)
2. start = the first result returned (optional)
3. numret = number of results to return (optional)

Returns: related terms (Descending order, as scored by the MySQL fulltext search) 
/**********************************/

require "../assets/php/conn.php";		// connection string

/* Process querystring */

$query=$_GET['q']; 	// the text query

/* Limits and start for pagination and or end user control */
if ($_GET['numret']){ $l = " LIMIT " . $_GET['numret']; }

if ($_GET['start']) { 
	if (empty($_GET['numret'])){ $_GET['numret']=20; } // if start is specified, set numret 20 as default.
	$l = " LIMIT " . $_GET['start'] . "," . $_GET['numret']; 
} 

/* End process querystring */

/* excute MySQL query and build JSON */

$sql ="SELECT DISTINCT *, MATCH (term) AGAINST ('$query') AS score
FROM ISO.ThesaurusTerm WHERE MATCH (term) AGAINST ('$query') order by score DESC" . $l;

$res = MySQL_query($sql) or DIE('There was an error with the query');       
$s = array(); 	// the array we will json encode
while ($ms = MySQL_fetch_assoc($res)) { //build array that will be json encoded
	//echo $ms[conceptID] . " - "; 
	array_push($s, $ms); // build array of results, allows for building json
}
empty($ms);	

$json = json_encode($s); // create the json
print $json; 			// and return the json

 
/* End PHP */
?>