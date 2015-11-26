<?php

require_once 'resources/AbstractResource.php';

function conceptCleaner($term){
	$ret = str_ireplace(" ", "_", $term);
	if (endsWith($ret, 's')){
		$ret = substr($ret, 0, -1);
	}
	return $ret;
}

function conceptFinder($resource, $id, $foaf=false){
	$uri = '';
	$sparql_answer = '';
	if (!$foaf){
		$requestURL = createDBpediaQuery($id);
		$response = request($requestURL);
		//	echo $response;
		$responseArray = json_decode($response,	true);
		if ($responseArray && $responseArray['results'] && $responseArray['results']['bindings']
		&& $responseArray['results']['bindings'][0] && $responseArray['results']['bindings'][0]['subj']
		&& $responseArray['results']['bindings'][0]['subj']['value']){
			$results = $responseArray['results']['bindings'];
			$uri = $results[0]['subj']['value'];
		}
	}else{
		$requestURL = createFoafSearchQuery($id);
		$response = request($requestURL);
//		echo $response;
		$responseArray = json_decode($response,	true);
		if ($responseArray && $responseArray['results'] && $responseArray['results'][0]
		&& $responseArray['results'][0]['link_text']){
			$results = $responseArray['results'];
			$tot = sizeof($results);
			$parenthesis = false;
//			echo 'row='.$results[0]['link_text'];
//			echo 'id='.$id;
			for ($ind=0; $ind<$tot && $parenthesis === false; $ind++){
				$row = $results[$ind]['link_text'];
//				echo 'row='.$row;
				if (strpos(strtolower($row), strtolower($id)) !== false){
					//	the uri is among parenthesis
					$parenthesis = strpos($row, '(');
					if ($parenthesis !== false){
						$uri = substr($row, $parenthesis+1, -1);
						//					echo 'uri='.$uri;
					}
				}
			}
//			$uri = "http://www.foaf-search.net/Profile?personid=".$results[0]['id'];
		}
	}
	if (!$uri){
		$uri = AbstractResource::$resource_base.$resource.'/'.trim($id);
	}
//	echo $uri;
	return $uri;
}

function createFoafSearchQuery($id){
	$searchUrl = 'http://www.foaf-search.net/api/rest?access_key='
	.AbstractResource::$foaf_query_access_key.'&method=search&query='
	.urlencode($id);
	return $searchUrl;
}

function createDBpediaQuery($id)
{
	$format = 'json';

	$query ='  PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
   
   SELECT ?subj WHERE {
     {?subj rdfs:label "'.$id.'"@en} 
        
}'; 
	$searchUrl = 'http://dbpedia.org/sparql?'
	.'query='.urlencode($query)
	.'&format='.$format;

	return $searchUrl;
}

function request($url){

	// is curl installed?
	if (!function_exists('curl_init')){
		die('CURL is not installed!');
	}

	// get curl handle
	$ch= curl_init();

	// set request url
	curl_setopt($ch,
	CURLOPT_URL,
	$url);

	// return response, don't print/echo
	curl_setopt($ch,
	CURLOPT_RETURNTRANSFER,
	true);

	$response = curl_exec($ch);

	curl_close($ch);

	return $response;
}

function printArray($array, $spaces = "")
{
	$retValue = "";

	if(is_array($array))
	{
		$spaces = $spaces
		."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

		$retValue = $retValue."<br/>";

		foreach(array_keys($array) as $key)
		{
			$retValue = $retValue.$spaces
			."<strong>".$key."</strong>"
			.printArray($array[$key],
			$spaces);
		}
		$spaces = substr($spaces, 0, -30);
	}
	else $retValue =
	$retValue." - ".$array."<br/>";

	return $retValue;
}
/**
 $id = "Temperature";

 $requestURL = conceptFinder('observed_property', $id);
 $responseArray = json_decode(
 request($requestURL),
 true);
 $results = $responseArray['results']['bindings'];
 $uri = $results['subj']['value'];

 ?>

 <h1>DBPedia Abstract for <?php echo $term ?></h1>

 <h3>Request URL:</h3>
 <?php echo $requestURL ?>
 <br />

 <h3>Parsed Response:</h3>
 <?php echo printArray($responseArray);
 echo json_encode($responseArray); ?>
 <br />

 <h3>Abstract:</h3>
 <?php echo $responseArray["results"]
 ["bindings"][0]
 ["abstract"]["value"] **/

?>

