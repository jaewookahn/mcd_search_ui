This page gets a children and notes for a term 


<?php 

$query=$_GET['q']; 	// the text query
print " for $query<hr>";
$f = file_get_contents ("http://http://rack90.cs.drexel.edu/search/api/GetVariantTerms.php?q=gt_1002992");

print $f;

?>