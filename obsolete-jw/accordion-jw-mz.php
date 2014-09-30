<?php 
include "assets/php/conn.php"; //sql connection string
$query=$_GET['q']; // the querystring
$data = json_decode(file_get_contents('http://rack90.cs.drexel.edu:8080/AAT1/Search?q=' . $query)); 
//print_r($data);


foreach ($data as $name => $value) {
     //    echo '<li>' . $value->conceptID . ' ' . $value->preferredTerm . ' ' . $value->sourceVocab . '</li>';
 
	if ($value->sourceVocab == "GettyAAT"){ $ga[$value->conceptID] = $value->preferredTerm; }
	if ($value->sourceVocab == "GettyULAN"){ $gu[$value->conceptID] = $value->preferredTerm; }
	if ($value->sourceVocab == "GettyAAT"){ $gu[$value->conceptID] = $value->preferredTerm; }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Meaningful Concept Displays (MCD)</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Meaningful Concept Displays">
<meta name="author" content="Drexel MCD Team">

<script type="text/javascript" src="d3.v2.min.js"></script>
<script src="http://code.jquery.com/jquery-latest.js"></script>

<script src="js-jw/PorterStemmer1980.js"></script>
<script src="js-jw/levenshtein.js"></script>
<script type="text/javascript" src="cvdi.js"></script>

<link rel="stylesheet" type="text/css" href="cvdi.css"/>

<!-- Le styles -->
<link href="assets/css/bootstrap.css" rel="stylesheet">
<style type="text/css">
body {
	padding-top: 60px;
	padding-bottom: 40px;
}
.collapse {
	font-size:10px;
}
.breadcrumb {
	font-size:10px;
}
.accordion-inner {
	max-height:300px;
	overflow-y: auto;
	-ms-overflow-y: auto;
}
.popover {
	font-size:10px;
}
</style>

<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

<!-- Fav and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="assets/ico/favicon.png">
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>
$(document).ready(function(){	
	/*$('.collapse .showem').on('show', function () { 
		var a = $(this); //the current div being shown
		var b = $(this).attr('title');	// the conceptID we will fetch 
		var c = $(this).attr('data-term') // other data attributes we may want ? 
		$.ajax({	
			type: "GET",
			url: "http://rack90.cs.drexel.edu/search/accordion-notes.php?q="+b,			
			beforeSend:function(){
				 // this is where we append a loading image
				$(a).html('<div class="loading"><strong>Loading term info</strong> <img src="assets/img/spinner.gif" alt="Loading notes..." /></div>');
			},
			success : function( resp ) {
				// add content to the DIV 
				$('#hier').empty();
					//$(a).html($('#inner_1' , resp).html()); 
					// $(someDiv).html($('#inner_2' , resp).html()); // if we want to update more than one div on this page
						// wrap data in corresponding <div id=inner_2></div> on the php file we are hitting.
					
					$('#hier').append('<span id="' + c + '" class="hier-column"> [X]' + $('#inner_2' , resp).html() + '</span>');
			}, 
			error:function(){
				// failed request; give feedback to user
				$(a).html('<p class="error">Error: No notes could be found for this term!</p>');
				
			}
		}); // end ajax	
		
		
		// append term to the breadcrumb
		$('.breadcrumb').append('<li id="' + $(this).attr('data-term') + '"> <span class="divider">/</span> ' + $(this).attr('data-term') + '</li>');
    }) // end on collapse show
	
		$('.collapse .showem').on('hidden', function () { 
		// when the div is hidden, remove from the breadcrumb
			p= '#'+$(this).attr('data-term');
			$(p).remove() ;
		}) // end hidden collapse delete the entry in the breadcrumb
*/
// on hover in accordion, run function to get data and create bootstrap popover


	
 $(".notes").click(function() {
    el = $(this);
	var b = $(this).attr('title');	// the conceptID we will fetch 
	var c = $(this).attr('data-term');
		$.get("http://rack90.cs.drexel.edu/search/accordion-notes.php?q="+b, function(response) {
			el.unbind('hover').popover({content: response, html: true, delay: {show: 300, hide: 100} }).popover('show');
    	}); //end get
	$('.breadcrumb').html('<span id="' + $(this).attr('data-term') + '">Selected: ' + $(this).attr('data-term') + '</span>');
  }); //end notes hover function
	
}); // end document ready
</script>
</head>

<body>
	
		<ul class="contextMenu node-contextmenu" style="background: #e0e0e0; list-style: none; margin: 0; padding: 4; display: none;">
		<li><a class="contextmenu-item" data-box="red">Add To Red Box</a></li>
		<li><a class="contextmenu-item" data-box="green">Add To Green Box</a></li>
		<li><a class="contextmenu-item" data-box="blue">Add To Blue Box</a></li>
	</ul>
	
	<span class="node-relation"
	style="background: #EEF66C; opacity: 0.8; padding: 4; display: none;"
	>
		aaa
	</span>
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container"> <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a> <a class="brand" href="#">Meaningful Concept Displays (MCD)</a>
			<div class="nav-collapse collapse">
				<ul class="nav">
					<li class="active"><a href="#">Home</a></li>
					<li><a data-toggle="modal" href="#aboutModal" >About</a></li>
					<li><a data-toggle="modal" href="#contactModal">Contact</a></li>
					<li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="#">Action</a></li>
							<li><a href="#">Another action</a></li>
							<li><a href="#">Something else here</a></li>
							<li class="divider"></li>
							<li class="nav-header">Nav header</li>
							<li><a href="#">Separated link</a></li>
							<li><a href="#">One more separated link</a></li>
						</ul>
					</li>
				</ul>
				<form class="navbar-form pull-right">
					<input name="q" type="search" id="q" tabindex="1" placeholder="Enter Search" value="<?php print $query ?>" class="span3">
					<button type="submit" class="btn">Search</button>
				</form>
			</div>
			<!--/.nav-collapse --> 
		</div>
	</div>
</div>
<div class="container">
	<!-- Main hero unit for a primary marketing message or call to action
	<div class="hero-unit">
		<h1>Hello, world!</h1>
		<p>This is a template for a simple marketing or informational website. It includes a large callout called the hero unit and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
		<p><a class="btn btn-primary btn-large">Learn more &raquo;</a></p>
	</div>
	 --> 
	<!-- Main column -->
	<div class="row">
		<div class="span8" >
			<div id="status"></div>
			<div id="viscanvas">
			</div>
			<!-- var nodeColors = ["#c09853", "#b94a48", "#3a87ad", "#468847", "#999999", "#444444"]; -->
			<div>
				<span style="background:#c09853; color:white; padding:2px; margin:2px"> Associations </span>
				<span style="background:#b94a48; color:white; padding:2px; margin:2px"> Siblings </span>
				<span style="background:#3a87ad; color:white; padding:2px; margin:2px"> Children </span>
				<span style="background:#468847; color:white; padding:2px; margin:2px"> Parent </span>
				
			</div>
		</div>
		
	<!-- the accordion menus & breadcrumb if desired --> 
		<div class="span4">
		<div class="breadcrumb">
		<span>Search:<a href="<?php print $_SERVER['PHP_SELF'] . '?q=' . $query; ?>"> <?php print $query ?></a></span>
		</div>
			<p><a class="btn btn-mini" data-toggle="collapse" data-target="#collapseOne">AAT</a> <a class="btn btn-mini" data-toggle="collapse" data-target="#collapseTwo">ULAN</a> <a class="btn btn-mini" data-toggle="collapse" data-target="#collapseThree">TGN</a></p>
			<div class="accordion" id="accordion2">
				<div class="accordion-group">
					<div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne"> Getty AAT <span class="badge"><?php print count($ga); ?></span> </a> </div>
					<div id="collapseOne" class="accordion-body collapse">
						<div class="accordion-inner">
							<?php 
if (!empty($ga)){ 
		echo '<ul class="unstyled">';
			foreach ($ga as $k=>$u){
				$p=str_replace(" ","_",$u);
				echo '<li>';
				echo " <span onClick='search(\"$k\", \"$u\")'<i class=\"icon-repeat\"></i> </span> ";
				
				echo '<a href="javascript:void(null);" class="notes" title="' . $k . '">';
				echo $u . "</a> ";
				//echo '<div id="notes-' . $k . '" class="collapse showem" title="' . $k . '" data-term="' . $p . '">'; // this is the hidden div with additional information for the item
				//echo '</div>'; // end hidden div
				echo '</li>';
				///echo '<hr>';
			} // end foreach
		echo '</ul>';
} // end if !empty
?>
						</div>
					</div>
				</div>
				<div class="accordion-group">
					<div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"> Getty ULAN <span class="badge"><?php print count($gu); ?></span> </a> </div>
					<div id="collapseTwo" class="accordion-body collapse">
						<div class="accordion-inner">
							<?php 
							echo '<ul class="unstyled">';
			foreach ($gu as $k=>$u){
				// special characters replaced for use in javascript 
				$p=str_replace(",","_",$u);
				$p=str_replace(".","",$p);
				$p=str_replace(" ","_",$p);
				$p=htmlentities($p);
				echo '<li>';

				echo " <span onClick='search(\"$k\", \"$u\")'<i class=\"icon-repeat\"></i> </span> ";
				

				echo '<a href="javascript:void(null);" class="notes" data-toggle="collapse" data-target="#notes-' . $k. '" title="' . $k . '" data-term="' . $p . '">';
				echo $u . "</a>";
				
				echo "</li>";
			
			} // end forach
			echo '</li>';
?>
						</div>
					</div>
				</div>
				<div class="accordion-group">
					<div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree"> Getty TGN <span class="badge"><?php print count($gt); ?></span> </a> </div>
					<div id="collapseThree" class="accordion-body collapse">
						<div class="accordion-inner">
							<?php 
foreach ($gt as $k=>$u){
	echo " <span onClick='search(\"$k\", \"$u\")'<i class=\"icon-repeat\"></i> </span> ";
	$p=str_replace(" ","_",$u);
	echo '<a href="javascript:void(null);" class="notes" data-toggle="collapse" data-target="#notes-' . $k. '" title="' . $k . '">';
	echo $u . "</a>";
	
	echo '<div id="notes-' . $k . '" class="collapse showem" title="' . $k . '" data-term="' . $p . '">'; // this is the hidden div with additional information for the item
	echo '</div>'; // end hidden div
	//echo ' <hr noshade size="1" width="100px">';

}

?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- end side div --> 
		
	</div>
	<!-- end rows/columns -->
	<hr>
	<footer>
		<p>&copy; Meaningful Concept Displays (MCD)</p>
	</footer>
</div>
<!-- /container --> 

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
<script src="assets/js/bootstrap.js"></script> 
<script src="assets/js/jquery.ui.core.js"></script> 
<script src="assets/js/jquery.ui.widget.js"></script> 
<script src="assets/js/jquery.ui.position.js"></script> 
<script src="assets/js/jquery.ui.autocomplete.js"></script> 
<script>


// autocomplete function	
$(function() { 
		$( "#q" ).autocomplete({
			source: "assets/php/autocomplete.php",
			minLength: 3,
		});
}); 
</script>
</body>
</html>
