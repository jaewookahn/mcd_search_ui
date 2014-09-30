<?php 
include "assets/php/conn.php";
$query=$_GET['q'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Meaningful Concept Displays - Search for<?php echo "$query" ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/vce.css" rel="stylesheet">
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>

<!-- jQuery -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

<!-- Visual Concept Explorer javascript -->
<script src="d3/d3.js"></script><!-- main d3.js file -->
<script src="d3/d3.geom.js"></script><!-- geometry libraries -->
<script src="d3/d3.layout.js"></script><!-- layouts like force layout (physics) -->
<script src="d3nl/d3nl.js"></script><!-- Serge wrote for us -->
<script> 

    $('#myTab a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
    })

/* 	
function showNotes(request,url,panel) { 	// the function that makes requests
	$.ajax({	
		type: "GET",
		// url: url+"?q="+request,
		 url:"api/GetHier.php?q=ga_300038217",
		//url: "getNotes.php?q="+request,			
		beforeSend:function(){
			// this is where we append a loading image
			$(panel).html('<div class="loading"><strong>Loading term info</strong> <img src="assets/img/spinner.gif" alt="Loading notes..." /></div>');
		},
		success: function(msg) {
			// add content to the DIV 
			$(panel).empty();
			$(panel).append(msg).fadeIn("slow");
	
		}, 
		error:function(){
			// failed request; give feedback to user
			$(panel).html('<p class="error">Error: No notes could be found for this term!</p>');
		}
		
	}); // end ajax
} //end function showNotes  */
	
$(document).ready(function(){	
	// this will show the notes dive for that result
	// and load the external data for that conceptID via ajax	
	$('.collapse').on('show', function () { 
    	//$(this).text('mike');
		var a = $(this); //the current div being shown
		var b = $(this).attr('title');	// the conceptID we will fetch 
		$.ajax({	
			type: "GET",
			url: "http://rack90.cs.drexel.edu/search/v1/notesDiv.php?q="+b,			
			beforeSend:function(){
				// this is where we append a loading image
				$(a).html('<div class="loading"><strong>Loading term info</strong> <img src="assets/img/spinner.gif" alt="Loading notes..." /></div>');
				$('#hier').html('<div class="loading"><strong>Loading term info</strong> <img src="assets/img/spinner.gif" alt="Loading notes..." /></div>');
			},
			success : function( resp ) {
				// add content to the DIV 
				$(a).empty();
					$(a).html($('#inner_1' , resp).html());
					$('#hier').html($('#inner_2' , resp).html());
			}, 
			error:function(){
				// failed request; give feedback to user
				$(a).html('<p class="error">Error: No notes could be found for this term!</p>');
				$('#hier').html('<p class="error">Error retrieving hierarchy</p>');
			}
		}); // end ajax		
		
		
    }) // on collapse show
		
	
}); // end document ready
</script>
<style type="text/css">
body {
	padding-top: 60px;
	padding-bottom: 40px;
}
.sidebar-nav {
	padding: 9px 0;
	position:fixed; 
	top:60px;
     width:21.97%;
}
.breadcrumb {
	margin-top: -25px;
	margin-bottom: -25px;
}
#listView #gridView {
	min-height: 90%;
}
.ui-autocomplete-loading {
	background: white url('assets/img/spinner.gif') right center no-repeat;
} /*for autocomplete */

	
</style>

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

<!-- Le fav and touch icons
    <link rel="shortcut icon" href="assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
     -->
</head>

<body>

<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container-fluid"> <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a> <a class="brand" href="#">MCD</a>
      <div class="btn-group pull-right"> <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> <i class="icon-user"></i> Username <span class="caret"></span> </a>
        <ul class="dropdown-menu">
          <li><a href="#">Profile</a></li>
          <li class="divider"></li>
          <li><a href="#">Sign Out</a></li>
        </ul>
      </div>
      <div class="nav-collapse">
        <ul class="nav">
          <li class="active"><a href="#">Home</a></li>
          <li><a data-toggle="modal" href="#aboutModal" >About</a></li>
          <li><a data-toggle="modal" href="#contactModal">Contact</a></li>
        </ul>
      </div>
      <!--/.nav-collapse --> 
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span3">
      <div class="well sidebar-nav navbar-fixed" id="hier">
	  <h4>&nbsp;MCD Hierarchy</h4><hr>
        <ul class="nav nav-list">

         
          <li>Parent</li>
          <li class="nav-header">Selected term</li>
          <li>Children</li>

        </ul>
      </div>
      <!--/.well --> 
    </div>
    <!--/span-->
    <div class="span9">
 	
	<div class="hero-unit">
	<form name="searchForm"id="searchForm" method="get" action="results.php" class="form-search">

