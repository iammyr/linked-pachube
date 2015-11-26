<?php
require_once 'lib/pachube_functions_modbymyr.php';
//echo "end = ".$_POST['end'];
if (isset($_POST['name'])){
	$name = $_POST['name'];
	$feed_id = $_POST['feedid'];
	
	$start=$_POST['start'];
	//		$end="2011-08-29T14:01:46Z";
	$end=$_POST['end'];
//echo "end = ".$end;
	if (strtotime($end) > strtotime('now')){
		$end = strtotime('now');
	}
//echo "received name = $name feedid=$feed_id start=$start end=$end<br />";	
$api_key = "lYPztOU3rzlqgGEBp2FMEpka0BubfncDmb_IB7eyxg8";
		$version = "v2";
		$pachube = new Pachube($api_key, $version);
			
$interval ="0";
	$feed_env = $pachube->environment_historical($feed_id, $start, $end, $interval);
	$datastream_id = "";
	$dss = $feed_env['datastreams'];
	for ($ind=0; $ind<sizeof($dss)&&(!$datastream_id); $ind++){
		$ds = $dss[$ind];
		$tags = $ds['tags'];
//		echo "processing ".$ds['id'].'<br />';
		for ($tagind=0; $tagind<sizeof($tags)&&(!$datastream_id) ;$tagind++){
//			echo "comparing ".$tags[$tagind]." with $name<br />";
			if (strpos(strtolower($tags[$tagind]), strtolower($name)) !== false){
				$datastream_id = $ds['id'];
//				echo "new datastreamId found!<br />";
			}
		}
	}
//	echo "datastream id = $datastream_id<br />";
	if ($datastream_id){			 
		echo "showGraph ( $feed_id, $datastream_id, 500, 300, \"#076FBF\", true, true, \"Datastream \".$datastream_id, $name, 6 )";
		echo $pachube->showGraph($feed_id, $datastream_id, 500, 300, "#076FBF", true, true, "Datastream ID ".$datastream_id, $name, 6 );
		
//		echo htmlspecialchars('<script type="text/javascript" src="http://www.google.com/jsapi"></script><script language="JavaScript" src="http://apps.pachube.com/google_viz/viz.js"></script><script language="JavaScript">createViz('.$feed_id.',"'.$datastream_id.'",600,200,"FF0066");</script>');
//echo '<script type="text/javascript" src="http://www.google.com/jsapi"></script><script language="JavaScript" src="http://apps.pachube.com/google_viz/viz.js"></script><script language="JavaScript">createViz('.$feed_id.',"'.$datastream_id.'",600,200,"FF0066");</script>';
	}else{
		echo "No Datastream found for the selected Observed Feature.";
	}
}
?>