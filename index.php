<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>inContext Sensing</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/cufon-yui.js"></script>
<script type="text/javascript" src="js/arial.js"></script>
<script type="text/javascript" src="js/cuf_run.js"></script>
<link href="lib/calendar/calendar/calendar.css" rel="stylesheet"
	type="text/css" />
<script language="javascript" src="lib/calendar/calendar/calendar.js"></script>

<?php
require_once 'template.php';
$title = 'inContext Sensing';
printHeader($title);


if (isset($_POST['cat_geo'])){
	$geography = true;
}else{
	$geography = false;
}
if (isset($_POST['cat_cross'])){
	$cross = true;
}else{
	$cross = false;
}
if (isset($_POST['cat_science'])){
	$science = true;
}else{
	$science = false;
}
if (isset($_POST['cat_gov'])){
	$gov = true;
}else{
	$gov = false;
}
if (isset($_POST['cat_pub'])){
	$pub = true;
}else{
	$pub = false;
}
if (isset($_POST['cat_user'])){
	$user = true;
}else{
	$user = false;
}
if (isset($_POST['cat_media'])){
	$media = true;
}else{
	$media = false;
}
if (isset($_POST['criteria_space'])){
	$space = true;
}else{
	$space = false;
}
if (isset($_POST['criteria_time'])){
	$time = true;
}else{
	$time = false;
}
if (isset($_POST['criteria_thing'])){
	$thing = true;
}else{
	$thing = false;
}
if (isset($_POST['confidence'])){
	$confidence = $_POST['confidence'];
}else{
	$confidence = "0.2";
}
?>

<script language=javascript type='text/javascript'> 
function hidediv(divid) { 
if (document.getElementById) { // DOM3 = IE5, NS6 
document.getElementById(divid).style.visibility = 'hidden'; 
} 
else { 
if (document.layers) { // Netscape 4 
document.hideShow.visibility = 'hidden'; 
} 
else { // IE 4 
document.all.hideShow.style.visibility = 'hidden'; 
} 
} 
return true;
}

function showdiv(divid) { 
if (document.getElementById) { // DOM3 = IE5, NS6 
document.getElementById(divid).style.visibility = 'visible'; 
} 
else { 
if (document.layers) { // Netscape 4 
document.hideShow.visibility = 'visible'; 
} 
else { // IE 4 
document.all.hideShow.style.visibility = 'visible'; 
} 
} 
} 


function show_popup(id) {
    if (document.getElementById){ 
        obj = document.getElementById(id); 
//        if (obj.style.display == "none") { 
//            obj.style.display = ""; 
//        }
        myWindow=window.open('','','width=600,height=500,"location=1,status=1,scrollbars=yes')
        myWindow.document.write(obj.innerHTML);
        myWindow.focus() 
    } 
}
//function hide_popup(id){ 
//    if (document.getElementById){ 
//        obj = document.getElementById(id); 
//        if (obj.style.display == ""){ 
//            obj.style.display = "none"; 
//        } 
//    } 
//}



	
function loadInfo(name, location, when)
{
//	myWindow=window.open('','','width=600,height=500');
//	stringa = "<br /> sent: name="+name+
<?php 
//echo "\"&user=$user&confidence=$confidence&space=$space&time=$time&concept=$thing\""; 
?>
//		+"&location="+location+"&when="+when;
//	myWindow.document.write(stringa);
//	myWindow.focus();
	 
var xmlhttp_media, xmlhttp_gov, xmlhttp_geo, xmlhttp_user, xmlhttp_pub, xmlhttp_science, 
xmlhttp_cross, xmlhttp_datastream;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_media=new XMLHttpRequest();
  xmlhttp_gov=new XMLHttpRequest(); 
  xmlhttp_geo=new XMLHttpRequest(); 
  xmlhttp_user=new XMLHttpRequest(); 
  xmlhttp_pub=new XMLHttpRequest(); 
  xmlhttp_science=new XMLHttpRequest(); 
  xmlhttp_cross=new XMLHttpRequest();
  xmlhttp_datastream=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_media=new ActiveXObject("Microsoft.XMLHTTP");
  xmlhttp_gov=new ActiveXObject("Microsoft.XMLHTTP"); 
  xmlhttp_geo=new ActiveXObject("Microsoft.XMLHTTP"); 
  xmlhttp_user=new ActiveXObject("Microsoft.XMLHTTP"); 
  xmlhttp_pub=new ActiveXObject("Microsoft.XMLHTTP"); 
  xmlhttp_science=new ActiveXObject("Microsoft.XMLHTTP"); 
  xmlhttp_cross=new ActiveXObject("Microsoft.XMLHTTP");
  xmlhttp_datastream=new ActiveXObject("Microsoft.XMLHTTP");
  }
stringa = "hola";
style = "background-color: #B8D4F4; border-style: dashed; overflow:auto; height: auto";



