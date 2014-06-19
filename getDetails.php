<?php
$cid=htmlspecialchars($_GET["id"]);
$url = "http://mcd.ischool.drexel.edu:8080/MCD3/GetConceptBasic?cid=$cid";
		$json = file_get_contents($url);
		$data = json_decode($json, TRUE);
		
print '<div class="panel panel-default"><div class="panel-heading"><strong>' . $data['preferredstring']['string'] . '</strong>  </div> <div class="panel-body">';

echo "<strong>Notes:</strong><br>";
foreach ($data['notes'] as $z){
	if ($z['language'] == "English") { print $z['noteText'] . "<br><br>"; } 
}


// hierarchy

echo "<strong>Hierarchy: </strong><br>";
print $data['hierarchy']['parent']['conceptString'] . " " . $data['hierarchy']['parent']['conceptID'];
foreach ($data['hierarchy']['parent']['siblings'] as $z){ 

	//print $data['hierarchy']['parent']['siblings'][0]['conceptString'];
	if ($z['conceptString'] == $data['preferredstring']['string']){
		echo "<br>. <strong>" . $z['conceptString'] . " " . $z['conceptID'] . "</strong>";
		echo '<a href="#" title ="' . $z['conceptID'] . '" data-term="' . $z['conceptString'] . '" class="insert">
							<span class="glyphicon glyphicon glyphicon-search pull-right"></span></a>';
	} 
	else {
		echo "<br> . " . $z['conceptString'] . " " . $z['conceptID'] ;
		echo '<a href="#" title ="' . $z['conceptID'] . '" data-term="' . $z['conceptString'] . '" class="insert">
							<span class="glyphicon glyphicon glyphicon-search pull-right"></span></a>';
	}
	// all children
	foreach ($z['children'] as $y){ 
		echo "<br> ... " . $y['conceptString'] . " " . $z['conceptID'] ;
		echo '<a href="#" title ="' . $z['conceptID'] . '" data-term="' . $z['conceptString'] . '" class="insert">
							<span class="glyphicon glyphicon glyphicon-search pull-right"></span></a>';
	}
		
}



/* echo "<br><br>Associated Concepts:<br>";
foreach ($data['associations'] as $z){
	echo $z['associationString'] . "<br>";
}
foreach ($data['terms'] as $z){
	echo $z['strings'][0][0] . "<br>";
}
*/




//echo "<HR>";	
//print_r($data);



echo "</div></div>"; // end bootstrap panel	
?>