<html>
<head>
<title>Linked Pachube Feed Handled</title>
</head>
<body>

<?php
require_once( 'lib/pachube_functions_modbymyr.php' );
require_once ( 'lib/semsol-arc2/ARC2.php' );

function printEnvironment($feed_env){
	echo "<h4>Environment</h4>";
	echo "Description = ".$feed_env['description'] ."<br />";
	echo "Status = ".$feed_env['status'] ."<br />";
	echo "Title = ".$feed_env['title'] ."<br />";
	echo "Website = ".$feed_env['website'] ."<br />";
	echo "Updated = ".$feed_env['updated'] ."<br />";
	echo "Version = ".$feed_env['version'] ."<br />";
	echo "Creator= ".$feed_env['creator'] ."<br />";
	echo "Tags = ".$feed_env['tags'] ."<br />";
	$feed_loc = $feed_env['location'];
	echo "location-disposition = ".$feed_loc['disposition'] ."<br />";
	echo "location-ele = ".$feed_loc['ele'] ."<br />";
	echo "location-name = ".$feed_loc['name'] ."<br />";
	echo "location-lat = ".$feed_loc['lat'] ."<br />";
	echo "location-lon = ".$feed_loc['lon'] ."<br />";
	echo "location-exposure = ".$feed_loc['exposure'] ."<br />";
	echo "location-domain = ".$feed_loc['domain'] ."<br />";
	$feed_datastreams = $feed_env['datastreams'];
	echo "# of datastreams = ".count($feed_datastreams)."<br />";
	$count=0;
	foreach ( $feed_datastreams as $ds )
	{
		//print_r ($result);
		echo "datastreams ".++$count."-at = ". $ds["at"] ."<br />";
		echo "datastreams ".$count."-current_value = ". $ds["current_value"] ."<br />";
		echo "datastreams ".$count."-id = ". $ds["id"] ."<br />";
		echo "datastreams ".$count."-max_value= ". $ds["max_value"] ."<br />";
		echo "datastreams ".$count."-min_value= ". $ds["min_value"] ."<br />";
		$dpoints = $ds['datapoints'];
foreach($dpoints as $dp){
		echo "dp-at=". $dp['at'].' value='.$dp['value'].'<br />';
}
	}
}


?>

<?php
if(isset($_POST['submit'])) {
	$feed_id = $_POST['feed_id'];
	//	$api_key = $_POST['api_key'];
	echo("Feed ID: " . $feed_id . "<br />\n");
	//	echo("API Key: " . $api_key . "<br />\n");

	$api_key = "lYPztOU3rzlqgGEBp2FMEpka0BubfncDmb_IB7eyxg8";
	$version = "v2";
	
	$pachube = new Pachube($api_key, $version);
	$datastream_id=1;

	echo "<p>Display a Pachube datastream graph without creating \$environment:<br />";
	echo '<code>$pachube->showGraph ( '. $feed_id. ', ' . $datastream_id .');	 </code><br />';
	$pachube->showGraph ( $feed_id, $datastream_id );
	echo "</p>";

	echo "<p>Display a <i>configured</i> Pachube datastream graph without creating \$environment: <br />";
	echo '<code>$pachube->showGraph ( '.$feed_id.', '.$datastream_id.', 500, 300, "00FF00", true, true, "My configured graph title", "My datastream units", 6 );	 </code><br />';
	$pachube->showGraph ( $feed_id, $datastream_id, 500, 300, "00FF00", true, true, "My configured graph title", "My datastream units", 6 );
	echo "</p>";


	$lat=51.52;
	$lon=-0.08;
	echo "search for feed having lat = $lat and long = $lon<br />";
	$search_result = $pachube->getLatLon("lat=$lat&lon=$lon");
	if (isset($search_result) && !empty($search_result)){
		echo "# of results = ". $search_result[0] ." which are:<br />";
		foreach ( $search_result as $result )
		{
			//print_r ($result);
			echo "lat = ". $result["lat"] ."<br />";
			echo "long = ". $result["lon"] ."<br /><hr />";

		}
	}


	$feed_env = $pachube->environment($feed_id);
	printEnvironment($feed_env);

}else{
	?>
<form method="post">
<p>Feed ID: <input type="text" name="feed_id" /></p>
<!--<p>API Key: <input type="text" name="api_key" /></p>--> <input
	type="submit" name="submit" value="Submit" /></form>
	<?php
}
?>
</body>
</html>
