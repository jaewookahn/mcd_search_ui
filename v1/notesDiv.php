<div> <!-- This is empty div and please don't remove this -->
<?php 
//include "assets/php/conn.php";
$getID=$_GET['q'];


///////////////////////////////////////////////
//
// THIS FILE RETRIEVES: 
// - Concept details 
// - Hierarchy elements 
// -- and is used called by the page "results.php"
//
///////////////////////////////////////////////


// hit the API

	
	
	function get_string_between($string, $start, $end){
	$string = " ".$string;
	$ini = strpos($string,$start);
	if ($ini == 0) return "";
	$ini += strlen($start);
	$len = strpos($string,$end,$ini) - $ini;
	return substr($string,$ini,$len);
}
		$lines = file('http://rack90.cs.drexel.edu:8080/AAT1/GetConceptDetail?q=' . $getID);
		//$lines = file('http://rack90.cs.drexel.edu:8080/AAT1/api/ConceptDetail.jsp?q=' . $getID);
		$raw='';
		foreach ($lines as $line_num => $line) {
    		//echo htmlspecialchars($line) . "<br />\n";
			$raw.= htmlspecialchars($line); // load up $raw variable for processing
		}
		
		//print $raw;
		
		
		$notes = get_string_between($raw,"&lt;notes&gt;","&lt;/notes&gt;");
		$notes = str_replace("&lt;","<",$notes);
		$notes = str_replace("&gt;",">",$notes);
		print '<div id="inner_1"><p><b>Note(s):</b> <br /> ' . $notes . '</p>';
		
		$assoc = get_string_between($raw,"&lt;association&gt;","&lt;/association&gt;");
		$assoc = str_replace("&lt;","<",$assoc);
		$assoc = str_replace("&gt;",">",$assoc);
		$assoc = str_replace("<relationType>"," (",$assoc);
		$assoc = str_replace("</relationType> ","),  ",$assoc);
		print '<P><b>Relationship(s):</b> <br /> ' . $assoc . '</P>';
		
		$variant = get_string_between($raw,"&lt;variant&gt;","&lt;/variant&gt;");
		$variant = str_replace("&lt;","<",$variant);
		$variant = str_replace("&gt;",">",$variant);
		$variant = str_replace("</variantTerm>","; ",$variant);
		print '<P><b>Variant term(s):</b> <br /> ' . $variant. '</P>';
		echo "</div>"; // end the notes div,  inner_1
	
	
		// hierarchy DIV, inner_2
		// Parents, Preferred Term, Children
		
		$preferred = get_string_between($raw,"&lt;preferredTerm termID=","&lt;/preferredTerm&gt;");
		$first=strpos($preferred, "&quot;&gt;"); 
		$first = $first+10;
		$preferred = substr($preferred, $first);
		
		$parents = get_string_between($raw,"&lt;parent&gt;","&lt;/parent&gt;");
		$parents = str_replace("&lt;","<",$parents);
		$parents = str_replace("&gt;","> ",$parents);

		$children = get_string_between($raw,"&lt;child&gt;","&lt;/child&gt;");
		$children = str_replace("&lt;","<",$children);
		$children = str_replace("&gt;",">",$children);
		$children = str_replace("<childTerm","<br /><childTerm",$children);
		
		
		// JSON decode
		$jj= file('http://rack90.cs.drexel.edu:8080/MCD3/SearchConcept?q=paint&limit=10');
		//$jj= file('http://rack90.cs.drexel.edu:8080/AAT1/GetConceptDetail?q=' . $getID);
		$j2=var_dump(json_decode($jj, true));
		
		
		print '<div id="inner_2"><h4>&nbsp;MCD Hierarchy</h4><hr><ul class="nav nav-list">';
		print_r ("$j2");
		if (!empty($parents)){ print '<li>' . $parents. '</li>'; }
	
		print '<li class="nav-header">&nbsp;&nbsp;' . $preferred . '</li>';
		
		if (!empty($children)){ print '<li>' . $children . '<li>'; }
		
		print "</ul></div>";
	
	//$xml=simplexml_load_file('http://rack90.cs.drexel.edu:8080/AAT1/api/GetConceptDetail.jsp?q=' . $getID) or print ("<p><i>simplexml_load_file: data not available</i></p>");
	//print_r($xml);
	/*echo '<table class="table table-bordered">
    
    <thead>
    <tr>
    <th>Parent(s)</th>
    <th>Children</th>
	<th>Associative</th>
    </tr>
    </thead>
    <tbody>
    <tr>
    <td><ul>';
	foreach($xml->parent->parentTerm as $parent){ 
			echo '<li><a href="results.php?id=' . $parent->attributes()->conceptID . '">' . $parent . '</a></li>';	
		}	
	
	echo '</ul></td>
    <td><ul>';
	
		
	foreach($xml->children->childTerm as $child){ 
			echo '<li><a href="results.php?id=' . $child->attributes()->conceptID . '">' . $child . '</a></li>';
		}
	echo '</ul></td><td><ul>';
	foreach($xml->association->relation as $rel){ 
			echo '<li><a href="results.php?id=' . $rel->relTerm->attributes()->conceptID . '">' . $rel->relTerm . '</a> (' . $rel->relType . ')</li>';
		}	
	
	echo '</ul></td></tr>
    </tbody>
    </table>';
		
		echo "<b>Variant terms</b> "; 
		foreach($xml->variants->variantTerm as $var){ 
			echo "; $var";
		}
		
		echo '<br />Term ID: ' . $v['identifier'] . '; Concept ID: ' .  $v['conceptID']; 
		echo '; Source vocabulary: ' . $v['sourceVocab'];
		echo '<br />MySQL fulltext indexing relevancy score: ' . $v['score'];
*/			
?>