media_div = "<div id=\""+name+"_media\"><div style=\""+style+"\"><h2><span><strong>Media</strong> Info About <span style=\"text-decoration: underline;\">"+name+"</span></span></h2>";
gov_div = "<div id=\""+name+"_gov\"><div style=\""+style+"\"><h2><span><strong>Government</strong> Info About <span style=\"text-decoration: underline;\">"+name+"</span></span></h2>";
pub_div = "<div id=\""+name+"_pub\"><div style=\""+style+"\"><h2><span><strong>Publication</strong> Info About <span style=\"text-decoration: underline;\">"+name+"</span></span></h2>";
geo_div = "<div id=\""+name+"_geo\"><div style=\""+style+"\"><h2><span><strong>Geography</strong> Info About <span style=\"text-decoration: underline;\">"+name+"</span></span></h2>";
science_div = "<div id=\""+name+"_science\"><div style=\""+style+"\"><h2><span><strong>Life Science</strong> Info About <span style=\"text-decoration: underline;\">"+name+"</span></span></h2>";
user_div = "<div id=\""+name+"_user\"><div style=\""+style+"\"><h2><span><strong>User-generated</strong> Info About <span style=\"text-decoration: underline;\">"+name+"</span></span></h2>";
cross_div = "<div id=\""+name+"_cross\"><div style=\""+style+"\"><h2><span><strong>Cross-Domain</strong> Info About <span style=\"text-decoration: underline;\">"+name+"</span></span></h2>";
datastream_div = "<div id=\""+name+"_datastream\"><div><h2><span><strong>DataStream Visualization</strong></span></h2>";

xmlhttp_datastream.onreadystatechange=function()
{
	  stringa += "sending media request";
//	readystate values:
//0: request not initialized
//1: server connection established
//2: request received
//3: processing request
//4: request finished and response is ready
if (xmlhttp_datastream.readyState==0)
  {
  document.getElementById("datastream").innerHTML=datastream_div+"request not initialized"+"</div></div>";
  }else if (xmlhttp_datastream.readyState==1)
{
document.getElementById("datastream").innerHTML=datastream_div+"server connection established"+"</div></div>";
}else if (xmlhttp_datastream.readyState==2)
{
document.getElementById("datastream").innerHTML=datastream_div+"request received"+"</div></div>";
}else if (xmlhttp_datastream.readyState==3)
{
	document.getElementById("datastream").innerHTML=datastream_div+"Loading, please wait..."+"</div></div>";
	}else if (xmlhttp_datastream.readyState==4 && xmlhttp_datastream.status==200)
  {
  document.getElementById("datastream").innerHTML=datastream_div+xmlhttp_datastream.responseText+"</div></div><br />";
//  stringa += " got response = "+xmlhttp_datastream.responseText;
  }
}
xmlhttp_datastream.open("POST","datastreamPerFoi.php",true);
xmlhttp_datastream.setRequestHeader("Content-type","application/x-www-form-urlencoded");
mainform = document.forms['main_form'];
feed_id = mainform.elements['feed_id'].value;
start = document.getElementById('date3').value;
end = document.getElementById('date4').value;
xmlhttp_datastream.send("name="+name+"&feedid="+feed_id+"&start="+start+"&end="+end);

