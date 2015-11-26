<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>inContext Sensing</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/cufon-yui.js"></script>
<script type="text/javascript" src="js/arial.js"></script>
<script type="text/javascript" src="js/cuf_run.js"></script>

<script>
var counter = 1;
var limit = 3;
function addInput(divName){
//     if (counter == limit)  {
//          alert("You have reached the limit of adding " + counter + " inputs");
//     }
//     else {
          var newdiv = document.createElement('div');
          newdiv.innerHTML = "Observed Phenomena-Property ("+ (counter + 1) +"): <input type='text' name='myInputs[]' value='' /> e.g. Wind-Direction";
          document.getElementById(divName).appendChild(newdiv);
          counter++;
//     }
}
</script>
</head>
<body onload="javascript:hidediv('hideShow'); hidediv('rain')">
<div class="main">
<div class="header" title="Philips bubble mood sensing dress">
<div class="header_resize">
<div class="logo">
<h1><a href="#">inContext<span>Sensing</span> <small>Augment your sensor
data</small></a></h1>
</div>
<div class="clr"></div>
<div class="htext">
<h2>Linked Sensor Data</h2>
<p>Augment Pachube sensor data with additional contextual information
from the Web of Data.</p>
</div>
<div class="clr"></div>
<div class="menu_nav">
<ul>
	<li class="active"><a href="index.html">Home</a></li>
	<li><a href="snippetGen.php">Snippet Generator</a></li>
	<li><a href="about.html">About Us</a></li>
	<li><a href="blog.html">Blog</a></li>
	<li><a href="contact.html">Contact Us</a></li>
</ul>

</div>
<div class="clr"></div>
</div>
</div>
<div class="content">
<div class="content_resize">
<div class="mainbar">
<div class="article" style="color: black;">

<div class="clr"></div>
<form method="post">*required fields<br />
<br />

Position where you will store the generated RDF* : <input type="text"
	name="filename" value="" /><br />
<br />
<br />
<br />

Sensor Model* : <input type="text" name="sensor_model" value="" /><br />
<br />
Sensor Instance: <input type="text" name="sensor_instance" value="" /><br />
<br />
Sensor Manual: <input type="text" name="datasheet" value="" /><br />
<br />

<div style="border-style: dotted;">
<div id="dynamicInput">Observed Phenomena-Property (1): <input
	type="text" name="myInputs[]" value="" /> e.g. Wind-Direction</div>
<input type="button" value="Add more"
	onclick="addInput('dynamicInput');" /></div>
<br />
<br />

<input type="submit" name="generate" value="Generate" /></form>
<?php
require_once ( 'lib/semsol-arc2/ARC2.php' );



if(isset($_POST['generate'])) {
	$datasheet = $_POST['datasheet'];
	$filename = $_POST['filename'];
	$sensor_model = $_POST['sensor_model'];
	if (empty($filename) or empty($sensor_model)){
		echo "Error: filename and sensor model are both required.";
	}else{
		$base = $filename.'#';
		$modeluri = $base.ucfirst($sensor_model);


		$data = '<?xml version="1.0"?>
<rdf:RDF xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#" 
xmlns:dul="http://www.loa-cnr.it/ontologies/DUL.owl#" 
xmlns:ssn="http://purl.oclc.org/NET/ssnx/ssn#" 
xmlns:xsd="http://www.w3.org/2001/XMLSchema#" 
xmlns:owl="http://www.w3.org/2002/07/owl#" 
xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
	<owl:Ontology rdf:about="'.$filename.'" />';

		if ($datasheet){
			$data .= '<owl:Class rdf:about="'.$modeluri.'_Datasheet">
		<rdfs:subClassOf rdf:resource="http://purl.oclc.org/NET/ssnx/ssn#SensorDataSheet" />
		<rdfs:subClassOf>
			<owl:Restriction>
				<owl:onProperty rdf:resource="http://www.loa-cnr.it/ontologies/DUL.owl#hasPart" />
				<ssn:hasValue rdf:datatype="http://www.w3.org/2001/XMLSchema#string">'.$datasheet.'</ssn:hasValue>
			</owl:Restriction>
		</rdfs:subClassOf>
	</owl:Class>';
		};

		$sensing_device = '<owl:Class rdf:about="'.$modeluri.'">
		<rdfs:subClassOf rdf:resource="http://purl.oclc.org/NET/ssnx/ssn#SensingDevice" />';



		$myInputs = $_POST["myInputs"];
		foreach ($myInputs as $eachInput) {
			$splitarr = split("-", $eachInput);
			if (sizeof($splitarr) == 2){
				$str = trim($str[1]);
				$sensing_device .= '<rdfs:subClassOf>
			<owl:Restriction>
				<owl:onProperty rdf:resource="http://purl.oclc.org/NET/ssnx/ssn#observes" />
				<owl:someValuesFrom rdf:resource=":'.$str.'" />
			</owl:Restriction>
		</rdfs:subClassOf>';
			}
		}
		$sensing_device .= '</owl:Class>';
		$data .= $sensing_device;

		$data .= "</rdf:RDF>";
		$parser = ARC2::getRDFXMLParser();
		$rdfxml_doc = $parser->parse($data);

		$data_4html = str_ireplace("<", "&lt", $data);
		$data_4html = str_ireplace(">", "&gt", $data_4html);
		echo "<pre><code>".$data_4html."</code></pre><br /><br />";


	}

}else{	?> <?php } ?></div>
</div>
</div>
</div>
</div>
</body>
</html>

