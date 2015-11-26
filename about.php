<?php 
require_once 'template.php';
$title = 'inContext Sensing | About Us';
printHeader($title);
?>
</head>
<?php 
$activeMenuItemNum = 3;
$body_onload = '';
$sidebar = '<div class="gadget">
<h2 class="star"><span>What</span></h2>
<div class="clr"></div>
Generate a RDF description for single sensor nodes.<br /> 
The returned description is split into sections according to the 
dependency of the included information, and "where" to store it is suggested. 
This service is available through either the manually fillable form here
or a RESTful API.</div>
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

              <p>
        <span style="font-weight: bold">inContextSensing</span> is a system that shows how
users with no expertise can benefit of a Linked Data representation to make sense of raw sensor data. Our motivations
are that <ol>
<li> these users are becoming the main consumers of sensor data,
but sensors conceptualisation do not consider their point of view; </li>
<li>so far, no application dynamically creates Linked Data for sensors, since the linked datasets are usually predefined.</li>
</ol></p>
<p>
A DEMO paper has been published about this at the <span style="font-style: italic;">International Semantic Web Conference 2011 (ISWC2011)</span>:
<a href = "http://iswc2011.semanticweb.org/fileadmin/iswc/Papers/PostersDemos/iswc11pd_submission_100.pdf" target="_blank">inContext Sensing: LOD augmented sensor data </a><br />
</p>
<br />
        <p>
        <span style="font-weight: bold">RDF4Sensors</span> is a generator of RDF descriptions for sensor metadata and sensor observations. It accepts input sent either manually by filling an online form or
        through a REST API (see <a href = "apiDocumentation.php">REST API SPECIFICATION</a>).<br />
        It's going to be integrated into the inContextSensing application.
        </p><br /><br />
        
<span style="font-weight: bold">Contacts:</span><ul>
<li><a href="http://www.deri.ie/about/team/member/myriam_leggieri" target="_blank">Myriam Leggieri</a>,</li> 
<li><a href="http://www.deri.ie/about/team/member/alexandre_passant" target="_blank">Alexandre Passant</a>,</li> 
<li><a href = "http://www.deri.ie/about/team/member/manfred_hauswirth" target="_blank">Manfred Hauswirth</a>.</li>
</ul>        
        
<?php 
$copyright_uri = 'http://www.deri.ie';
$copyright_name = 'DERI';
printFooter($copyright_uri, $copyright_name);
?>
 