<?php if ($media){ ?>
xmlhttp_media.onreadystatechange=function()
  {
	  stringa += "sending media request";
//	readystate values:
//0: request not initialized
//1: server connection established
//2: request received
//3: processing request
//4: request finished and response is ready
if (xmlhttp_media.readyState==0)
    {
    document.getElementById("observedFeature_media").innerHTML=media_div+"request not initialized"+"</div></div>";
    }else if (xmlhttp_media.readyState==1)
{
document.getElementById("observedFeature_media").innerHTML=media_div+"server connection established"+"</div></div>";
}else if (xmlhttp_media.readyState==2)
{
document.getElementById("observedFeature_media").innerHTML=media_div+"request received"+"</div></div>";
}else if (xmlhttp_media.readyState==3)
{
	document.getElementById("observedFeature_media").innerHTML=media_div+"Loading, please wait..."+"</div></div>";
	}else if (xmlhttp_media.readyState==4 && xmlhttp_media.status==200)
    {
    document.getElementById("observedFeature_media").innerHTML=media_div+xmlhttp_media.responseText+"</div></div>";
    }
  }
xmlhttp_media.open("POST","harvest_info.php",true);
xmlhttp_media.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp_media.send("name="+name+<?php echo "\"&media=$media&confidence=$confidence&space=$space&time=$time&concept=$thing\""; ?>+"&location="+location+"&start="+start+"&end="+end+"&when="+when);

<?php } if ($geography){ ?>
xmlhttp_geo.onreadystatechange=function()
{
	  stringa += "sending geo request";
//	readystate values:
//0: request not initialized
//1: server connection established
//2: request received
//3: processing request
//4: request finished and response is ready
mess = "";
if (xmlhttp_geo.readyState==0)
  {
	  mess += "Request not initialized.<br />";
  document.getElementById("observedFeature_geo").innerHTML=geo_div+mess+"</div></div>";
  }else if (xmlhttp_geo.readyState==1)
{
	  mess += "Server connection established.<br />";
document.getElementById("observedFeature_geo").innerHTML=geo_div+mess+"</div></div>";
}else if (xmlhttp_geo.readyState==2)
{
	mess += "Request received. <br />";
document.getElementById("observedFeature_geo").innerHTML=geo_div+mess+"</div></div>";
}else if (xmlhttp_geo.readyState==3)
{
	mess += "Loading, please wait...<br />";
	document.getElementById("observedFeature_geo").innerHTML=geo_div+mess+"</div></div>";
	}else if (xmlhttp_geo.readyState==4 && xmlhttp_geo.status==200)
  {
		if (xmlhttp_geo.responseText){
  document.getElementById("observedFeature_geo").innerHTML=geo_div+xmlhttp_geo.responseText+"</div></div>";
		}
  }
}
xmlhttp_geo.open("POST","harvest_info.php",true);
xmlhttp_geo.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp_geo.send("name="+name+<?php echo "\"&geo=$geography&confidence=$confidence&space=$space&time=$time&concept=$thing\""; ?>+"&location="+location+"&start="+start+"&end="+end+"&when="+when);

<?php } if ($gov){ ?>
xmlhttp_gov.onreadystatechange=function()
{
	  stringa += " sending gov request";
//	readystate values:
//0: request not initialized
//1: server connection established
//2: request received
//3: processing request
//4: request finished and response is ready
if (xmlhttp_gov.readyState==0)
  {
  document.getElementById("observedFeature_gov").innerHTML=gov_div+"request not initialized"+"</div></div>";
  }else if (xmlhttp_gov.readyState==1)
{
document.getElementById("observedFeature_gov").innerHTML=gov_div+"server connection established"+"</div></div>";
}else if (xmlhttp_gov.readyState==2)
{
document.getElementById("observedFeature_gov").innerHTML=gov_div+"request received"+"</div></div>";
}else if (xmlhttp_gov.readyState==3)
{
	document.getElementById("observedFeature_gov").innerHTML=gov_div+"Loading, please wait..."+"</div></div>";
	}else if (xmlhttp_gov.readyState==4 && xmlhttp_gov.status==200)
  {
		if (xmlhttp_gov.responseText){
  document.getElementById("observedFeature_gov").innerHTML=gov_div+xmlhttp_gov.responseText+"</div></div>";
		}
  }
}
xmlhttp_gov.open("POST","harvest_info.php",true);
xmlhttp_gov.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp_gov.send("name="+name+<?php echo "\"&gov=$gov&confidence=$confidence&space=$space&time=$time&concept=$thing\""; ?>+"&location="+location+"&start="+start+"&end="+end+"&when="+when);

<?php } if ($cross){ ?>
xmlhttp_cross.onreadystatechange=function()
{
	  stringa += " sending cross request";
//	readystate values:
//0: request not initialized
//1: server connection established
//2: request received
//3: processing request
//4: request finished and response is ready
if (xmlhttp_cross.readyState==0)
  {
  document.getElementById("observedFeature_cross").innerHTML=cross_div+"request not initialized"+"</div></div>";
  }else if (xmlhttp_cross.readyState==1)
{
document.getElementById("observedFeature_cross").innerHTML=cross_div+"server connection established"+"</div></div>";
}else if (xmlhttp_cross.readyState==2)
{
document.getElementById("observedFeature_cross").innerHTML=cross_div+"request received"+"</div></div>";
}else if (xmlhttp_cross.readyState==3)
{
	document.getElementById("observedFeature_cross").innerHTML=cross_div+"Loading, please wait..."+"</div></div>";
	}else if (xmlhttp_cross.readyState==4 && xmlhttp_cross.status==200)
  {
		if (xmlhttp_cross.responseText){
  document.getElementById("observedFeature_cross").innerHTML=cross_div+xmlhttp_cross.responseText+"</div></div>";
		}
  }
}
xmlhttp_cross.open("POST","harvest_info.php",true);
xmlhttp_cross.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp_cross.send("name="+name+<?php echo "\"&cross=$cross&confidence=$confidence&space=$space&time=$time&concept=$thing\""; ?>+"&location="+location+"&start="+start+"&end="+end+"&when="+when);

<?php } if ($pub){ ?>
xmlhttp_pub.onreadystatechange=function()
{
	  stringa += " sending pub request";
//	readystate values:
//0: request not initialized
//1: server connection established
//2: request received
//3: processing request
//4: request finished and response is ready
if (xmlhttp_pub.readyState==0)
  {
  document.getElementById("observedFeature_pub").innerHTML=pub_div+"request not initialized"+"</div></div>";
  }else if (xmlhttp_pub.readyState==1)
{
document.getElementById("observedFeature_pub").innerHTML=pub_div+"server connection established"+"</div></div>";
}else if (xmlhttp_pub.readyState==2)
{
document.getElementById("observedFeature_pub").innerHTML=pub_div+"request received"+"</div></div>";
}else if (xmlhttp_pub.readyState==3)
{
	document.getElementById("observedFeature_pub").innerHTML=pub_div+"Loading, please wait..."+"</div></div>";
	}else if (xmlhttp_pub.readyState==4 && xmlhttp_pub.status==200)
  {
		if (xmlhttp_pub.responseText){
  document.getElementById("observedFeature_pub").innerHTML=pub_div+xmlhttp_pub.responseText+"</div></div>";
		}
  }
}
xmlhttp_pub.open("POST","harvest_info.php",true);
xmlhttp_pub.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp_pub.send("name="+name+<?php echo "\"&pub=$pub&confidence=$confidence&space=$space&time=$time&concept=$thing\""; ?>+"&location="+location+"&start="+start+"&end="+end+"&when="+when);

<?php } if ($science){ ?>

xmlhttp_science.onreadystatechange=function()
{
	  stringa += " sending science request";
//	readystate values:
//0: request not initialized
//1: server connection established
//2: request received
//3: processing request
//4: request finished and response is ready
if (xmlhttp_science.readyState==0)
  {
  document.getElementById("observedFeature_science").innerHTML=science_div+"request not initialized"+"</div></div>";
  }else if (xmlhttp_science.readyState==1)
{
document.getElementById("observedFeature_science").innerHTML=science_div+"server connection established"+"</div></div>";
}else if (xmlhttp_science.readyState==2)
{
document.getElementById("observedFeature_science").innerHTML=science_div+"request received"+"</div></div>";
}else if (xmlhttp_science.readyState==3)
{
	document.getElementById("observedFeature_science").innerHTML=science_div+"Loading, please wait..."+"</div></div>";
	}else if (xmlhttp_science.readyState==4 && xmlhttp_science.status==200)
  {
		if (xmlhttp_science.responseText){
  document.getElementById("observedFeature_science").innerHTML=science_div+xmlhttp_science.responseText+"</div></div>";
		}
  }
}
xmlhttp_science.open("POST","harvest_info.php",true);
xmlhttp_science.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp_science.send("name="+name+<?php echo "\"&science=$science&confidence=$confidence&space=$space&time=$time&concept=$thing\""; ?>+"&location="+location+"&start="+start+"&end="+end+"&when="+when);

<?php } if ($user){ ?>

xmlhttp_user.onreadystatechange=function()
{
	  stringa += " sending user-generated content request";
//	readystate values:
//0: request not initialized
//1: server connection established
//2: request received
//3: processing request
//4: request finished and response is ready
if (xmlhttp_user.readyState==0)
  {
  document.getElementById("observedFeature_user").innerHTML=user_div+"request not initialized"+"</div></div>";
  }else if (xmlhttp_user.readyState==1)
{
document.getElementById("observedFeature_user").innerHTML=user_div+"server connection established"+"</div></div>";
}else if (xmlhttp_user.readyState==2)
{
document.getElementById("observedFeature_user").innerHTML=user_div+"request received"+"</div></div>";
}else if (xmlhttp_user.readyState==3)
{
	document.getElementById("observedFeature_user").innerHTML=user_div+"Loading, please wait..."+"</div></div>";
	}else if (xmlhttp_user.readyState==4 && xmlhttp_user.status==200)
  {
		if (xmlhttp_user.responseText){
  document.getElementById("observedFeature_user").innerHTML=user_div+xmlhttp_user.responseText+"</div></div>";
		}
  }
}
xmlhttp_user.open("POST","harvest_info.php",true);
xmlhttp_user.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp_user.send("name="+name+<?php echo "\"&user=$user&confidence=$confidence&space=$space&time=$time&concept=$thing\""; ?>+"&location="+location+"&start="+start+"&end="+end+"&when="+when);
<?php } ?>
//myWindow=window.open('','','width=600,height=500');
//stringa += "<br /> sent: name="+name+
<?php 
//echo "\"&user=$user&confidence=$confidence&space=$space&time=$time&concept=$thing\""; 
?>
//	+"&location="+location+"&when="+when+"readyState="+xmlhttp_media.readyState+"; status="+xmlhttp_media.status;
//myWindow.document.write(stringa);
//myWindow.focus(); 

