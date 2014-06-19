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
$source=$_GET['source'];  //
$type=$_GET['type'];     // whether limit to preferred terms
if ($source!=null){
  $query= "term like '%" . $query . "%' and sourceVocab like '%" . $source . "' ";
}else{
  $query= "term like '" . $query . "%' ";
}
if ($type!=null){
  $query= $query . " and preferred = '" . $type .  "' ";
}
print $query . "\n";

/* End process querystring */

$sql ="SELECT * from ISO.ThesaurusTerm WHERE " . $query . " ORDER BY term";
print $sql . "\n";

/* excute MySQL query and build JSON */

$res = MySQL_query($sql) or DIE('There was an error with the query');       
$s = array(); 	// the array we will json encode
while ($ms = MySQL_fetch_assoc($res)) { //build array that will be json encoded
	array_push($s, $ms); // build array of results, allows for building json
}
empty($ms);	

$json = json_encode($s); // create the json
print $json; 			// and return the json
 
 
/* End PHP */
?>
