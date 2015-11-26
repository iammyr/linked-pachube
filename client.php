<?php
$intrinsic_url = "http://localhost/incontextsensing/rdf4sensors/intrinsic";
$intrinsic_sider_url = "http://localhost/incontextsensing/rdf4sensors/intrinsic_sider";
$node_dependent_url = "http://localhost/incontextsensing/rdf4sensors/node_dependent";
$owner_dependent_url = "http://localhost/incontextsensing/rdf4sensors/owner_dependent";
//---------------------------------------------------------
//intrinsic
//intrinsic_sider
$parameters = array("sensor_id" => "123456",
"uom" => "Centigrades - C", 
"observed_property" => "Temperature", 
"observed_value" => "10.2");

//---------------------------------------------------------
//node-dependent
$parameters += array("sensor_model" => "wm1015", "sensor_manual" => "http://example/sheet.pdf", 
"sensor_stimulus" => "silver expansion", "capabilities" => "Accuracy - 0.5/0.8");

//---------------------------------------------------------
//owner-dependent
$parameters += array("sensor_owner" => "deri", "sensor_publisher" => "myriam","sensor_foi" => "Room", 
"sensor_platform" => "http://www.example.com/platform/1","sensor_history" => "http://www.example.com/history/24");

//---------------------------------------------------------
$json_data = json_encode($parameters);
$accepted_type = "xml";
//$uri = $intrinsic_url;
//$uri = $intrinsic_sider_url;
//$uri = $node_dependent_url;
$uri = $owner_dependent_url;
//---------------------------------------------------------
$response = sendRequest($json_data, $uri, $accepted_type);
displayResponse($response);
//---------------------------------------------------------


function sendRequest($json_data, $uri, $accepted_type){
	$ret = null;
	$tuCurl = curl_init();
	curl_setopt($tuCurl, CURLOPT_URL, $uri);
	curl_setopt($tuCurl, CURLOPT_PORT , 80);
	curl_setopt($tuCurl, CURLOPT_VERBOSE, 0);
	curl_setopt($tuCurl, CURLOPT_HEADER, false);
	curl_setopt($tuCurl, CURLOPT_POST, true);
	curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($tuCurl, CURLOPT_POSTFIELDS, $json_data);
	curl_setopt($tuCurl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml", "Accept: $accepted_type",
"inContextSensing: RDF4Sensors intrinsic representation", "Content-length: ".strlen($json_data)));


	$response = curl_exec($tuCurl);
	if(!curl_errno($tuCurl)){
		$ret = $response;
		//		$info = curl_getinfo($tuCurl);
		//	echo 'Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url'];
	} else {
		$ret = curl_error($tuCurl);
	}
	curl_close($tuCurl);
	return $ret;
}

function displayResponse($response){
	echo $response;
}


?>