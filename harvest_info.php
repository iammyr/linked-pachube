<?php

//echo "<p>HOLA output text from harvest_info.php </p>";


require_once 'Harvester.php';
$obj = new Harvester();


//TEST PURPOSE: check through Sindice
$sindice = true;

if (!isset($endpoints)){
	$endpoints = $obj->initEndpoint_Cat_List($sindice);
}
//foreach ($endpoints as $key => $urilist){
//	echo "$key -> ";
//	foreach ($urilist as $uri){
//				echo "$uri,";
//	}
//	echo "<br />";
//}


if (isset($_POST['name']) && isset($_POST['location'])){



	//then decode them
	$space = $_POST['space'];
	$time = $_POST['time'];
	$thing = $_POST['concept'];
	$confidence = $_POST['confidence'];

	$concept = rtrim(urldecode($_POST['name']));
	$_POST['location'] = rtrim(urldecode($_POST['location']));
	$startdate = $_POST['start'];
	$enddate = $_POST['end'];
//	echo"initial start=$startdate initial end = $enddate";



	if (strpos($_POST['location'], ":") !== false){
		$locations = split(":", $_POST['location']);
	}else if (strpos($_POST['location'], "-") !== false){
		$locations = split("-", $_POST['location']);
	}else if (strpos($_POST['location'], ",") !== false){
		$locations = split(",", $_POST['location']);
	}else if (strpos($_POST['location'], ";") !== false){
		$locations = split(";", $_POST['location']);
	}else if(strpos($_POST['location'], "/") !== false){
		$locations = split("/", $_POST['location']);
	}else if (strpos($_POST['location'], "\\") !== false){
		$locations = split("\\", $_POST['location']);
	}else if (strpos($_POST['location'], "&") !== false){
		$locations = split("&", $_POST['location']);
	}else if (strpos($_POST['location'], "and") !== false){
		$locations = split("and", $_POST['location']);
	}else{
		$locations = array($_POST['location']);
	}
	//	echo "<p>HOLA output text from harvest_info.php post received</p>";
	$name = "$concept";
	foreach ($locations as $loc){
		$name .= "_$loc";
	}

	$name = sanitize(str_ireplace(" ", "", $name), true, true);
	$html_content = '';
	$concept_uri = "concept URI"; //should be taken from links found in DBpedia by Silk
	$ds = "datasource name";
	$ds_content = "datasource content";


	if (isset($_POST['cross']) && $_POST['cross']){
		$configFileName=$name.'_cross.xml';
		$outputFileName=$name.'_cross.nt';
		$ret_arr = collectLinksPerCategory($startdate, $enddate, $sindice, $confidence, $space, $time, $thing, $locations, $concept, $endpoints['crossdomain'], $configFileName, $outputFileName, $obj);

		$html_content .= '<ul>';
		$start = 'http://';
		foreach ($endpoints['crossdomain'] as $endp){
			$endpul = '';
			foreach ($ret_arr as $res){
				$pos = strpos($res['link'], 'www4.wiwiss.fu-berlin.de');
				if (strpos($res['link'], $endp) !== false){
					if ($pos === false){
						$endpul .= '<br />    <a href = "'.$res['link'].'" target = "_blank">'.$res['title'].'</a> - Last updated:'.$res['updated'];
					}

				}
			}
			//			echo "endpul=".htmlspecialchars($endpul)." <br />";
			if ($endpul){
				$html_content .= '<li><strong>Datasource: </strong>'.$endp.$endpul.'</li>';
			}
		}
		$html_content .= '</ul>';
		//		$html_content .= '<strong>'.$res.'</strong>:<br />'.$ds_content.'<br /><a href="'.$concept_uri.'" target="_blank">[Read more]</a><br />
		//		<strong>additional info from</strong>';'
	}

	if (isset($_POST['media']) && $_POST['media']){
		$configFileName=$name.'_media.xml';
		$outputFileName=$name.'_media.nt';
		$ret_arr = collectLinksPerCategory($startdate, $enddate, $sindice, $confidence, $space, $time, $thing, $locations, $concept, $endpoints['media'], $configFileName, $outputFileName, $obj);

		$html_content .= '<ul>';
		$start = 'http://';
		foreach ($endpoints['media'] as $endp){
			//			echo "endp $endp <br />";
			$endpul = '';
			foreach ($ret_arr as $res){
				$pos = strpos($res['link'], 'www4.wiwiss.fu-berlin.de');
				if (strpos($res['link'], $endp) !== false){
					if ($pos === false){
						$endpul .= '<br />    <a href = "'.$res['link'].'" target = "_blank">'.$res['title'].'</a> - Last updated:'.$res['updated'];
					}

				}
			}
			//			echo "endpul=".htmlspecialchars($endpul)." <br />";
			if ($endpul){
				$html_content .= '<li><strong>Datasource: </strong>'.$endp.$endpul.'</li>';
			}
		}
		$html_content .= '</ul>';
		//		$html_content .= '<ul><li>Media:<br />
		//		<strong>'.$ds.'</strong>:<br />'.$ds_content.'<br /><a href="'.$concept_uri.'" target="_blank">-Read more</a>
		//		</li>';
		//		$html_content .= '</ul>';
	}
	if (isset($_POST['pub']) && $_POST['pub']){
		$configFileName=$name.'_pub.xml';
		$outputFileName=$name.'_pub.nt';
		$ret_arr = collectLinksPerCategory($startdate, $enddate, $sindice, $confidence, $space, $time, $thing, $locations, $concept, $endpoints['publication'], $configFileName, $outputFileName, $obj);
		$html_content .= '<ul>';
		$start = 'http://';
		foreach ($endpoints['publication'] as $endp){
			$endpul = '';
			foreach ($ret_arr as $res){
				$pos = strpos($res['link'], 'www4.wiwiss.fu-berlin.de');
				if (strpos($res['link'], $endp) !== false){
					if ($pos === false ||
					(strpos($res['link'], 'dblp') !== false
					|| strpos($res['link'], 'cordis') !== false
					|| strpos($res['link'], 'gutendata') !== false
					)
					){
						$endpul .= '<br />    <a href = "'.$res['link'].'" target = "_blank">'.$res['title'].'</a> - Last updated:'.$res['updated'];
					}

				}
			}
			//			echo "endpul=".htmlspecialchars($endpul)." <br />";
			if ($endpul){
				$html_content .= '<li><strong>Datasource: </strong>'.$endp.$endpul.'</li>';
			}
		}
		$html_content .= '</ul>';
		//		$html_content .= '<ul><li>Publication:<br />
		//		<strong>'.$ds.'</strong>:<br />'.$ds_content.'<br /><a href="'.$concept_uri.'" target="_blank">[Read more]</a></li>';
		//		$html_content .= '</ul>';
	}
	if (isset($_POST['user']) && $_POST['user']){
		$configFileName=$name.'_user.xml';
		$outputFileName=$name.'_user.nt';
		$ret_arr = collectLinksPerCategory($startdate, $enddate, $sindice, $confidence, $space, $time, $thing, $locations, $concept, $endpoints['usergeneratedcontent'], $configFileName, $outputFileName, $obj);

		$html_content .= '<ul>';
		$start = 'http://';
		foreach ($endpoints['usergeneratedcontent'] as $endp){
			$endpul = '';
			foreach ($ret_arr as $res){
				$pos = strpos($res['link'], 'www4.wiwiss.fu-berlin.de');
				if (strpos($res['link'], $endp) !== false){
					if ($pos === false ||
					(strpos($res['link'], 'flickr') !== false)
					){
						$endpul .= '<br />    <a href = "'.$res['link'].'" target = "_blank">'.$res['title'].'</a> - Last updated:'.$res['updated'];
					}

				}
			}
			//			echo "endpul=".htmlspecialchars($endpul)." <br />";
			if ($endpul){
				$html_content .= '<li><strong>Datasource: </strong>'.$endp.$endpul.'</li>';
			}
		}
		$html_content .= '</ul>';
		//		$html_content .= '<ul><li>User-generated content:<br />
		//		<strong>'.$ds.'</strong>:<br />'.$ds_content.'<br /><a href="'.$concept_uri.'" target="_blank">[Read more]</a></li>';
		//		$html_content .= '</ul>';
	}
	if (isset($_POST['science']) && $_POST['science']){
		$configFileName=$name.'_science.xml';
		$outputFileName=$name.'_science.nt';
		$ret_arr = collectLinksPerCategory($startdate, $enddate, $sindice, $confidence, $space, $time, $thing, $locations, $concept, $endpoints['lifescience'], $configFileName, $outputFileName, $obj);
		//		$html_content .= $ret_arr['total'].' Results Found.<ul>';
		$html_content .= '<ul>';
		$start = 'http://';
		foreach ($endpoints['lifescience'] as $endp){
			$endpul = '';
			foreach ($ret_arr as $res){
				$pos = strpos($res['link'], 'www4.wiwiss.fu-berlin.de');
				if (strpos($res['link'], $endp) !== false){
					if ($pos === false ||
					(strpos($res['link'], 'factbook') === false
					&& strpos($res['link'], 'flickr') === false
					&& strpos($res['link'], 'eurostat') === FALSE
					&& strpos($res['link'], 'eures') === false
					&& strpos($res['link'], 'dblp') === false
					&& strpos($res['link'], 'cordis') === false
					&& strpos($res['link'], 'gutendata') === false
					)
					){
						$endpul .= '<br />    <a href = "'.$res['link'].'" target = "_blank">'.$res['title'].'</a> - Last updated:'.$res['updated'];
					}

				}
			}
			//			echo "endpul=".htmlspecialchars($endpul)." <br />";
			if ($endpul){
				$html_content .= '<li><strong>Datasource: </strong>'.$endp.$endpul.'</li>';
			}
		}
		$html_content .= '</ul>';
		//		$html_content .= '<ul><li>Life-science:<br />
		//		<strong>'.$ds.'</strong>:<br />'.$ds_content.'<br /><a href="'.$concept_uri.'" target="_blank">[Read more]</a></li>';
		//		$html_content .= '</ul>';
	}




	if ((isset($_POST['gov']) && $_POST['gov']) || (isset($_POST['geo']) && $_POST['geo'])){

		if (isset($_POST['geo']) && $_POST['geo']){

			$configFileName=$name.'_geo.xml';
			$outputFileName=$name.'_geo.nt';
			$ret_arr = collectLinksPerCategory($startdate, $enddate, $sindice, $confidence, $space, $time, $thing, $locations, $concept, $endpoints['geography'], $configFileName, $outputFileName, $obj);
			$html_content .= '<ul>';
			$start = 'http://';
			foreach ($endpoints['geography'] as $endp){
				$endpul = '';
				foreach ($ret_arr as $res){
					$pos = strpos($res['link'], 'www4.wiwiss.fu-berlin.de');
					if (strpos($res['link'], $endp) !== false){
						if ($pos === false ||
						(strpos($res['link'], 'factbook') !== false)
						){
							$endpul .= '<br />    <a href = "'.$res['link'].'" target = "_blank">'.$res['title'].'</a> - Last updated:'.$res['updated'];
						}
							
					}
				}
				//			echo "endpul=".htmlspecialchars($endpul)." <br />";
				if ($endpul){
					$html_content .= '<li><strong>Datasource: </strong>'.$endp.$endpul.'</li>';
				}
			}
			$html_content .= '</ul>';
			//			$html_content .= '<ul><li>Geography:<br />
			//		<strong>'.$ds.'</strong>:<br />'.$ds_content.'<br /><a href="'.$concept_uri.'" target="_blank">[Read more]</a></li>';
			//			$html_content .= '</ul>';
		}
		if (isset($_POST['gov']) && $_POST['gov']){
			$configFileName=$name.'_gov.xml';
			$outputFileName=$name.'_gov.nt';
			$ret_arr = collectLinksPerCategory($startdate, $enddate, $sindice, $confidence, $space, $time, $thing, $locations, $concept, $endpoints['government'], $configFileName, $outputFileName, $obj);

			$html_content .= '<ul>';
			$start = 'http://';
			foreach ($endpoints['government'] as $endp){
				$endpul = '';
				foreach ($ret_arr as $res){
					$pos = strpos($res['link'], 'www4.wiwiss.fu-berlin.de');
					if (strpos($res['link'], $endp) !== false){
						if ($pos === false ||
						(strpos($res['link'], 'eurostat') !== FALSE
						|| strpos($res['link'], 'eures') !== false)
						){
							$endpul .= '<br />    <a href = "'.$res['link'].'" target = "_blank">'.$res['title'].'</a> - Last updated:'.$res['updated'];
						}
							
					}
				}
				//			echo "endpul=".htmlspecialchars($endpul)." <br />";
				if ($endpul){
					$html_content .= '<li><strong>Datasource: </strong>'.$endp.$endpul.'</li>';
				}
			}
			$html_content .= '</ul>';
			//			$html_content .= '<ul><li>Government:<br />
			//		<strong>'.$ds.'</strong>:<br />'.$ds_content.'<br /><a href="'.$concept_uri.'" target="_blank">[Read more]</a></li>';
			//			$html_content .= '</ul>';
		}
	}
	echo $html_content;

}else{
	echo "<p>No result: Concept name and/or location not set.</p>";
}