return false; //this is to not execute the href
}

function showObservedFeature(name, uri, source, content){
	if (document.getElementById){ 
        obj = document.getElementById('observedFeature'); 
        if (obj.style.display == "none") { 
            obj.style.display = ""; 
        }
        obj.getElementById('foi_name').innerHTML = name;
        obj.getElementById('foi_uri').innerHTML = uri;
        obj.getElementById('foi_source').innerHTML = source;
        obj.getElementById('foi_content').innerHTML = content;
    } 
}

</script>
</head>
<body onload="javascript:hidediv('hideShow');">
<!--<div class="main">-->
<!--<div class="header" title="Philips bubble mood sensing dress">-->
<!--<div class="header_resize">-->
<!--<div class="logo">-->
<!--<h1><a href="#">inContext<span>Sensing</span> <small>Augment your sensor-->
<!--data</small></a></h1>-->
<!--</div>-->
<!--<div class="clr"></div>-->
<!--<div class="htext">-->
<!--<h2>Linked Sensor Data</h2>-->
<!--<p>Augment Pachube sensor data with additional contextual information-->
<!--from the Web of Data.</p>-->
<!--</div>-->
<!--<div class="clr"></div>-->
<!--<div class="menu_nav">-->
<!--<ul id="coolMenu">-->
<!--	<li class="active"><a href="index.html">Home</a></li>-->
<!--	<li><a href="rdf4sensors.php">RDF4Sensors</a>-->
<!--	<ul class="noJS">-->
<!--					<li><a href="apiDocumentation.php">REST API</a></li>-->
<!--				</ul>-->
<!--	</li>-->
<!--	<li><a href="about.php">About Us</a></li>-->
<!--</ul>-->
<!---->
<!--</div>-->
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
<div class = "menu_nav">

<ul id="coolMenu">
	<li class="active"><a href="index.php">Home</a></li>
	<li><a href="rdf4sensors.php">RDF4Sensors</a>
		<ul class="noJS">
					<li><a href="apiDocumentation.php">REST API</a></li>
				</ul>
	</li>
	<li><a href="about.php">About Us</a></li>
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
<?php
//TEST PURPOSE
//foreach ($_POST as $key => $inp){
//echo "$key = $inp<br />";
//}
?>




<form method="post" action="index.php" name="main_form">
<p>Sensor Data from Pachube View ID: <input type="text" name="feed_id"
	value="<?php if(isset($_POST['submit'])) { echo $_POST['feed_id'];}else{echo "e.g. 17475";} ?>" /><br />
