<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
<title>MCD API - Help</title>
<style type="text/css">
body {
	font-family: Arial, Helvetica, sans-serif;
}
h1 {
	border-bottom-width: thin;
	border-bottom-style: solid;
	border-bottom-color: #333;
}
.alert {
	color: #F00;
}
.returns {
	font-family: "Lucida Console", Monaco, monospace;
	color: #666;
	font-size: 90%;
}
</style>
</head>

<body>
<h1>Meaningful Concept Displays: API Help 

</h1>
<p><?php 
echo " {This document last modified: " . date ("F d Y H:i:s.", getlastmod());
echo "}";
?></p>
<p class="alert"><strong>IMPORTANT!!! Read this message first!   </strong></p>
<p>IDENTIFIERS HAVE BEEN UPDATED (7/23/2012) <br>
Because we are importing from many different sources, we must ensure that identifiers accurately and uniquely identify rows in our ISO database, as follows: </p>
<ul>
  <li>Prepended &quot;ga_&quot; for GettyAAT concepts, &quot;gt_&quot; for GettyTGN concepts, and &quot;gu_&quot; for GettyTGN concepts. </li>
  <li>So, an identifier that was &quot;12345&quot; is now &quot;ga_12345&quot;</li>
  <li class="alert">Note that ULAN at least has NON-UNIQUE Term IDs provided in the data files. This must be addressed to ensure correct data returned from the API<br>
  </li>
</ul>
<hr>
<h2>Instructions and examples: </h2>
<p>The MCD API takes a querystring and returns results as <a href="http://www.json.org/" target="_blank">JSON</a>. Try it out: <a href="http://rack90.cs.drexel.edu/search/api/GetRelatedTerms.php?q=washington">http://rack90.cs.drexel.edu/search/api/GetRelatedTerms.php?q=washington</a><br>
Queries to the API without a querystring will result in empty result sets.</p>
<p>A scenario:</p>
<ol>
  <li>Query sent for  term &quot;washington&quot; - which returns an array of rows that match that query. <br>
  </li>
  <li>Using the returned conceptID, get the parent and child of each concept. </li>
</ol>
<p>&nbsp;</p>
<h2>Pages</h2>
<ul>
  <li><strong>GetRelatedTerms.php</strong>
<ul>
      <li>Input: A string
        <ul>
          <li>Example: <a href="http://rack90.cs.drexel.edu/search/api/GetRelatedTerms.php?q=washington">http://rack90.cs.drexel.edu/search/api/GetRelatedTerms.php?q=washington</a></li>
        </ul>
      </li>
      <li>Output: The row(s) that match the query
        <ul>
          <li class="returns">{"identifier":"gt_3157","conceptID":"gt_1002992","term":"Washington","created":null,"modified":null,"source":"GettyTGN","status":null,"lang":null,"preferred":"P","flag":null,"end_date":null,"lterm":null,"MCD_ID":"224136","score":"7.94309091567993"},</li>
        </ul>
      </li>
    </ul>
  </li>
  <li><strong>GetParents.php</strong>
<ul>
      <li>Input: Concept identfiers, <strike>Number of parents to return (will stop when it reaches database root)</strike>. <br>
<ul>
          <li>http://rack90.cs.drexel.edu/search/api/GetParents.php?q=ga_300023117</li>
        </ul>
      </li>
      <li>Output: Parents (conceptA_ID) and Child (conceptB_ID)</li>
      <li class="returns">[{"role":"P","conceptA_ID":"ga_300163316","conceptB_ID":"ga_300023117","preferred":"P","hier_type":"G","sourceVocab":"GettyAAT","parents":"ga_300163316"}]</li>
    </ul>
  </li>
  <li><strong>GetChild.php</strong>
