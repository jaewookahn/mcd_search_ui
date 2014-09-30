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

	$("#collap").click(function(){

        $("#collapseOne").collapse('toggle');

    });


  $(".shownotes").click(function(){
	var a=$(this).attr('title');
    $.ajax({url:"http://rack90.cs.drexel.edu/search/getDetails.php?id="+a,
	beforeSend:function(){
	$('#summary').html('<div class="loading"><strong>Loading term info</strong> <img src="assets/img/spinner.gif" alt="Loading notes..." /></div>');
	},
	success:function(result){
      $("#summary").html(result);
    },
	error:function(){
		$('#summary').html('<p class="error">Sorry, there was an error</p>' +a);
	}
	}); // end AJAX
  });
  
	// add term to expanded query box  
  	$(".insert").click(function () {
    	var insertText = $(this).attr('title');
       	$('#xquery').append(" "+insertText);
	});
	/////////

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
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Meaningful Concept Displays</a>
        </div>
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
		  
		  
        </div><!--/.nav-collapse -->
      </div>
    </div>

<div class="container">
<p>&nbsp;</p>
<!-- tab navigation --> 
<ul class="nav nav-tabs">
  <li class="active"><a href="#vocab" data-toggle="tab">Vocabulary Results</a></li>
  <li><a href="#artstor-images" data-toggle="tab">Expanded Query and Images</a></li>
  <li><a href="#hier" data-toggle="tab">Hierarchy</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
  <div class="tab-pane active" id="vocab">

			<h3>Getty AAT Results: <?php echo "$query"; ?></h3>
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
						print '';
						$c= count($data[$i]['concepts']);
						foreach ($data[$i]['concepts'] as $z){ //concepts array for each facet
							echo '<p>';
							echo '<a href="#" class="shownotes" title="' . $z['id'] . '">' . $z['preferredstring'] . '</a>
							&nbsp;<span class="glyphicon glyphicon glyphicon-font pull-right" id="collap"> 
							</span>&nbsp;&nbsp;<a href="#" title ="' . $z['preferredstring'] . '&#10;" class="insert">
							<span class="glyphicon glyphicon-th-large pull-right"></span></a></p>';
							// $z['id'] - the id for this concept --- &#10; is the linefeed HTML entity 
						}
						
						if ($c == "49"){ echo '<li class="list-group-item">View All in Facet</li>'; } // if max 50 items id reached, provide link to facet page 
						
						print '</div>'; // end panel and panel body for this facet
					}
			
				?>
				
				
				</div> <!-- end row  --> 


  
  </div> <!-- end vocabulary pane --> 
  
  
  
  <div class="tab-pane" id="artstor-images">
  
  <h4><span class="glyphicon glyphicon-th-large pull-left"></span>&nbsp;&nbsp; Expanded Query</h4>
	<div id="expanded_query"> 
	<div class="panel panel-default"> <div class="panel-body">
		<form action="http://rack90.cs.drexel.edu/ahn/mcd_qe/api.cgi?mode=searchartstor&query=oil%20on%20canvas">
			<textarea name="xquery" id="xquery" cols="335" rows="5"></textarea><br>
			<input name="sub_xquery" type="submit" class="btn btn-primary"> <input type="radio" name="andor" value="and">And  <input type="radio" name="andor" value="or">Or
		</form>
	</div></div>
	</div>
	
	<div class ="row">
		<div class="col-md-12">
		<h3>ArtStor Images</h3>
		</div>
	</div>	
	
	
	<div class ="row">
	<div class="col-md-3 well well-sm">
	<a href="#" data-toggle="modal" data-target="#myModal2"><img src="artstor-sample.jpg" alt="art" width="183" height="178">
	<br>
	Lorem Ipsum</a>
	</div>	
	
	<div class="col-md-3 well well-sm">
	<img src="artstor-sample.jpg" alt="art" width="183" height="178">
	<br>
	Lorem Ipsum
	</div>	
	
	<div class="col-md-3 well well-sm">
	<img src="artstor-sample.jpg" alt="art" width="183" height="178">
	<br>
	Lorem Ipsum
	</div>	
	
	<div class="col-md-3 well well-sm">
	<img src="artstor-sample.jpg" alt="art" width="183" height="178">
	<br>
	Lorem Ipsum
	</div>	
	</div> <!-- end row 2 -->	
	<div class ="row">
	<div class="col-md-3 well well-sm">
	<img src="artstor-sample.jpg" alt="art" width="183" height="178">
	<br>
	Lorem Ipsum
	</div>	
	
	<div class="col-md-3 well well-sm">
	<img src="artstor-sample.jpg" alt="art" width="183" height="178">
	<br>
	Lorem Ipsum
	</div>	
	
	<div class="col-md-3 well well-sm">
	<img src="artstor-sample.jpg" alt="art" width="183" height="178">
	<br>
	Lorem Ipsum
	</div>	
	
	<div class="col-md-3 well well-sm">
	<img src="artstor-sample.jpg" alt="art" width="183" height="178">
	<br>
	Lorem Ipsum
	</div>	
	</div>
	<div class ="row">
	<div class="col-md-3 well well-sm">
	<img src="artstor-sample.jpg" alt="art" width="183" height="178">
	<br>
	Lorem Ipsum
	</div>	
	
	<div class="col-md-3 well well-sm">
	<img src="artstor-sample.jpg" alt="art" width="183" height="178">
	<br>
	Lorem Ipsum
	</div>	
	
	<div class="col-md-3 well well-sm">
	<img src="artstor-sample.jpg" alt="art" width="183" height="178">
	<br>
	Lorem Ipsum
	</div>	
	
	<div class="col-md-3 well well-sm">
	<img src="artstor-sample.jpg" alt="art" width="183" height="178">
	<br>
	Lorem Ipsum
	</div>	
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
  
  
  </div> <!-- end xquery pane --> 
  
  <div class="tab-pane" id="hier">
  
  In hac habitasse platea dictumst. Suspendisse vitae iaculis risus. Pellentesque accumsan, mauris vel sollicitudin tempus, arcu elit feugiat dolor, et blandit tortor quam a sem. In tristique enim quam, eget pulvinar orci varius in. Sed sed justo feugiat, egestas lorem nec, tincidunt mauris. Vestibulum ac dapibus lectus, et dictum lacus. Vivamus nec viverra mi.

Duis eget viverra mauris. Vivamus vel convallis magna. Sed diam justo, fringilla vel dolor sed, laoreet dapibus quam. Suspendisse mattis consequat libero at faucibus. Quisque adipiscing scelerisque tellus ac interdum. Quisque eu consectetur sapien, nec semper lectus. Sed eget ipsum ultrices nibh tincidunt varius et nec felis.

</div>
  
</div> <!-- end tab panes -->


	
	
		  
</div>	  <!-- End Results DIV -->
		  
		  



</div></div><!-- /.container  and row-->


<!-- MODALS --> 
<div class="modal fade" id="myModal2" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Maria de Medici</h4>
      </div>
      <div class="modal-body">
        <img src="artstor-big.jpg" alt="astore" width="568" height="405">
      </div>
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
	
</body></html>