which have been collected
<div style="float: left;">
<div style="float: left; padding-right: 3px; line-height: 18px;">from:</div>
<div style="float: left;"><?php
require_once 'lib/calendar/calendar/classes/tc_calendar.php';
$thisweek = date('W');
$thisyear = date('Y');

$dayTimes = getDaysInWeek($thisweek, $thisyear);
//----------------------------------------

$date1 = date('Y-m-d', $dayTimes[0]);
$date2 = date('Y-m-d', $dayTimes[(sizeof($dayTimes)-1)]);

function getDaysInWeek ($weekNumber, $year, $dayStart = 1) {
	// Count from '0104' because January 4th is always in week 1
	// (according to ISO 8601).
	$time = strtotime($year . '0104 +' . ($weekNumber - 1).' weeks');
	// Get the time of the first day of the week
	$dayTime = strtotime('-' . (date('w', $time) - $dayStart) . ' days', $time);
	// Get the times of days 0 -> 6
	$dayTimes = array ();
	for ($i = 0; $i < 7; ++$i) {
		$dayTimes[] = strtotime('+' . $i . ' days', $dayTime);
	}
	// Return timestamps for mon-sun.
	return $dayTimes;
}


$myCalendar = new tc_calendar("date3", true, false);
$myCalendar->setIcon("lib/calendar/calendar/images/iconCalendar.gif");
$myCalendar->setDate(date('d', strtotime(isset($_POST['date3'])?$_POST['date3']:$date1)), date('m', strtotime(isset($_POST['date3'])?$_POST['date3']:$date1)), date('Y', strtotime(isset($_POST['date3'])?$_POST['date3']:$date1)));
$myCalendar->setPath("lib/calendar/calendar/");
$myCalendar->setYearInterval(1970, date('Y', strtotime('now')));
//$myCalendar->dateAllow('2009-02-20', "", false);
$myCalendar->setAlignment('left', 'bottom');
$myCalendar->setDatePair('date3', 'date4', $date2);
//$myCalendar->setSpecificDate(array("2011-04-01", "2011-04-04", "2011-12-25"), 0, 'year');
$myCalendar->writeScript();
?></div>
</div>
<div style="float: left;">
<div
	style="float: left; padding-left: 3px; padding-right: 3px; line-height: 18px;">to</div>
<div style="float: left;"><?php
$myCalendar = new tc_calendar("date4", true, false);
$myCalendar->setIcon("lib/calendar/calendar/images/iconCalendar.gif");
$myCalendar->setDate(date('d', strtotime(isset($_POST['date4'])?$_POST['date4']:$date2)), date('m', strtotime(isset($_POST['date4'])?$_POST['date4']:$date2)), date('Y', strtotime(isset($_POST['date4'])?$_POST['date4']:$date2)));
$myCalendar->setPath("lib/calendar/calendar/");
$myCalendar->setYearInterval(1970, date('Y', strtotime('now')));
//$myCalendar->dateAllow("", '2009-11-03', false);
$myCalendar->setAlignment('left', 'top');
$myCalendar->setDatePair('date3', 'date4', $date1);
//$myCalendar->setSpecificDate(array("2011-04-01", "2011-04-04", "2011-12-25"), 0, 'year');
$myCalendar->writeScript();
?></div>
</div>
</p>
<?php if ($cross){?> <input type="hidden" name="cat_cross" value="" /> <?php } if ($geography){?>
<input type="hidden" name="cat_geo" value="" /> <?php }if($gov){?> <input
	type="hidden" name="cat_gov" value="" /> <?php }if ($media){?> <input
	type="hidden" name="cat_media" value="" /> <?php }if($pub){?> <input
	type="hidden" name="cat_pub" value="" /> <?php }if ($science){?> <input
	type="hidden" name="cat_science" value="" /> <?php }if ($user){ ?> <input
	type="hidden" name="cat_user" value="" /> <?php } if ($space){?> <input
	type="hidden" name="criteria_space" value="" /> <?php } if ($time){?> <input
	type="hidden" name="criteria_time" value="" /> <?php }if($thing){?> <input
	type="hidden" name="criteria_thing" value="" /> <?php }if ($confidence){?>
<input type="hidden" name="confidence"
	value="<?php echo $confidence; ?>" /> <?php } ?> <br />
<input type="submit" name="submit" value="Submit" /></form>


<a href="javascript:showdiv('hideShow')">Advanced search setting</a>
<div id="hideShow"
	style="height: 120px; overflow: auto; border-style: double; background-color: #95B9F5; color: black;">Select
which kind of data you want to link to the Pachube sensor feed.<br />
<form method="post" action="index.php" name="preferences"><input
	type="checkbox" name="cat_geo"
	<?php if ($geography){echo "checked = \"\"";}
	else{echo "value=\"\"";}?> />Geography<br />