/**
 * Function: sanitize
 * Returns a sanitized string, typically for URLs.
 *
 * Parameters:
 *     $string - The string to sanitize.
 *     $force_lowercase - Force the string to lowercase?
 *     $anal - If set to *true*, will remove all non-alphanumeric characters.
 */
function sanitize($string, $force_lowercase = true, $anal = false) {
	$strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
                   "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
                   "—", "–", ",", "<", ".", ">", "/", "?");
	$clean = trim(str_replace($strip, "", strip_tags($string)));
	$clean = preg_replace('/\s+/', "-", $clean);
	$clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
	return ($force_lowercase) ?
	(function_exists('mb_strtolower')) ?
	mb_strtolower($clean, 'UTF-8') :
	strtolower($clean) :
	$clean;
}

function getSindiceResults($data, $endpoints, $confidence){
	$return_arr = array();

	//	echo "total results = ".$data['totalResults'].'<br />';
	$return_arr['total'] = $data['totalResults'];
	$ind = 0;
	$entries = $data['entries'];
	if ($confidence>=1){
		$confidence = 0.9;
	}
	$tosubtract = ($confidence*100*sizeof($entries))/100;	
	for($ind=0; $ind<(sizeof($entries)-$tosubtract); $ind++){
		$entry = $entries[$ind];
		//		echo "into entry ".$entry['link'].'<br />';
		$titles = $entry['title'];
		$title = '';
		for ($tind=0; $tind<sizeof($titles)&&!$title ;$tind++){
			$value = str_ireplace("List of all instances:", "", $titles[$tind]['value']);
			$title .= $value." ";

		}
		//		$domain = '';
		//		foreach ($endpoints as $endp){
		//			if (!$domain){
		//				echo "comparing ".$entry['link']." with $endp<br />";
		//				if (strpos($entry['link'], $key) !== false){
		//					$domain = $endp;
		//				}
		//			}
		//		}
		$single = array ("".$ind++."" => array('link' => $entry['link'], 'title' => $title,
			'updated' => $entry['updated']));
		$return_arr = array_merge($return_arr, $single);

		//		echo "****".print_r($return_arr);

	}

	return $return_arr;
}


