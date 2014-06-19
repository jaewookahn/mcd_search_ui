<?php
if(isset($_GET["var"])) {
	$var = $_GET["var"];
}
else {
	$var = "";
}
?>
<html>
<head>
	
<style>
.info {
	font-weight: bold;
}
</style>
</head>
<body>
<form method="GET" action="index.php">
AAT Concept ID:<input name="var" value="<?php echo $var; ?>">
<input type="submit">
</form>


<?php
if($var == "")
	exit;

$link = mysql_connect('127.0.0.1', 'jahn', 'wodnr405');
if(!$link) {
	die("Link error:". mysql_error());
}
mysql_select_db('mcd');

$init = True;
$cids = explode(" ", $var);
// "1344472 1348238"

foreach($cids as $cid) {
	$query = "select distinct `FKOS-Concept` cid from MappingComponent mc, MappingMaster mm where `TKOS-Concept` = '".$cid."' and mc.`MappingID` = mm.`MappingID`";
	$result = mysql_query($query);
	$arr = array();
	while($o = mysql_fetch_object($result)) {
		array_push($arr, $o->cid);
	}
	print "<div class='info'>Found ". sizeof($arr) . " records matching cid:" . $cid . "</div>";
	if($init) {
		$init = False;
		$int = $arr;
	}
	else {
		$int = array_intersect($arr, $int);
	}
}

$int_cids = join(",", $int);
print $int_cids;
$query = "select StringId, stringtext from StringTable where StringID in (".$int_cids.")";

print "<div class='info'>Found ".sizeof($int)." records with all cids</div>";

$result = mysql_query($query);
while($o = mysql_fetch_object($result)) {
	print "<div>".$o->StringId. " " . $o->stringtext."</div>";
}

?>
