<?php
/**************************************
1. q = the query ID's - comma separated
2. numret = number of levels to traverse up

Returns: parents for each ID
/**********************************/

require "../assets/php/conn.php";		// connection string

/* Process querystring */ 	
$query = " WHERE conceptB_ID = '"; 
$query.=str_replace(",","' OR conceptB_ID = '",$_GET['q']);
$query.="'";
$numret=$_GET['numret'];
/* End process querystring */

$sql="select * FROM ISO.HierarchicalRelationship". $query;

print "$sql <hr>";
/* excute MySQL query and build JSON */
$s = array(); 	// the array we will json encode

function getHierarchy($g,$n){ // if the user specified, get parent up to the number in $levels
	$hier="";
	for ($i = 1; $i <= $n; $i++) { //loop as many times as we have numret
		$sql="select conceptA_ID FROM ISO.HierarchicalRelationship WHERE conceptB_ID =' ". $g . "'";
		$res=MySQL_query($sql) or print("$sql<br>");
		print("$sql<br>");
		while ($ms = MySQL_fetch_assoc($res)) { //build hierarchy 
			// STOP and RETURN VALUE: if conceptA and $g are identical, we've reached the top of the hierarchy!!! 
			if ($ms['conceptA_ID'] == $g){ return $hier; } 
			echo $ms[conceptA_ID];
			$hier.= $ms['conceptA_ID'] . ",";
			$g=$ms['conceptA_ID'];
		} //end while
		
	} //end for
	
return $hier; //send back value
} //end function

$res=MySQL_query($sql) or print('There was an error fetching heriarchy');
while ($ms = MySQL_fetch_assoc($res)) { //build array that will be json encoded 
	
	if (!empty($numret)){ 	 // if multiple levels are requested
		$parents=getHierarchy($ms['conceptB_ID'],$numret); //	if there is more than one level in querystring:
		$parents=rtrim($parents,','); // trim that last comma
		$ms['parents']=$parents;
		$ms['levels_requested']=$numret;
	} else { $ms['parents']=$ms['conceptA_ID']; }  // if no levels, just set parents to conceptA
	
	array_push($s, $ms); // build array of results, allows for building json
	
}

empty($ms);	

$json = json_encode($s); // create the json
print $json; 			// and return the json

/* End PHP */
?> 