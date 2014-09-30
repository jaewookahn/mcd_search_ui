<?php
//$q=htmlspecialchars($_GET["query"]);
$gt = $_GET['getty_terms']; // get the cids sent from form
// array_push($gt, "1344472", "1348238");
foreach ($gt as $g){ 
	$cids .=$g ."+";
}
 	$url = "http://rack90.cs.drexel.edu/search/mcd_qe_api/api.cgi?mode=qe&cids=$cids";
	$json = file_get_contents($url);
	$data = json_decode($json, TRUE);


//print_r($data);

/* NEW 
foreach ($data['records'] as $z){
	echo '<div class="checkbox"><label><input type="checkbox" checked name="images[]" value="' . $z['artstor_string'] . '"> ' . $z['artstor_string'] . '</label></div>';
}
*/
$i = 1;
foreach ($data['records'] as $z){
	#echo $z['artstor_string'] . "&#10;";
	echo $z['artstor_string'] . "\n";
}


	



?>