<table>

    <tbody>
    <tr>
    <td width="348" align="left" valign="top">
	
		<input name="q" type="search" id="q" tabindex="1" placeholder="Enter Search" class="search-query">
          <button type="submit" class="btn">Search</button>
	</td>	
	<td width="216" align="left" valign="top">
			
			<input name="src_vocab" type="radio" value="all" checked>
			Search all terms<br />
			<input name="src_vocab" type="radio" value="a">
			only AAT terms<br />
			<input name="src_vocab" type="radio" value="t">
			only TGN terms<br />			
			<input name="src_vocab" type="radio" value="u" >
			only ULAN terms
			
	</td>
	<td width="270" align="left" valign="top"><br />
			<input name="limit_res" type="checkbox" value="y">
			Limit results</td>
	</tr>
	</tbody>
	</table>	
	 </form>
	 </div>
       <ul class="breadcrumb">
        <li> Search Results: <a href="#"><?php echo "$query"; ?></a> <span class="divider">></span> </li>
      <!--<li> <a href="#">Level 2</a> <span class="divider">></span> </li>
        <li class="active">Level 3</li>--> 
      </ul>  
	    <p>&nbsp;</p>
      <ul class="nav nav-tabs" id="navTabs">
      	<li><a href="#listView" data-toggle="tab" >List View</a></li>      
        <li><a href="#chart" data-toggle="tab">Graph View</a></li>
      </ul>
   
	  <div class="row-fluid">
        <div id="my-tab-content" class="tab-content">
        <div id="gridView" class="tab-pane fade">
      
        </div>
          <div id="listView" class="tab-pane fade">
		  	        
<?php
	
/****************
Execute initial query: 
- fulltext match of user-entered term on ISO.ThesaurusTerm
- Get concept ID's & add to array, do not add duplicates (use in_array to capture the first, highest scoring value.) 
- Query the db again
-- Get full row for each conceptID where preferred term is true 
- Hidden notes DIV 
-- Send conceptID to Java API when the DIV is shown using AJAX
-- Process XML return and display returned data
****************/

if ($_GET['limit_res'] == "y") { $l=" limit 12"; }
 
$sql ="SELECT conceptID, MATCH (term) AGAINST ('$query') AS score
FROM ISO.ThesaurusTerm WHERE MATCH (term) AGAINST ('$query') ";
	if ($_GET['src_vocab'] == "a") { $sql .= "AND sourceVocab='GettyAAT' "; }
	if ($_GET['src_vocab'] == "t") { $sql .= "AND sourceVocab='GettyTGN' "; }
	if ($_GET['src_vocab'] == "u") { $sql .= "AND sourceVocab='GettyULAN' "; }
$sql .= "order by score DESC" . $l;
$res = MySQL_query($sql) or DIE('There was an error with the query');       
while ($v = MySQL_fetch_assoc($res)) { 
	if (!array_key_exists($v['conceptID'], $pref)) {
		$pref[$v['conceptID']]=$v['score']; // add to array, in order of score
		$fq .= "(conceptID='" . $v['conceptID'] . "' AND PREFERRED='P') OR "; 	 // build second sql query to get terms
	} // end if  
} // end while

// we now have the $pref array with all conceptIDs and their highest score
// now get the rows that are the preferred term for that conceptID

$fq= substr($fq, 0, -4); // trim that last OR
$sql ="SELECT * from ISO.ThesaurusTerm WHERE " . $fq;
$res = MySQL_query($sql) or DIE('There was an error with the query');     
while ($v = MySQL_fetch_assoc($res)) { 
	$r[$v['conceptID']] = $v['term'];
//echo "<p>" . $v['term'] . " " . $v['conceptID'] . " " . $v['sourceVocab'] . "</p>";
}

foreach ($pref as $k => $v){  // for each value in the $pref array, get the corresponding term from $r	
	$pos=(strpos($k,"ga_"));
	if ($pos !== false) { //this is a ga term
		$ga[$k]=$r[$k];
	}
	$pos=(strpos($k,"gu_"));
	if ($pos !== false) { //this is a gu term
		$gu[$k]=$r[$k];
	}
	$pos=(strpos($k,"gt_"));
	if ($pos !== false) { //this is a gu term
		$gt[$k]=$r[$k];;
	}
} // end foreach

		if (!empty($ga)){ 
		echo '<div class="span4"><h4>Getty AAT</h4>';
			foreach ($ga as $k=>$u){
				echo '<a class="notes" data-toggle="collapse" data-target="#notes-' . $k. '" title="' . $k . '"><i class="icon-list-alt"></i> ';
				echo $u . "</a> ";
				echo '<div id="notes-' . $k . '" class="collapse in" title="' . $k . '">'; // this is the hidden div with additional information for the item
				echo '</div>'; // end hidden div
				echo ' <hr noshade size="1" width="100px">';
			
			}

		} // end if !empty
