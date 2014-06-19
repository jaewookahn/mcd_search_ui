<?php
header("Content-type: text/html; charset=utf-8");
require "conn.php";		// connection string
$q = strtolower($_GET["term"]);

if (!$q) return;
$items= array();

$sql="select * FROM ISO.ThesaurusTerm WHERE LOWER(Term) LIKE '$q%' AND PREFERRED='P'";
$res=MySQL_query($sql);
while ($ms = MySQL_fetch_assoc($res)): //build array that will be json encoded 
	$items[$ms['term']] = $ms['identifier']; 
	//print $ms['term']; 
endwhile;
	

function array_to_json( $array ){

    if( !is_array( $array ) ){
        return false;
    }

    $associative = count( array_diff( array_keys($array), array_keys( array_keys( $array )) ));
    if( $associative ){

        $construct = array();
        foreach( $array as $key => $value ){

            // We first copy each key/value pair into a staging array,
            // formatting each key and value properly as we go.

            // Format the key:
            if( is_numeric($key) ){
                $key = "key_$key";
            }
            $key = "\"".addslashes($key)."\"";

            // Format the value:
            if( is_array( $value )){
                $value = array_to_json( $value );
            } else if( !is_numeric( $value ) || is_string( $value ) ){
                $value = "\"".addslashes($value)."\"";
            }

            // Add to staging array:
            $construct[] = "$key: $value";
        }

        // Then we collapse the staging array into the JSON form:
        $result = "{ " . implode( ", ", $construct ) . " }";

    } else { // If the array is a vector (not associative):

        $construct = array();
        foreach( $array as $value ){

            // Format the value:
            if( is_array( $value )){
                $value = array_to_json( $value );
            } else if( !is_numeric( $value ) || is_string( $value ) ){
                $value = "'".addslashes($value)."'";
            }

            // Add to staging array:
            $construct[] = $value;
        }

        // Then we collapse the staging array into the JSON form:
        $result = "[ " . implode( ", ", $construct ) . " ]";
    }

    return $result;
}

$result = array();
foreach ($items as $key=>$value) {
	if (strpos(strtolower($key), $q) !== false) {
		array_push($result, array("id"=>$value, "label"=>$key, "value" => strip_tags($key)));
		// if you want to try and add icons: 
		//array_push($result, array("id"=>$value, "label"=>"<img src=\"http://mcd.ischool.drexel.edu/search/icons.png\" height=55 width=55>" . $key , "value" => strip_tags($key)));
	}
	if (count($result) > 11)
		break;
}
echo array_to_json($result);

?>