<?php 
require_once 'template.php';
$title = 'inContext Sensing | RDF4Sensors - REST API Specification';
printHeader($title);
?>
</head>
<?php 
$activeMenuItemNum = 2;
$body_onload = '';
$sidebar = '<div class="gadget">
<h2 class="star"><span>What</span></h2>
<div class="clr"></div>
Generate a RDF description for single sensor nodes.<br /> 
The returned description is split into sections according to the 
dependency of the included information, and "where" to store it is suggested. 
This service is available through either the manually fillable form here
or a <a href="apiDocumentation.php">RESTful API</a>.</div>
<div class="gadget">
<h2 class="star"><span>Why</span></h2>
<div class="clr"></div>
The unambiguity and schema-indepenedency features that characterize
RDF, are the key to enable 
<ul>
	<li>plug-and-play sensor nodes</li>
	<li>interoperability among different sensors</li>
</ul>
</div>';

printBodyTop($activeMenuItemNum, $sidebar, $body_onload);
?>
<h2>REST API Specification</h2>
              <p>
              HTTP Method: POST;<br />
              Payload encoding: JSON;<br />
              Accepted type: xml, json, turtle, n3, ntriples, html, plain text<br />
              RDF Serialization language supported: RDF/XML, RDF/JSON, TURTLE, N3, NTRIPLES<br />
        Resources:
        <ul>
        
        <li>http://spitfire-project.eu/incontextsensing/intrinsic<br />
        RDF representation of intrinsic (which means stored on the node itself) information<br />
        Payload example: $parameters = array("sensor_id" => "123456",
"uom" => "Centigrades - C", 
"observed_property" => "Temperature", 
"observed_value" => "10.2",
"timestamp" => "11-12-12T20:38Z",);</li>
        <li>http://spitfire-project.eu/incontextsensing/intrinsic_sider<br />
        RDF representation of sider intrinsic (which means stored anywhere but referred by the intrinsic information) information<br />
        Payload example: same as above  
        </li>

        <li>http://spitfire-project.eu/incontextsensing/node_dependent<br />
        RDF representation of node-dependent (which means that change when brand features of a particular node model change) information<br />
        Payload example: $parameters = array("sensor_id" => "123456", "observed_property" => "Temperature", "sensor_model" => "wm1015", "sensor_manual" => "http://example/sheet.pdf", 
"sensor_stimulus" => "silver expansion", "capabilities" => "Accuracy - 0.5/0.8");</li>
        <li>http://spitfire-project.eu/incontextsensing/owner_dependent<br />
        RDF representation of owner-dependent (which means that change when the sensor's owner change) information<br />
        Payload example: $parameters = array("sensor_id" => "123456","sensor_owner" => "deri", "sensor_publisher" => "myriam","sensor_foi" => "Room", 
"sensor_platform" => "http://www.example.com/platform/1","sensor_history" => "http://www.example.com/history/24");
        </li>
        </ul>
        
        </p>
        <p>
        Here follows a sample PHP client:<br />
        <div style="overflow: auto; font-style: italic;">
$intrinsic_url = "http://spitfire-project.eu/incontextsensing/rdf4sensors/intrinsic";<br />
$intrinsic_sider_url = "http://spitfire-project.eu/incontextsensing/rdf4sensors/intrinsic_sider";<br />
$node_dependent_url = "http://spitfire-project.eu/incontextsensing/rdf4sensors/node_dependent";<br />
$owner_dependent_url = "http://spitfire-project.eu/incontextsensing/rdf4sensors/owner_dependent";<br />
//---------------------------------------------------------<br />
//intrinsic<br />
//intrinsic_sider<br />
$parameters = array("sensor_id" => "123456",<br />
"uom" => "Centigrades - C",<br />
"observed_property" => "Temperature",<br />
"observed_value" => "10.2");<br />

//---------------------------------------------------------<br />
//node-dependent<br />
$parameters += array("sensor_model" => "wm1015", "sensor_manual" => "http://example/sheet.pdf",<br />
"sensor_stimulus" => "silver expansion", "capabilities" => "Accuracy - 0.5/0.8");<br />

//---------------------------------------------------------<br />
//owner-dependent<br />
$parameters += array("sensor_owner" => "deri", "sensor_publisher" => "myriam","sensor_foi" => "Room",<br />
"sensor_platform" => "http://www.example.com/platform/1","sensor_history" => "http://www.example.com/history/24");<br />

//---------------------------------------------------------<br />
$json_data = json_encode($parameters);<br />
$accepted_type = "xml";<br />
//$uri = $intrinsic_url;<br />
$uri = $intrinsic_sider_url;<br />
//$uri = $node_dependent_url;<br />
//$uri = $owner_dependent_url;<br />
//---------------------------------------------------------<br />
$response = sendRequest($json_data, $uri, $accepted_type);<br />
displayResponse($response);<br />
//---------------------------------------------------------<br />


function sendRequest($json_data, $uri, $accepted_type){<br />
        $ret = null;<br />
        $tuCurl = curl_init();<br />
        curl_setopt($tuCurl, CURLOPT_URL, $uri);<br />
        curl_setopt($tuCurl, CURLOPT_PORT , 80);<br />
        curl_setopt($tuCurl, CURLOPT_VERBOSE, 0);<br />
        curl_setopt($tuCurl, CURLOPT_HEADER, false);<br />
        curl_setopt($tuCurl, CURLOPT_POST, true);<br />
        curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, true);<br />
        curl_setopt($tuCurl, CURLOPT_POSTFIELDS, $json_data);<br />
        curl_setopt($tuCurl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml", "Accept: $accepted_type",<br />
"inContextSensing: RDF4Sensors intrinsic representation", "Content-length: ".strlen($json_data)));<br />


        $response = curl_exec($tuCurl);<br />
        if(!curl_errno($tuCurl)){<br />
                $ret = $response;<br />
                //              $info = curl_getinfo($tuCurl);<br />
                //      echo 'Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url'];<br />
        } else {<br />
                $ret = curl_error($tuCurl);<br />
        }<br />
        curl_close($tuCurl);<br />
        return $ret;<br />
}<br />
<br />
function displayResponse($response){<br />
        echo $response;<br />
}

        </div>
        </p>        
        
<?php 
$copyright_uri = 'http://www.deri.ie';
$copyright_name = 'DERI';
printFooter($copyright_uri, $copyright_name);
?>
 