<input type="checkbox" name="cat_gov"
<?php if ($gov){echo "checked = \"\"";}
else{echo "value=\"\"";}?> />Government<br />
<input type="checkbox" name="cat_media"
<?php if ($media){echo "checked = \"\"";}
else{echo "value=\"\"";}?> />Media<br />
<input type="checkbox" name="cat_user"
<?php if ($user){echo "checked = \"\"";}
else{echo "value=\"\"";}?> />User Generated Content<br />
<input type="checkbox" name="cat_pub"
<?php if ($pub){echo "checked = \"\"";}
else{echo "value=\"\"";}?> />Publication<br />
<input type="checkbox" name="cat_science"
<?php if ($science){echo "checked = \"\"";}
else{echo "value=\"\"";}?> />Life Science<br />
<input type="checkbox" name="cat_cross"
<?php if ($cross){echo "checked = \"\"";}
else{echo "value=\"\"";}?> />Cross Domain<br />
Select criteria to consider while linking.<br />
<input type="checkbox" name="criteria_space"
<?php if ($space){echo "checked = \"\"";}
else{echo "value=\"\"";}?> />Space<br />
<input type="checkbox" name="criteria_time"
<?php if ($time){echo "checked = \"\"";}
else{echo "value=\"\"";}?> />Time<br />
<input type="checkbox" name="criteria_thing"
<?php if ($thing){echo "checked = \"\"";}
else{echo "value=\"\"";}?> />Thing<br />
Confidence Level threshold: <input type="text"
	value="<?php if ($confidence){ echo htmlspecialchars($confidence);} else {echo "0.2"; } ?>"
	name="confidence" /> <input type="submit" name="advanced" value="Save"
	onclick="javascript:hidediv('hideShow')" /></form>
</div>



<?php
require_once( 'lib/pachube_functions_modbymyr.php' );
require_once ( 'lib/semsol-arc2/ARC2.php' );



if(isset($_POST['submit'])) {
	$feed_id = $_POST['feed_id'];
	$feed_env = main();



}else{	?> <?php } ?></div>

</div>
<div class="sidebar">
<div class="gadget">
<h2 class="star"><span>What</span></h2>
<div class="clr"></div>
Web Service that, starting from a Pachube aggregation of sensor data,
provides you with detailed and wide description of the whole context of
such data.</div>
<div class="gadget">
<h2 class="star"><span>Why</span></h2>
<div class="clr"></div>
To let you benefit of
<ul>
	<li>additional information regarding the context of sensor data stored
	in Pachube</li>
	<li>sensor interoperability (thanks to the semantic annotation provided
	for sensor observations)</li>
</ul>
</div>
</div>
<div class="clr"></div>
</div>
</div>
<div class="fbg">
<div class="fbg_resize">
<div class="col c1"></div>
<div class="col c3"></div>
<div class="clr"></div>
</div>
</div>
<div class="footer">
<div class="footer_resize">
<p class="lf">&copy; Copyright <a href="http://www.deri.ie">DERI</a>.</p>
<p class="rf">Layout by Rocket <a
	href="http://www.rocketwebsitetemplates.com/">Website Templates</a></p>
<div class="clr"></div>
</div>
</div>
</div>

<?php
//functions



function getPrintableCode($data){
	$data_4html = str_ireplace("<", "&lt", $data);
	$data_4html = str_ireplace(">", "&gt", $data_4html);
	$data_4html = '<pre><code>'.$data_4html.'</pre></code>';
	return $data_4html;
}