function collectLinksPerCategory($startdate, $enddate, $sindice, $confidence, $space, $time, $thing, $locations, $concept, $endpoints, $configFileName, $outputFileName, $obj){
	$return_arr = array();
	if ($sindice){
		$query = 'http://api.sindice.com/v3/search?q=';
		//		(dog%20AND%20puppy)%20OR%20(cat%20AND%20kitten)&nq=&fq=
		//domain:(dbpedia.org%20OR%20rkbexplorer.com)';
		//		&interface=guru ?not sure
	}else{
		$restriction = '
	?a rdf:type ?t .
	?a ?b ?c .
{';}
		if (isset($space) && $space){
			if ($sindice){
				if (sizeof($locations) > 0){
					$query .= '('.$locations[0];
				}
				for($ind = 1; $ind<sizeof($locations); $ind++){
					//'or' rather than 'and' is preferred since the label used up to now for the location often contains a mix of 
					//useful names e.g. city/country but also unuseful ones like parts/rooms of a building/house etc
					$query .= ' OR '.$locations[$ind]; 
				}
				$query .= ')';
			}else{
				if (sizeof($locations) > 0){
					$restriction .= '{ FILTER regex(?c, "'.$locations[0].'", "i") }';
				}
				for($ind = 1; $ind<sizeof($locations); $ind++){
					$restriction .= 'UNION { FILTER regex(?c, "'.$locations[$ind].'", "i") }';
				}
			}

		}
		if (isset($thing) && $thing){
			if (isset($space) && $space){
				if ($sindice){
					//					$query .= ' OR ';
					$query .= ' AND ';
				}else{
					$restriction .= 'UNION ';
				}
			}
			if ($sindice){
				$query .= '('.$concept.')';
			}else{
				$restriction .= '{ FILTER regex(?c, "'.$concept.'", "i") }';
			}
		}

		if ($sindice){
			$query .= '&nq=&fq=';
			if (sizeof($endpoints) > 0){
				$query .= 'domain:('.$endpoints[0];
			}
			foreach ($endpoints as $endp){
				if ($endp){
					$query .= ' OR '.$endp;
				}
			}
			$query .= ')';

			$query = preg_replace('/\s+/', '%20', $query);
			//			$query = str_ireplace("OR OR", "OR ", $query);
			//			$query = str_ireplace(":", "%3A", $query);

				
			if (isset($time) && $time){
				if ($sindice){
//					echo "start and end before checking the interval=$startdate and $enddate<br />";
					$interval = getSindiceDateInterval($startdate, $enddate);
					if ($interval){
						$query .= '&date:'.$interval;
					}
				}
					
			}

			$query .= '&format=json';
//			echo $query;
			$answer = get_url_contents($query);
//			echo htmlspecialchars($answer);

			$data = json_decode($answer, true);
			//		echo print_r($data, true);
			$return_arr = getSindiceResults($data, $endpoints, $confidence);
			//			echo print_r($return_arr, true);
			//			cut the last results according to the threshold (0.2 -> cut the last 2, etc)
			//			write results in the $outputFileName as: datasource (domain), content, uri
		}else{
			$restriction .= '}';
			$restricion_selected_var = "a";
			//	echo "<br />$restriction<br />";
			if (!file_exists(getcwd().'\\'.$configFileName)){
				//		echo "appendToSilkFile<br />";
				//		print_r($endpoints);
				//		echo"<br /><br />FINE<br />";
				//		foreach ($endpoints as $k => $end){
				//		echo ("$k -> $end<br />");
				//		}
				$obj->appendToSilkFile($confidence, $space, $time, $thing, $endpoints, $restriction, $restricion_selected_var, $locations, $concept, $configFileName, $outputFileName);
			}
			//	if (!file_exists(getcwd().'\\'.$outputFileName)){
			//		$cmd = 'java';
			//		$args = array('DconfigFile' => $configFileName, 'DlogQueries' => true, 'jar' => 'C:\public_html\LinkedPachubeGUI\lib\silk\silk.jar');
			//		require_once 'execApp.php';
			//		$result = runSynchronously($cmd, $args);
			$cmd = "java -DconfigFile=$configFileName -DlogQueries=true -jar C:\public_html\LinkedPachubeGUI\lib\silk\silk.jar";
			//		$result = syscall($cmd);
			//		$result = shell_exec($cmd." > cmd_output.txt ");
			//passthru($cmd, $result);
			//$result = array();
			//	exec($cmd, $result, $status);
			//fclose(STDIN);
			//fclose(STDOUT);
			//fclose(STDERR);
			//$STDIN = fopen('/dev/null', 'r');
			//$STDOUT = fopen('application.log', 'wb');
			//$STDERR = fopen('error.log', 'wb');


			//fclose(STDIN);
			//fclose(STDOUT);
			//fclose(STDERR);


			//		ob_start();
			//	$result =
			system($cmd);
			//$contents = ob_get_flush();
			//file_put_contents('output_cmd.txt',$contents);

			//		$result = my_exec($cmd);
			if (file_exists($outputFileName)){
				if (filesize($outputFileName) === 0){
					echo "No external related resources found. Executed Command: $cmd ";
				}else{
					echo "Executed Command: $cmd <br />Results: <br /><br/>".file_get_contents($outputFileName);
				}
			}else{
				echo "An error occurred: link generation process stopped. Executed Command: $cmd ";
			}
			//	}
		}
		return $return_arr;
}

/**
 * Sindice supports date intervals:
 * today, yesterday, last week, last month, last year
 * so starting from any couple of dates, one of the above alternatives will be returned as a string
 * @param date $sStartDate
 * @param date $sEndDate
 * @return string dateinterval
 */
    function getSindiceDateInterval($sStartDate, $sEndDate){
    	$returndate = "";  
      // Firstly, format the provided dates.  
      // This function works best with YYYY-MM-DD  
      // but other date formats will work thanks  
      // to strtotime().  
//      $sStartDate = gmdate("Y-m-d", strtotime($sStartDate));  
//      $sEndDate = gmdate("Y-m-d", strtotime($sEndDate));  
//      echo "startdate=$sStartDate enddate=$sEndDate<br />";
      
//      echo "current=".date('Y-m-d', strtotime('now'))."<br />";
      if (date('Y-m-d', strtotime($sStartDate)) == date('Y-m-d', strtotime($sEndDate)) && date('Y-m-d', strtotime($sStartDate)) == date('Y-m-d', strtotime('now'))){
      	$returndate = "today";
      }else{
//      	echo "yesterday=".date('Y-m-d', strtotime('-1 day'))."<br />";
      	if (date('Y-m-d', strtotime($sStartDate)) == date('Y-m-d', strtotime('-1 day'))){
      		$returndate = "yesterday";
      	}else{
//      		echo "last week=".date('Y-m-d', strtotime('-1 week'))."<br />";
      		if (date('m', strtotime($sStartDate)) == date('m', strtotime('now'))){
      			$returndate = "last_week";
      		}else{
//      			echo "compare last month=".date('Y-m-d', strtotime('-1 month'))." <= ".date('Y-m-d', strtotime($sStartDate))."?<br />";
      			if (date('m', strtotime($sStartDate)) == date('m', strtotime('-1 month'))){
      				$returndate = "last_month";
      			}else{
//      				echo "compare last year=".date('Y-m-d', strtotime('-1 year'))." <= ".date('Y-m-d', strtotime($sStartDate))."?<br />";
      				if (date('Y', strtotime($sStartDate)) == date('Y', strtotime('-1 year'))){
      					$returndate = "last_year";
      				}
      			}
      		}
      	}
      }
      return $returndate;  
    }  

function get_url_contents($url){
	$crl = curl_init();
	$timeout = 5;
	curl_setopt ($crl, CURLOPT_URL,$url);
	curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
	$ret = curl_exec($crl);
	curl_close($crl);
	return $ret;
}

function getPrintableCode($data){
	$data_4html = str_ireplace("<", "&lt", $data);
	$data_4html = str_ireplace(">", "&gt", $data_4html);
	$data_4html = '<pre><code>'.$data_4html.'</pre></code>';
	return $data_4html;
}
//
//
//	//	$divTemplate = '<div style="background-color: #C0C0FF; border-style: dashed;">
//	//<h2><span>About the Observed Feature <span
//	//	style="text-decoration: underline;" id="foi_name">'.$_POST['name'].'</span></span></h2>';
//	//
//	//	if(strcasecmp($_POST['cross'], "check") == 0){
//	//		$divTemplate .= '<span id="foi_source" style="font-weight: bold;">'.$_POST['source'].'</span>: <span
//	//	id="foi_content">'.$_POST['content'].'</span><br />';
//	//	}
//	//	if(strcasecmp($_POST['media'], "check") == 0 || strcasecmp($_POST['pub'], "check") == 0){
//	//		$divTemplate .= '<strong>additional info from</strong>';
//	//		if(strcasecmp($_POST['media'], "check") == 0){
//	//			$mediaContent = getMedia($_POST['name'], $_POST['uri'], $_POST['content'],
//	//			$_POST['location'], $_POST['when'], $categories_datasets['media']);
//	//			$divTemplate .= '<span id="media"> <br />
//	//- Media:<br />
//	//<span id="media_content">'.$mediaContent.'</span> </span>';
//	//		}
//	//
//	//		if(strcasecmp($_POST['pub'], "check") == 0){
//	//			$pubContent = getPublication($_POST['name'], $_POST['uri'], $_POST['content'], $_POST['location'], $_POST['when']);
//	//			$divTemplate .= '<span id="publication"> <br />
//	//- Publications<br />
//	//<span id="publication_content">'.$pubContent.'</span> </span>';
//	//		}
//	//	}
//	////	$divTemplate .= '</div>';
//	////
//	//}
//	////
//	////echo getPrintableCode($divTemplate);
//	////
//	////function getPrintableCode($data){
//	////		$data_4html = str_ireplace("<", "&lt", $data);
//	////		$data_4html = str_ireplace(">", "&gt", $data_4html);
//	////		$data_4html = '<pre><code>'.$data_4html.'</pre></code>';
//	////		return $data_4html;
//	////	}
//
//}


?>