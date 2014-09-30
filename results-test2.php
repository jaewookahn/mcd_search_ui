<?php
$query=htmlspecialchars($_GET["q"]);

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
	height: 400px;
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

	$("#collap").click(function(){

        $("#collapseOne").collapse('toggle');

    });

$('.show-art').click(function(e){
      e.preventDefault();
      var link = "http://rack90.cs.drexel.edu/ahn/mcd_qe/api.cgi?mode=searchartstor&query=watercolor"; //$(this).attr("title");
      $('#g-h').load(link);
      
  });



/*

  $(".show-art").click(function(){
	var a=$(this).attr('title');
    //$.ajax({url:"http://rack90.cs.drexel.edu/search/getDetails.php?id="+a,
	$.ajax({url:"http://rack90.cs.drexel.edu/ahn/mcd_qe/api.cgi?mode=searchartstor&query=watercolor",
	beforeSend:function(){
	$('#artstor-terms').html('<div class="loading"><strong>Loading term info</strong> <img src="assets/img/spinner.gif" alt="Loading notes..." /></div>');
	},
	success:function(result){
      $("#artstor-terms").html(result);
    },
	error:function(){
		$('#artstor-terms').html('<p class="error">Sorry, there was an error</p>' +a);
	}
	}); // end AJAX
  });
  */
	// add term to expanded query box  
  	$(".insert").click(function (e) {
    	var insertText = '<div class="checkbox"><label><input type="checkbox" checked name="chk" value="' + $(this).attr('title') + '">' + $(this).attr('title') + '</label></div><input type="checkbox" checked name="tess" value="tess">';
       	$('#xquery').append(" "+insertText);
	});
	/////////
	
	$('[data-toggle="tabajax"]').click(function(e) {
    var $this = $(this),
        //loadurl = $this.attr('href'),
		loadurl='getDetails.php?id=2658',
        targ = $this.attr('data-target');

    $.get(loadurl, function(data) {
        $(targ).html(data);
    });

    $this.tab('show');
    return false;
});

/*
$('#testy').click(function () { 
    	$(this).text('mike');
		var b = $(this).attr('title');	// the conceptID we will fetch 
		$.ajax({	
			type: "GET",
			url: "http://rack90.cs.drexel.edu/search2/notesDiv2.php?q="+b,			
			beforeSend:function(){
				// this is where we append a loading image
				$(a).html('<div class="loading"><strong>Loading term info</strong> <img src="assets/img/spinner.gif" alt="Loading notes..." /></div>');
				$('#summary').html('<div class="loading"><strong>Loading term info</strong> <img src="assets/img/spinner.gif" alt="Loading notes..." /></div>');
			},
			success : function( resp ) {
				// add content to the DIV 
				$(a).empty();
					$(a).html($('#inner_1' , resp).html());
					$('#summary').html($('#inner_2' , resp).html());
			}, 
			error:function(){
				// failed request; give feedback to user
				$(a).html('<p class="error">Error: No notes could be found for this term!</p>');
				$('#summary').html('<p class="error">Error retrieving hierarchy</p>');
			}
		}); // end ajax		
		
		
    }) // end click function
*/

});

</script>
</head>

<body>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
			<a class="navbar-brand" href="#">Meaningful Concept Displays</a> </div>
		<div class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<li class="active"><a href="#">Home</a></li>
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
		<li><a href="#artstor-images" data-toggle="tab"><span class="glyphicon glyphicon glyphicon-search pull-left"></span>&nbsp;ArtStor</a></li>
		<li><a href="getDetails.php?id=2658" data-toggle="tabajax" data-target="#hier"><span class="glyphicon glyphicon glyphicon-th-list"></span>&nbsp;Getty Hierarchy</a></li>
	</ul>
	
	<!-- Tab panes -->
	<div class="tab-content">
		<div class="tab-pane active" id="vocab">
			<h3>Getty Results: <?php echo "$query"; ?>
			</h3>
			<div class ="row">
				<?php 
					
					if (empty($query)){ 
						echo "error, you did not specify a querystring! (?q=xyz)"; 
					}
					
					$url = "http://rack90.cs.drexel.edu:8080/MCD3/SearchGroupedConcepts?q=$query&limit=50";
					$json = file_get_contents($url);
					$data = json_decode($json, TRUE);
					
					for ($i = 0, $size = count($data); $i < $size; ++$i) { // entire json array
						//print_r($data[$i]);
						
						echo '<div class="panel panel-default col-md-4 mygrid-wrapper-div">'; // start panel for this facet
						$c= count($data[$i]['concepts']);
						print '<h5>' .$data[$i]['facetString'] . '</a> <span class="badge pull-right">'. $c . '</span></h5>'; // facet name
						print '<table class="table table-condensed">';
						$c= count($data[$i]['concepts']);
						foreach ($data[$i]['concepts'] as $z){ //concepts array for each facet
							echo '<tr>';
							echo '<td>' . $z['preferredstring'] . ' ( '. $z['id'] . ' ) 
							</td><td>
							<span class="glyphicon glyphicon glyphicon-font pull-right" id="collap"> 
							</span></td><td><a href="#" title ="' . $z['preferredstring'] . '&#10;" class="insert">
							<span class="glyphicon glyphicon glyphicon-search pull-right"></span></a></td></tr>';
							// $z['id'] - the id for this concept --- &#10; is the linefeed HTML entity 
						}
						
						if ($c == "49"){ echo '<li class="list-group-item">View All in Facet</li>'; } // if max 50 items id reached, provide link to facet page 
						
						print '</table></div>'; // end panel and panel body for this facet
					}
			
				?>
			</div>
			<!-- end row  --> 
			
		</div>
		<!-- end vocabulary pane -->

