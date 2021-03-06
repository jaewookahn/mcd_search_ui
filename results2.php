<?php
$query=htmlspecialchars($_GET["q"]);
$facets = $_GET["facets"];

if (empty($facets)){
	$facets[]="Materials Facet"; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="MCD research team">
<link rel="shortcut icon" href="http://getbootstrap.com/assets/ico/favicon.ico">
<title>Meaningful Concept Displays</title>

<!-- Bootstrap core CSS -->
<link href="http://getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="http://getbootstrap.com/examples/starter-template/starter-template.css" rel="stylesheet">
<style type="text/css">
#notes {
	right: 0;
}
.mygrid-wrapper-div {
	overflow: auto;
	height: 300px;
}
</style>

<!-- Just for debugging purposes. Don't actually copy this line! -->
<!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="./assets/js/bootstrap.js"></script>
<script type="text/javascript">
$(document).ready(function() {

	// add term to expanded query box  
  	$(".insert").click(function (e) {
		e.preventDefault();
    	var insertText = '<div class="checkbox"><label><input type="checkbox" checked name="getty_terms[]" value="' + $(this).attr('title') + '">' + $(this).attr('data-term') + '</label></div>';
       	$('#xquery').append(" "+insertText);
	});
	
	<!-- open modal and load hierarchy -->
	$('.openBtn').click(function(e){
		e.preventDefault();
		var goGet = 'getDetails.php?id=' + $(this).attr('title');
		
		$('.modal-body').load(goGet,function(result){
			$('#myModal').modal({show:true});
		});
	});
	
	
	
	$('[data-toggle="tabajax"]').click(function(e) { // tab load URL
	e.preventDefault();
		var $this = $(this),
			//loadurl = $this.attr('href'),
			loadurl='getDetails.php?id=2658',
			targ = $this.attr('data-target');
			$.get(loadurl, function(data) {
				$(targ).html(data);
			});
		$this.tab('show');
		return false;
	}); // end tab load URL


	$('#mapit').click(function(e) { 
	e.preventDefault();
			var action = $("#artstor_terms").attr('action');
			var form_data = $('#artstor_terms').serialize(); 
				$.ajax({	
					type: "GET",
					url: action, //"getArtstorTerms.php",
					data: form_data,	
					beforeSend:function(){
						// this is where we append a loading image
					$('textarea#artstor-query').html('loading...');
					//$('#artstor-query2').html('loading...');
					},
					success : function(result) {
						// add content to the DIV 
						$('textarea#artstor-query').html(result);
						//$('#artstor-query2').html(result);
						
					}, 
					error:function(){
						// failed request; give feedback to user
						$('#artstor-query2').append('Error!');
					}
				}); // end ajax		
		return false;
		
		}) // end click function
		
		// this opens the Artstor tab when you add the term. 
	  $("button[data-tab-destination]").on('click', function() {
        var tab = $(this).attr('data-tab-destination');
        $("#"+tab).click();
    });
	// end open artstor tab


	$('#get-images').click(function (e) { 
	//e.preventDefault();
		var b = $('textarea#artstor-query').val();
			$.ajax({	
				type: "GET",
				//url: "getImages.php?query="+b,	
				//url: "http://rack90.cs.drexel.edu/ahn/mcd_qe/search_artstor.cgi?query=crayon%20on%20paper,oil%20on%20canvas",	
				url: "getImages.php?query=crayon%20on%20paper,oil%20on%20canvas",	
				
				beforeSend:function(){
					// this is where we append a loading image
					$('#image-results').html('<div class="loading"><strong>Retrieving Results</strong> <img src="assets/img/spinner.gif" alt="Loading notes results" /></div>');
				},
				success : function(result) {
					// add content to the DIV 
					$("#image-results").html(result);
				}, 
				error:function(){
					// failed request; give feedback to user
					$('#image-results').html('<p class="error">Could not search.</p>');
				}
			}); // end ajax				
		}) // end click function


/* NEW GET IMAGES
$('#get-images').click(function (e) { 
		e.preventDefault();
		var action = $("#artstor_get_images").attr('action');
		var form_data = $('#artstor_get_images').serialize(); 
		var b = $('textarea#artstor-query').val();
			$.ajax({	
				type: "GET",
				url: action, //"getArtstorTerms.php",
				data: form_data,	
				//url: "http://rack90.cs.drexel.edu/ahn/mcd_qe/search_artstor.cgi?query=crayon%20on%20paper,oil%20on%20canvas",	
				//url: "getImages.php",
				beforeSend:function(){
					// this is where we append a loading image
					$('#image-results').html('<div class="loading"><strong>Retrieving Results</strong> <img src="assets/img/spinner.gif" alt="Loading notes results" /></div>');
				},
				success : function(result) {
					// add content to the DIV 
					$("#image-results").html(result);
				}, 
				error:function(){
					// failed request; give feedback to user
					$('#image-results').html('<p class="error">Could not search.</p>');
				}
			}); // end ajax				
		}) // end click function
*/

}); // end document ready

</script>
</head>

