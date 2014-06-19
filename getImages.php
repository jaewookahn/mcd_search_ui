<?php
//$q=htmlspecialchars($_GET["query"]);
$q=$_GET["query"]; 
//url = "http://mcd.ischool.drexel.edu/ahn/mcd_qe/search_artstor.cgi?query=" . $q;

$url = "http://mcd.ischool.drexel.edu/ahn/mcd_qe/search_artstor.cgi?query=watercolor%20on%20silk";
		$json = file_get_contents($url);
		$data = json_decode($json, TRUE);


//print_r($data);
$i = 1;
echo '<div class ="row">';
foreach ($data['records'] as $z){
	echo '<div class="col-md-3">';
		$zurl = str_replace("size0","size1",$z['url']); // change size from smallest to one larger
		echo '<img src="' . $zurl . ' " class="thumbnail">';
		
		echo "<div class=\"caption\"><strong>" . $z['Title'] . "</strong><br>Material:". $z['Material'] . "";
	echo '</div></div>';
	
	if ($i % 4 == 0) { echo "</div>\r\n" . '<hr><div class="row">';};
	$i++;
}
echo "</div></div>";
	
/* '<div class="panel panel-default"><div class="panel-heading"><strong>' . $data['preferredstring']['string'] . '</strong>  </div> <div class="panel-body">';


echo "<strong>Notes:</strong><br>";
foreach ($data['notes'] as $z){
	if ($z['language'] == "English") { print $z['noteText'] . "<br><br>"; } 
}

// hierarchy

echo "<strong>Hierarchy: </strong><br>";
print $data['hierarchy']['parent']['conceptString'];
foreach ($data['hierarchy']['parent']['siblings'] as $z){ 

	//print $data['hierarchy']['parent']['siblings'][0]['conceptString'];
	if ($z['conceptString'] == $data['preferredstring']['string']){
		echo "<br>. <strong>" . $z['conceptString'] . "</strong>";
	} 
	else {
		echo "<br> . " . $z['conceptString'];
	}
	// all children
	foreach ($z['children'] as $y){ 
		echo "<br> ... " . $y['conceptString'];
	}
		
}



/* echo "<br><br>Associated Concepts:<br>";
foreach ($data['associations'] as $z){
	echo $z['associationString'] . "<br>";
}
foreach ($data['terms'] as $z){
	echo $z['strings'][0][0] . "<br>";
}


echo "</div></div>"; // end bootstrap panel	
*/




?>