<ul>
      <li>Input: Concept identfiers
        <ul>
          <li><a href="http://rack90.cs.drexel.edu/search/api/GetChild.php?q=ga_300022973,ga_300023451">http://rack90.cs.drexel.edu/search/api/GetChild.php?q=ga_300022973,ga_300023451</a></li>
        </ul>
      </li>
      <li>Output: 
        <ul>
          <li class="returns">[{"role":"P","conceptA_ID":"ga_300022973","conceptB_ID":"ga_300022351","preferred":"P","hier_type":"G","sourceVocab":"GettyAAT"},{"role":"P","conceptA_ID":"ga_300022973","conceptB_ID":"ga_300022974","preferred":"P","hier_type":"G","sourceVocab":"GettyAAT"},{"role":"P","conceptA_ID":"ga_300022973","conceptB_ID":"ga_300022975","preferred":"P","hier_type":"G","sourceVocab":"GettyAAT"},{"role":"P","conceptA_ID":"ga_300022973","conceptB_ID":"ga_300022976","preferred":"P","hier_type":"G","sourceVocab":"GettyAAT"},{"role":"P","conceptA_ID":"ga_300022973","conceptB_ID":"ga_300022986","preferred":"P","hier_type":"G","sourceVocab":"GettyAAT"},{"role":"P","conceptA_ID":"ga_300022973","conceptB_ID":"ga_300022988","preferred":"P","hier_type":"G","sourceVocab":"GettyAAT"},{"role":"P","conceptA_ID":"ga_300022973","conceptB_ID":"ga_300022991","preferred":"P","hier_type":"G","sourceVocab":"GettyAAT"},{"role":"P","conceptA_ID":"ga_300022973","conceptB_ID":"ga_300023023","preferred":"P","hier_type":"G","sourceVocab":"GettyAAT"},{"role":"P","conceptA_ID":"ga_300022973","conceptB_ID":"ga_300023027","preferred":"P","hier_type":"G","sourceVocab":"GettyAAT"},{"role":"P","conceptA_ID":"ga_300022973","conceptB_ID":"ga_300023050","preferred":"P","hier_type":"G","sourceVocab":"GettyAAT"},{"role":"P","conceptA_ID":"ga_300022973","conceptB_ID":"ga_300023055","preferred":"P","hier_type":"G","sourceVocab":"GettyAAT"},{"role":"P","conceptA_ID":"ga_300022973","conceptB_ID":"ga_300023056","preferred":"P","hier_type":"G","sourceVocab":"GettyAAT"},{"role":"P","conceptA_ID":"ga_300022973","conceptB_ID":"ga_300023064","preferred":"P","hier_type":"G","sourceVocab":"GettyAAT"},{"role":"P","conceptA_ID":"ga_300022973","conceptB_ID":"ga_300023066","preferred":"P","hier_type":"G","sourceVocab":"GettyAAT"},{"role":"P","conceptA_ID":"ga_300022973","conceptB_ID":"ga_300023071","preferred":"P","hier_type":"G","sourceVocab":"GettyAAT"},{"role":"P","conceptA_ID":"ga_300022973","conceptB_ID":"ga_300023074","preferred":"P","hier_type":"G","sourceVocab":"GettyAAT"},{"role":"P","conceptA_ID":"ga_300022973","conceptB_ID":"ga_300023075","preferred":"P","hier_type":"G","sourceVocab":"GettyAAT"},{"role":"P","conceptA_ID":"ga_300022973","conceptB_ID":"ga_300023076","preferred":"P","hier_type":"G","sourceVocab":"GettyAAT"},{"role":"P","conceptA_ID":"ga_300022973","conceptB_ID":"ga_300023081","preferred":"P","hier_type":"G","sourceVocab":"GettyAAT"},{"role":"P","conceptA_ID":"ga_300022973","conceptB_ID":"ga_300023093","preferred":"P","hier_type":"G","sourceVocab":"GettyAAT"},{"role":"P","conceptA_ID":"ga_300022973","conceptB_ID":"ga_300023142","preferred":"P","hier_type":"G","sourceVocab":"GettyAAT"}]</li>
        </ul>
      </li>
      <li>GetNotes
        <ul>
          <li>Input: Concept identfiers
            <ul>
              <li><a href="http://rack90.cs.drexel.edu/search/api/GetNotes.php?q=ga_300023456,ga_300023451">http://rack90.cs.drexel.edu/search/api/GetNotes.php?q=ga_300023456,ga_300023451 </a></li>
            </ul>
          </li>
          <li>Output: 
            <ul>
              <li class="returns">[{"MCD_NOTE_ID":"83562","noteType":"SN","text":"Cylindrical cases to hold pins.","conceptID":"ga_300023456","lang":null,"contributor":null,"created":null,"modified":null,"sourceVocab":"GettyAAT"},{"MCD_NOTE_ID":"83568","noteType":"SN","text":"Cases for holding sewing needles.","conceptID":"ga_300023451","lang":null,"contributor":null,"created":null,"modified":null,"sourceVocab":"GettyAAT"}]</li>
            </ul>
          </li>
        </ul>
      </li>
    </ul>
  </li>
  <li><strong>GetAssociative.php</strong>