?>
			</div><!-- end span 4.1 -->
			
			<?php 
			if (empty($ga)){ 
				echo  '<div class="span6">'; 
			} else { 
				echo '<div class="span4">';
			}
			?>
			<h4>Getty ULAN</h4>
			<?php 
			foreach ($gu as $k=>$u){
				echo '<a class="notes" data-toggle="collapse" data-target="#notes-' . $k. '" title="' . $k . '"><i class="icon-list-alt"></i> ';
				echo $u . "</a>";
				echo '<div id="notes-' . $k . '" class="collapse in" title="' . $k . '">'; // this is the hidden div with additional information for the item
				echo '</div>'; // end hidden div
				echo ' <hr noshade size="1" width="100px">';
			
			}
			?>
			</div><!-- end span 4.2 -->
			<div class="span4">
			<h4>Getty TGN</h4>
			<?php 
			foreach ($gt as $k=>$u){
				echo '<a class="notes" data-toggle="collapse" data-target="#notes-' . $k. '" title="' . $k . '"><i class="icon-list-alt"></i> ';
				echo $u . "</a>";
				echo '<div id="notes-' . $k . '" class="collapse in" title="' . $k . '">'; // this is the hidden div with additional information for the item
				echo '</div>'; // end hidden div
				echo ' <hr noshade size="1" width="100px">';
			
			}
			
			?>
			</div><!-- end span 4.3 -->
			
			
          </div> <!-- end list view --> 
          
         
         
        </div>
      </div>
	  
	  

  
  <hr>
  <footer>
    <p><a href="http://rack90.cs.drexel.edu/" title="MCD" target="_blank">Meaningful Concept Displays</a> 2012 | Made with <a href="http://twitter.github.com/bootstrap/" title="Bootstrap" target="_blank">Bootstrap</a> | <a href="http://cluster.cis.drexel.edu/vce/" title="VCE" target="_blank">Visual Concept Explorer</a> | <a href="http://d3js.org/" title="D3 Javascript Library" target="_blank">D3</a> | <a href="http://jquery.com/" title="jQuery" target="_blank">jQuery</a></p>
  </footer>
</div>

<!--/.fluid-container--> 

<!-- Modal windows --> 

<!-- About Modal -->

<div class="modal hide" id="aboutModal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3>About MCD</h3>
  </div>
  <div class="modal-body">
    <p>The Meaningful Concept Displays project is focused on investigating and evaluating ways to improve users' experience with libraries and museums as searchers (search, both query formulation and result exploration; sensemaking; and learning) and as contributors (social tagging) through exploiting the intellectual capital present in many Knowledge Organization Systems (KOS) (controlled lists, thesauri, classifications, taxonomies, ontologies, …) and Linked Open Data.</p>
    <p> This project aims to have a significant impact on users' learning and mastering "21 century skills" through the following:
    <ul>
      <li>transforming the search experience and success of a wide range of users;</li>
      <li>transforming the ease and quality of social tagging, increasing the benefits from the vast volunteer labor invested and possibly engaging even more people;</li>
      <li> transforming learning: students' individual or collaborative exploration, sensemaking, making connections, and active learning.</li>
    </ul>
    </p>
  </div>
  <div class="modal-footer"> <a href="#" class="btn" data-dismiss="modal">Close</a> </div>
</div>

<!-- End About Modal --> 

<!-- About Modal -->

<div class="modal hide" id="contactModal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3>Contact MCD</h3>
  </div>
  <div class="modal-body">
    <p>Contact Form Here </p>
  </div>
  <div class="modal-footer"> <a href="#" class="btn" data-dismiss="modal">Close</a> </div>
</div>

<!-- End Contact Modal --> 

<!-- End Modals --> 

<!-- Le javascript
    ================================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script> 
<script src="assets/js/bootstrap.js"></script> 
<script src="assets/js/jquery.ui.core.js"></script> 
<script src="assets/js/jquery.ui.widget.js"></script> 
<script src="assets/js/jquery.ui.position.js"></script> 
<script src="assets/js/jquery.ui.autocomplete.js"></script> 
<script>
// open first results tab
 $(function () {
$('#navTabs a:first').tab('show');
})

$(".collapse").collapse();

// autocomplete function	
$(function() { 
		$( "#q" ).autocomplete({
			source: "assets/php/autocomplete.php",
			minLength: 3,
		});
	}); 
	
	

// end autocomplete


// AJAX the typeahead -- but for now we're using autocomplete above
//var autosearches = ['Baltimore', 'Boston', 'New York', 'Tampa Bay', 'Toronto', 'Chicago', 'Cleveland', 'Detroit', 'Kansas City', 'Minnesota', 'Los Angeles', 'Oakland', 'Seattle', 'Texas'].sort();
//	$('#city').typeahead({source: autosearches, items:5});
//});
</script>
</body>
</html>