function main(){
	global $feed_env, $feed_id;

	$api_key = "lYPztOU3rzlqgGEBp2FMEpka0BubfncDmb_IB7eyxg8";
	$version = "v2";
	$pachube = new Pachube($api_key, $version);
	$start=$_POST['date3'];
	//		$end="2011-08-29T14:01:46Z";
	$end=$_POST['date4'];
	if (strtotime($end) > strtotime('now')){
		$end = strtotime('now');
	}
	$interval ="0";
	$feed_env = $pachube->environment_historical($feed_id, $start, $end, $interval);
	
		//	printEnvironment($feed_env);
		$context = initResources($feed_env, $pachube, $start, $end, $interval);

		//GET THE RDF-IZATION
		$data = $context->getRDFXMLHeader();
		$data .= $context->getContextObservationsTriples();
		$data.= $context->getRDFXMLFooter();
		$parser = ARC2::getRDFXMLParser();
		$rdfxml_doc = $parser->parse($data);
		$data_4html = getPrintableCode($data);

		if(isset($feed_env['title'])){?>
<h2><span><?php echo $feed_env['title']; ?></span></h2>
		<?php } ?>
<div id="my_popup"
	style="display: none; border: 1px dotted gray; padding: .3em; background-color: white; position: absolute; width: 200px; height: 200px; left: 100px; top: 100px">
<p><?php echo $data_4html; ?></p>
</div>
<a href="javascript:show_popup('my_popup')"><img
	src="images/triplify.jpg" alt="Get RDF representation" /></a>
		<?php if (isset($feed_env['status'])){?>
<p>Status = <a target="_blank"
	href="<?php echo $context->getStatusUri(); ?>"><?php echo $feed_env['status']?></a><br />
	<?php }?> Sensor Data collected from <?php echo $_POST['date3']." to ".$_POST['date4']; ?><br />
Observed features: <?php
//@todo:list FOIs with links to their definitions
$fois = $context->getFOIs();
$str = "<form>";
foreach ($fois as $foi){
	//		if ($str){
	//			$str .= ', ';
	//		}
	//		if(strcasecmp($_POST['cat_media'], "check") == 0 || strcasecmp($_POST['cat_pub'], "check") == 0
	//		|| strcasecmp($_POST['cat_geo'], "check") == 0 || strcasecmp($_POST['cat_gov'], "check") == 0 || strcasecmp($_POST['cat_user'], "check") == 0
	//		){
	//			if(strcasecmp($_POST['cat_media'], "check") == 0){
	//				//@todo: retrieve media info about $context in $media_content
	//
	//			}
	//			if(strcasecmp($_POST['cat_pub'], "check") == 0){
	//				//@todo: retrieve publication info about $context in $pub_content
	//			}
	//			$str .= '<a>'.$foi['name'].'</a>';
	//		}else{
	//			$str .= $foi['name'];
	//		}
	$loc = $feed_env['location'];
	//		if ($foi['uri']){
	$str .= '<input type="button" value="'.$foi.'" onclick="javascript:loadInfo(\''.$foi.'\', \''.$loc['name'].'\', \''.$feed_env['updated'].'\');" />';
	//			$str .= '<a onclick="javascript:loadInfo(\''.$foi['name']."', '".$foi['uri']."', '".$foi['source'].'\'
	//			, '.$foi['content'].'\', \''.$loc['name'].'\', \''.$feed_env['updated'].'\');">'.$foi['name'].'</a>';
	//		}
}
$str .= '</form>';
echo $str;
?><br />

<?php if (isset($feed_env['location'])){?> Location = <?php echo $loc['name']; ?><br />
<?php }if (isset($feed_env['updated'])){?> Time = <?php echo $feed_env['updated'];?><br />
<?php } ?></p>

<div id="datastream"></div>
<div id="observedFeature_media"></div>
<div id="observedFeature_pub"></div>

<div id="observedFeature_user"></div>

<div id="observedFeature_cross"></div>

<div id="observedFeature_gov"></div>

<div id="observedFeature_science"></div>

<div id="observedFeature_geo"></div>

<?php
return $feed_env;
	}
	function initResources($feed_env, $pachube, $start, $end, $interval){
		//	echo "before collecting the id";
		require_once ('resources/SensingContext.php');
		$context_id = $feed_env['id'];
		//	echo "after collecting the id ".$feed_env['id'];
		$context = new SensingContext($context_id);
		//	echo "after instantiating the SensingContext";

		if (isset($feed_env['description'])){
			$description = $feed_env['description'];
			$context->setDescription($description);
			//	echo "Description = ".$description ."<br />";
		}
		//	echo $context->getTriples();

		if (isset($feed_env['status'])){
			$status = $feed_env['status'];
			$context->setStatus($status);
			//	echo "Status = ".$status ."<br />";
		}
		if (isset($feed_env['title'])){
			$title = $feed_env['title'];
			$context->setTitle($title);
			//	echo "Title = ". $title ."<br />";
		}
		if (isset($feed_env['website'])){
			$website = $feed_env['website'];
			$context->setWebsite($website);
		}
		//	echo "Website = ".$website."<br />";
		if (isset($feed_env['updated'])){
			$lastmod = $feed_env['updated'];
			$context->setLastModified($lastmod);
			//	echo "Updated = ".$lastmod."<br />";
		}
		if (isset($feed_env['version'])){
			$version = $feed_env['version'];
			$context->setVersion($version);
			//	echo "Version = ".$version."<br />";
		}
		if (isset($feed_env['creator'])){
			$creator = $feed_env['creator'];
			$context->setCreator($creator);
			//	echo "Creator= ".$creator."<br />";
		}
		if (isset($feed_env['tags'])){
			$tags = $feed_env['tags'];
			$context->setTags($tags);
			//	echo "Tags = ".$tags."<br />";
		}
		if (isset($feed_env['location'])){
			$feed_loc = $feed_env['location'];

			if (isset($feed_loc['name'])){
				$locname = $feed_loc['name'];
				//			echo "location-name = ".$locname."<br />";
			}
			if (isset($feed_loc['exposure'])){
				$exposure = $feed_loc['exposure'];
				//			echo "location-exposure = ".$exposure."<br />";
			}
			if (isset($feed_loc['disposition'])){
				$disposition = $feed_loc['disposition'];
				//			echo "location-disposition = ".$disposition."<br />";
			}
			if (isset($locname)){
				require_once 'resources/Place.php';
				$place = new Place($locname, $exposure, $disposition);
				$context->setLocation($place->getUri());
			}
			if (isset($feed_loc['ele'])){
				//			echo "location-elevation = ".$feed_loc['ele'] ."<br />";
			}
			if (isset($feed_loc['lat'])){
				//			echo "location-lat = ".$feed_loc['lat'] ."<br />";
			}
			if (isset($feed_loc['lon'])){
				//			echo "location-lon = ".$feed_loc['lon'] ."<br />";
			}
			if (isset($feed_loc['domain'])){
				//			echo "location-domain = ".$feed_loc['domain'] ."<br />";
			}
		}
		if (isset($feed_env['datastreams'])){
			$feed_datastreams = $feed_env['datastreams'];
			$context->setObservations($feed_datastreams, $pachube, $start, $end, $interval);
		}
		//print triples per each resource
		//print triples collected since now for testing purpose
		//	$triples = $context->getContextObservationsTriples();
		//	$parser = ARC2::getRDFJSONSerializer();
		//	$doc = $parser->toRDFJSON($triples);
		//	echo $doc;

		return $context;
	}

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
		echo "location-elevation = ".$feed_loc['ele'] ."<br />";
		echo "location-name = ".$feed_loc['name'] ."<br />";
		echo "location-lat = ".$feed_loc['lat'] ."<br />";
		echo "location-lon = ".$feed_loc['lon'] ."<br />";
		echo "location-exposure = ".$feed_loc['exposure'] ."<br />";
		echo "location-domain = ".$feed_loc['domain'] ."<br />";
		$feed_datastreams = $feed_env['datastreams'];
		echo "# of datastreams = ".count($feed_datastreams)."<br />";
		foreach ( $feed_datastreams as $ds )
		{
			//print_r ($result);
			echo "<h1>datastream ID ".$ds["id"]."</h1>";
			echo "when: ". $ds["at"] ."<br />";
			echo "tags: ";
			foreach($ds["tags"] as $tag){
				echo $tag .", ";
			}
			echo "<br />current_value = ". $ds["current_value"] ."<br />";
			echo "max_value= ". $ds["max_value"] ."<br />";
			echo "min_value= ". $ds["min_value"] ."<br />";


		}
	}

	function screenshot(){
		//	if (isset($checkMedia)){
		//if(strcasecmp($checkMedia, "uncheck") == 0){
		//	echo "<h1>Media FALSE in screenshot</h2>";
		//}}else{
		//	echo "<h1>Media UNSET in screenshot</h2>";
		//}
		?>

<h2><span>FHEM-Weather: Munich, Germany</span></h2>
<a target="_blank" href=""><img src="images/triplify.jpg"
	alt="Get RDF representation" /></a>
<p>Status = <a target="_blank" href="">Live</a><br />
Observed features = <a href="javascript:showdiv('rain')">Rain</a> (<a
	target="_blank"
	href="http://sigma.ontologyportal.org:4010/sigma/WordNet.jsp?synset=111501381&kb=SUMO&flang=SUO-KIF&lang=EnglishLanguage&kb=SUMO">SUMO</a>),
Atmospheric pressure (<a target="_blank" href="">DBPEDIA</a>),
RelativeHumidity (<a href="">SWEET</a>), Temperature(<a target="_blank"
	href="">SWEET</a>) Wind (<a target="_blank" href="">SWEET</a>), <br />
Location = Munich <br />
Time = Fri, 19 Aug 2011 12:46:25 +0000<br />
</p>
<div id="rain">
<div style="background-color: #C0C0FF; border-style: dashed;">
<h2><span>About the Observed Feature <span
	style="text-decoration: underline;">Rain</span></span></h2>
		<?php if(strcasecmp($_POST['cat_cross'], "check") == 0){?> <strong>Sumo-WordNet</strong>:
water falling in drops from vapor condensed in the atmosphere.<br />
hypernym: <a target="_blank"
	href="http://sigma.ontologyportal.org:4010/sigma/WordNet.jsp?synset=111494638&flang=SUO-KIF&lang=EnglishLanguage&kb=SUMO">precipitation</a><br />
meronym <a target="_blank"
	href="http://sigma.ontologyportal.org:4010/sigma/WordNet.jsp?synset=111501649&flang=SUO-KIF&lang=EnglishLanguage&kb=SUMO">raindrop</a><br />
<strong>additional info from</strong> <?php } ?>
<ul>
<?php if(strcasecmp($_POST['cat_media'], "check") == 0){?>
	<li>Media:<br />
	<span style="font-style: italic;">Rain</span> - Official Album (<a
		target="_blank"
		href="http://musicbrainz.org/release/dec00ff6-1b29-4a97-9844-2a6c19532660">MUSICBRAINZ</a>)
	by Jessy Artist (<a target="_blank"
		href="http://musicbrainz.org/artist/ba9f7b30-3fc7-4cb4-af39-5925f33b8f93">MUSICBRAINZ</a>)<br />
	<a target="_blank" href="">[Read more..]</a></li>
	<?php } ?>
	<?php if(strcasecmp($_POST['cat_pub'], "check") == 0){?>
	<li>Publications<br />
	<span style="font-style: italic;">Rain and dust : magnetic records of
	climate, and pollution.</span> ( <!--<a target="_blank" href="http://eprints.rkbexplorer.com/id/lancaster/eprints-27048">ePrints</a>-->
	<a target="_blank" href="http://eprints.lancs.ac.uk/27048/">ePrints</a>
	) <br />
	<a target="_blank" href="">[Read more..]</a></li>
	<?php } ?>
</ul>

</div>
<div style="background-color: #C0FFC0; border-style: dashed;">
<h2><span>About the Location <span style="text-decoration: underline;">Munich</span></span></h2>
<ul>
<?php if(strcasecmp($_POST['cat_geo'], "check") == 0){?>
	<li>Geography:<br />
	<span style="font-style: italic;">M&#252;nchen (<a target="_blank"
		href="http://www.geonames.org/2867714">GEONAMES</a>)</span> - seat of
	a first-order administrative division;<br />
	<strong>State:</strong> Bavaria<br />
	<strong>Country:</strong> Germany;(<a target="_blank"
		href="ttp://www.geonames.org/2921044">GEONAMES</a>)<br />
	<a target="_blank" href="">[Read more..]</a></li>
	<?php } ?>
	<?php if(strcasecmp($_POST['cat_user'], "check") == 0){?>
	<li>User-Generated Content<br />
	<span style="font-style: italic;">Photos taken in M&#252;nchen</span> <img
		src="http://upload.wikimedia.org/wikipedia/en/thumb/5/59/Munchen_collage.jpg/300px-Munchen_collage.jpg" />(<a
		href="http://www4.wiwiss.fu-berlin.de/flickrwrappr/photos/Munich">FLICKRWRAPPR</a>)
	<br />
	<a target="_blank" href="">[Read more..]</a></li>
	<?php } ?>
</ul>
</div>
</div>
	<?php
	}
	?>
</body>
</html>