<ul>
      <li>Input: Concept identfiers
        <ul>
          <li><a href="http://rack90.cs.drexel.edu/search/api/GetAssociative.php?q=ga_300248289,ga_300015640">http://rack90.cs.drexel.edu/search/api/GetAssociative.php?q=ga_300248289,ga_300015640</a></li>
        </ul>
      </li>
      <li>Output: First concept (conceptA_ID) and  associated concept (conceptB_ID)<br>
        Note: this will return concepts for the query as conceptA_ID<em> - AND AS -</em> conceptB_ID, so you should see reciprocal relationships in the JSON
        <ul>
          <li class="returns">[{"role":null,"conceptA_ID":"ga_300025417","conceptB_ID":"ga_300248289","associationType":"2100","sourceVocab":"GettyAAT","description":"distinguished from","relatedType":"2100"},{"role":null,"conceptA_ID":"ga_300248289","conceptB_ID":"ga_300025417","associationType":"2100","sourceVocab":"GettyAAT","description":"distinguished from","relatedType":"2100"},{"role":null,"conceptA_ID":"ga_300015640","conceptB_ID":"ga_300053257","associationType":"2427","sourceVocab":"GettyAAT","description":"activity\/event producing is","relatedType":"2428"},{"role":null,"conceptA_ID":"ga_300015640","conceptB_ID":"ga_300015643","associationType":"2100","sourceVocab":"GettyAAT","description":"distinguished from","relatedType":"2100"},{"role":null,"conceptA_ID":"ga_300015640","conceptB_ID":"ga_300015641","associationType":"2100","sourceVocab":"GettyAAT","description":"distinguished from","relatedType":"2100"},{"role":null,"conceptA_ID":"ga_300015641","conceptB_ID":"ga_300015640","associationType":"2100","sourceVocab":"GettyAAT","description":"distinguished from","relatedType":"2100"},{"role":null,"conceptA_ID":"ga_300015643","conceptB_ID":"ga_300015640","associationType":"2100","sourceVocab":"GettyAAT","description":"distinguished from","relatedType":"2100"},{"role":null,"conceptA_ID":"ga_300053257","conceptB_ID":"ga_300015640","associationType":"2428","sourceVocab":"GettyAAT","description":"document(s)\/product(s) created are","relatedType":"2427"}]</li>
        </ul>
      </li>
    </ul>
  </li>
  <li><strong>GetVariantTerms.php</strong>
<ul>
      <li>Input: A concept identifier
        <ul>
          <li><a href="http://rack90.cs.drexel.edu/search/api/GetVariantTerms.php?q=gt_1002992">http://rack90.cs.drexel.edu/search/api/GetVariantTerms.php?q=gt_1002992</a>
            <ul>
              <li class="returns">{"identifier":"gt_3157","conceptID":"gt_1002992","term":"Washington","created":null,"modified":null,"source":"GettyTGN","status":null,"lang":null,"preferred":"P","flag":null,"end_date":null,"lterm":null,"MCD_ID":"224136"},{"identifier":"gt_316960","conceptID":"gt_1002992","term":"Washington county","created":null,"modified":null,"source":"GettyTGN","status":null,"lang":null,"preferred":"V","flag":null,"end_date":null,"lterm":null,"MCD_ID":"224137"}</li>
            </ul>
          </li>
        </ul>
      </li>
</ul>
  </li>
  <li><strong>GetCoordinates.php</strong>
<ul>
      <li>Input: Concept identifiers
        <ul>
          <li>http://rack90.cs.drexel.edu/search/api/GetCoordinates.php?q=1131941,1042310</li>
        </ul>
      </li>
      <li>Output: 
        <ul>
          <li class="returns">[{"SUBJECT_ID":"1042310","LAT_DECIMAL":"46","LAT_DEGREE":"46","LAT_MIN":"16","LAT_DIRECTION":"N","LONG_DECIMAL":"17","LONG_DEGREE":"17","LONG_MIN":"9","LONG_DIRECTION":"E"},{"SUBJECT_ID":"1131941","LAT_DECIMAL":"44","LAT_DEGREE":"43","LAT_MIN":"40","LAT_DIRECTION":"N","LONG_DECIMAL":"124","LONG_DEGREE":"123","LONG_MIN":"35","LONG_DIRECTION":"E"}] </li>
        </ul>
      </li>
    </ul>
  </li>
</ul>
</body>
</html>