<!-- Artstor search pane -->		
		<div class="tab-pane" id="artstor-images">
			<div class ="row">
				<div class="col-md-12">
				<h3>Instructions: </h3>
				<ol>
				<li>Review the Getty Terms for mapping, click "Map it!" to map Getty to Artstor.</li>
				<li>Review the Artstor Terms, click Image Search to search for Artstor Images using the selected query terms.</li>
				</ol>
				
			<div class ="row">
				<div class="col-md-6">
					<h4><span class="label label-default">1 </span> &nbsp;Getty Terms&nbsp;<span class="glyphicon glyphicon-search pull-right"></span></h4>
					<div id="expanded_query">
						<div class="panel panel-default">
							<div class="panel-body">
								<form action="http://rack90.cs.drexel.edu/ahn/mcd_qe/api.cgi?mode=searchartstor&query=oil%20on%20canvas">
									<!-- <textarea name="xquery" id="xquery" cols="200" rows="5">adasd</textarea> -->
									
									<div id="xquery"></div>
									<input name="sub_xquery" type="submit" class="btn btn-primary">
									<input type="radio" name="andor" value="and">
									And
									<input type="radio" name="andor" value="or">
									Or
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<h4><span class="label label-default">2 </span> &nbsp;Arstor Terms&nbsp;<span class="glyphicon glyphicon-picture pull-right"></span></h4>
					<div id="artstor-terms"> <br /><p>Artstor terms mapped to Getty will appear here....</p></div>
				</div>
			
			</div>
			<!-- end row / col -->
			
			<div class ="row">
				<div class="col-md-12">
					<h3>ArtStor Images</h3>
				</div>
			</div>
			<div class ="row">
				<div class="col-md-3"> <a href="#" data-toggle="modal" data-target="#myModal2"><img src="artstor-sample.jpg" alt="art" width="183" height="178">
					Lorem Ipsum</a> </div>
				<div class="col-md-3 well well-sm"> <img src="artstor-sample.jpg" alt="art" width="183" height="178"> <br>
					Lorem Ipsum </div>
				<div class="col-md-3 well well-sm"> <img src="artstor-sample.jpg" alt="art" width="183" height="178"> <br>
					Lorem Ipsum </div>
				<div class="col-md-3 well well-sm"> <img src="artstor-sample.jpg" alt="art" width="183" height="178"> <br>
					Lorem Ipsum </div>
			</div>
			<!-- end row 2 -->
			<div class ="row">
				<div class="col-md-3 well well-sm"> <img src="artstor-sample.jpg" alt="art" width="183" height="178"> <br>
					Lorem Ipsum </div>
				<div class="col-md-3 well well-sm"> <img src="artstor-sample.jpg" alt="art" width="183" height="178"> <br>
					Lorem Ipsum </div>
				<div class="col-md-3 well well-sm"> <img src="artstor-sample.jpg" alt="art" width="183" height="178"> <br>
					Lorem Ipsum </div>
				<div class="col-md-3 well well-sm"> <img src="artstor-sample.jpg" alt="art" width="183" height="178"> <br>
					Lorem Ipsum </div>
			</div>
			<div class ="row">
				<div class="col-md-3 well well-sm"> <img src="artstor-sample.jpg" alt="art" width="183" height="178"> <br>
					Lorem Ipsum </div>
				<div class="col-md-3 well well-sm"> <img src="artstor-sample.jpg" alt="art" width="183" height="178"> <br>
					Lorem Ipsum </div>
				<div class="col-md-3 well well-sm"> <img src="artstor-sample.jpg" alt="art" width="183" height="178"> <br>
					Lorem Ipsum </div>
				<div class="col-md-3 well well-sm"> <img src="artstor-sample.jpg" alt="art" width="183" height="178"> <br>
					Lorem Ipsum </div>
			</div>
			<ul class="pagination">
				<li><a href="#">&laquo;</a></li>
				<li><a href="#">1</a></li>
				<li><a href="#">2</a></li>
				<li><a href="#">3</a></li>
				<li><a href="#">4</a></li>
				<li><a href="#">5</a></li>
				<li><a href="#">&raquo;</a></li>
			</ul>
		</div>
		<!-- end xquery pane -->
		
		<div class="tab-pane" id="hier"> 
		Loading <img src="assets/img/spinner.gif" alt="Loading notes..." />
		<div id="g-h">
		 </div>
	
	
	
	</div>
	<!-- end tab panes --> 
	
</div>
<!-- End Results DIV -->

</div>
</div>
<!-- /.container  and row--> 

<!-- MODALS -->
<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Maria de Medici</h4>
			</div>
			<div class="modal-body"> <img src="artstor-big.jpg" alt="astore" width="568" height="405"> </div>
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

<script>  
$(function ()  
{ $("#example").popover('hover');
});  

</script>
</body>
</html>