<body>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
			<a class="navbar-brand" href="index.html">Meaningful Concept Displays</a> </div>
		<div class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<li class="active"><a href="index.html">Home</a></li>
				<li><a href="#">About</a></li>
				<li><a href="#">Contact</a></li>
			</ul>
			<form class="form-inline" method="get" action="" role="form">
				<div class="form-group">
					<input type="search" class="form-control" name="q" tabindex="1" id="q" value="<?php echo "$query"; ?>">
				</div>
				<button type="submit" class="btn btn-default">Search</button>
			</form>
		</div>
		<!--/.nav-collapse --> 
	</div>
</div>
<div class="container">
	<p>&nbsp;</p>
	<!-- tab navigation -->
	<ul class="nav nav-tabs">
		<li class="active"><a href="#vocab" data-toggle="tab">Getty Results</a></li>
		<li><a href="#artstor-images" id="tab-2" data-toggle="tab"><span class="glyphicon glyphicon glyphicon-search pull-left"></span>&nbsp;ArtStor</a></li>
		<!-- <li><a href="getDetails.php?id=2658" data-toggle="tabajax" data-target="#hier"><span class="glyphicon glyphicon glyphicon-th-list"></span>&nbsp;Getty Hierarchy</a></li> -->
	</ul>
	
	<!-- Tab panes -->
	<div class="tab-content">
		<div class="tab-pane active" id="vocab">
			<h3>Getty Results: <?php echo "$query"; ?> <button type="button" class="btn btn-primary pull-right" data-tab-destination="tab-2" id="mapit">Map Terms!</button></h3>
			
			<p><span class="glyphicon glyphicon-th-list"></span> displays hierarchy (please wait for window to load)
											
</p>
			<div class ="row">
			<form name="artstor_terms" id="artstor_terms" method="get" action="getArtstorTerms.php" >
								
								<!-- <input type="radio" name="andor" value="and">
								And
								<input type="radio" name="andor" value="or">
								Or --> 
								
				<?php 
					
					if (empty($query)){ 
						echo "error, you did not specify a querystring! (?q=xyz)"; 
					}
					
					$url = "http://rack90.cs.drexel.edu:8080/MCD3/SearchGroupedConcepts?q=$query&limit=50";
					$json = file_get_contents($url);
					$data = json_decode($json, TRUE);
					
					
					for ($i = 0, $size = count($data); $i < $size; ++$i) { // entire json array
						//print_r($data[$i]);
						if (in_array($data[$i]['facetString'], $facets)){
						echo '<div class="panel panel-default col-md-4 mygrid-wrapper-div">'; // start panel for this facet
						$c= count($data[$i]['concepts']);
						
							print '<h5>' .$data[$i]['facetString'] . '</a> <span class="badge pull-right">'. $c . '</span></h5>'; // facet name
							print '<table class="table table-condensed">';
							$c= count($data[$i]['concepts']);
							foreach ($data[$i]['concepts'] as $z){ //concepts array for each facet
								echo '<tr>';
								echo '<td><div class="checkbox"><label><a href="#" class="openBtn" title ="' . $z['id'] . '"><span class="glyphicon glyphicon-th-list pull-right"></span></a> ';
								echo $z['preferredstring']; 
								echo ' ( '. $z['id'] . ' )';
								echo '<input type="checkbox" name="getty_terms[]" value="' . $z['id'] . '">';
								echo '</label></div>';
								
							}
							
							if ($c == "49"){ echo '<li class="list-group-item">View All in Facet</li>'; } // if max 50 items id reached, provide link to facet page 
							
							print '</table>';
					
							
							print '</div>'; // end panel and panel body for this facet
							
						} // end if in facet
					}
			
				?>
				</form>
			</div>
			<!-- end row  --> 
			
		</div>
		<!-- end vocabulary pane --> 
		
		<!-- Artstor search pane -->
		<div class="tab-pane" id="artstor-images">
			<div class ="row">
				
				<div class="col-md-12">
					<div class="panel panel-default" id="artstor-terms">
						<div class="panel-heading">
							<h4>Arstor Terms</h4>
						</div>
						<div class="panel-body">
						<div id="artstor-query2"></div>
							<textarea name="artstor-query" id="artstor-query" cols="100" rows="4"></textarea>
							<button type="button" class="btn btn-primary pull-right" id="get-images">Find Images!</button>
						</div>
					</div>
				</div>
			</div>
			<!-- end row / col -->
			
			<div class ="row">
				<div class="col-md-12">
					<h4>ArtStor Images</h4>
				</div>
			</div>
			<div class ="row" id="image-results">
			Results to appear here...
		
			</div>
</div>
<!-- end xquery pane -->

<!-- Don't think we want this in a tab -- we want a modal for the hierarchy
<div class="tab-pane" id="hier"> Loading <img src="assets/img/spinner.gif" alt="Loading notes..." />
	<div id="g-h"> </div>
</div>
--> 
<!-- end tab panes -->

</div>
<!-- End Results DIV -->

</div>
</div>
<!-- /.container  and row--> 

<!-- MODALS -->



<!-- image modal --> 

<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Hierarchy</h4>
			</div>
			<div class="modal-body">  </div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<!-- <button type="button" class="btn btn-primary">Submit Query</button> --> 
			</div>
		</div>
	</div>
</div>

<!-- END MODALS --> 

<!-- Bootstrap core JavaScript
    ================================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 


</body>
</html>