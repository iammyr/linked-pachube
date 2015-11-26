<?php
require_once 'resources/AbstractResource.php';
class Harvester extends AbstractResource{

	function getMedia($concept, $uri, $content, $locations, $when, $endpoint_uris){
		$media_html = '<a href = "';
		//	$query = getMediaQuery($concept, $locations, $when); //in the future: consider key words from $content as well
		//run on Silk
		$result ="";
		$media_html .= $result .'">'.$concept.'</a><br />';
		return $media_html;
	}

	function getPublication($name, $uri, $content, $locations, $when){
		$pub_html = "";
		//	$query = getPublicationQuery($name, $locations, $when); //in the future: consider key words from $content as well
		return $pub_html;
	}

	function getPrintableCode($data){
		$data_4html = str_ireplace("<", "&lt", $data);
		$data_4html = str_ireplace(">", "&gt", $data_4html);
		$data_4html = '<pre><code>'.$data_4html.'</pre></code>';
		return $data_4html;
	}

	function appendToSilkFile($confidence, $space, $time, $thing, $endpoints, $restriction, $restriction_selected_var, $locations, $concept, $configFileName, $outputFileName){
		$silk_base_file = "silkConfBase";
		$content = '<?xml version="1.0" encoding="utf-8" ?>
		<Silk>
		<Prefixes>';
		foreach ($this->prefixes as $key => $val){
			$content .= '<Prefix id="'.$key . '" namespace="'. $val.'" />
			';
		}
		$content .= '</Prefixes>
		<DataSources>';
		$dbpedia_datasource = '<DataSource id="dbpedia" type="sparqlEndpoint">
      <Param name="endpointURI" value="http://dbpedia.org/sparql" />
      <Param name="retryCount" value="'.
			$this->retryCount.'" /><Param name="retryPause" value="'.$this->retryPause.'" />
    </DataSource>';
		$content .= $dbpedia_datasource;
		$id = 0;
		foreach ($endpoints as $uri){
			$uri = str_ireplace(" ", "", $uri);
//			echo "<br /><br />current uri = $uri<br />";
			if (strpos($uri, 'dbpedia') === false){
				$content .= '<DataSource id="'.$id++.'"';
//				echo 'DataSource id="'.$id.'"<br />';
			}
			//			$uri = urlencode($uri);
			$content .= ' type="sparqlEndpoint"><Param name="endpointURI" value="'.$uri.'" /><Param name="retryCount" value="'.
			$this->retryCount.'" /><Param name="retryPause" value="'.$this->retryPause.'" /></DataSource>';
		}
		$content .= '</DataSources>
		<Interlinks>';
		//----------------INTERLINK
		$id = 0;
		$id1 = 0;
		$dbpedia_sourcedataset = '<SourceDataset dataSource="dbpedia" var="'.$restriction_selected_var.'"><RestrictTo>'.$restriction.'</RestrictTo></SourceDataset>';
		$skip = false;
		foreach ($endpoints as $uri){
			if (!$skip){
				$var = $id.$id1;
				$content .= '<Interlink id="'.$var.'_info">
		<LinkType>rdfs:seeAlso</LinkType>';
				$content .= $dbpedia_sourcedataset;
			}

			if (strpos($uri, 'dbpedia') === false){
				$content .= '<TargetDataset dataSource="'.$id.'" var="'.$var.'">
				<RestrictTo>
	?'.$var.' rdf:type ?any .
	?'.$var.' ?any1 ?any2 .
	{';
				if (isset($space) && $space){
					if (sizeof($locations > 0)){
						$content .= '{ FILTER regex(?any2, "'.$locations[0].'", "i") }';
					}
					for($ind = 1; $ind<sizeof($locations); $ind++){
						$content .= 'UNION { FILTER regex(?any2, "'.$locations[$ind].'", "i") }';
					}
				}
				if (isset($thing) && $thing){
					if (isset($space) && $space){
						$content .= 'UNION ';
					}

					
						$content .= '{ FILTER regex(?any2, "'.$concept.'", "i") }';
					
				}
				$content .= '}
    		</RestrictTo></TargetDataset>
    		<LinkageRule>
        <Aggregate type="average">
          <Aggregate type="max" required="true" >';

				$content .= '<Compare metric="levenshteinDistance" threshold="2">
              <Input path="?a/rdfs:label[@lang=\'en\']" />
              <Input path="?'.$var.'/rdfs:label[@lang=\'en\']" />
            </Compare>
            <Compare metric="levenshteinDistance" threshold="2">
              <Input path="?a/rdfs:label[@lang=\'en\']" />
              <Input path="?'.$var.'/rdfs:label[@lang=\'\']" />
            </Compare>
            </Aggregate>
       </Aggregate>              
      </LinkageRule>
      <Filter limit="1" />
      <Outputs>
        <Output ';
				if (isset($confidence)){
					$content .= 'minConfidence="'.$confidence;
				}
				$content .= '" type="file">
          <Param name="file" value="'.$outputFileName.'"/>
          <Param name="format" value="ntriples"/>
        </Output>
      </Outputs>
      
    </Interlink>';

				$id++;
				$id1++;
				$skip = false;
			}else{
				$skip = true;
			}
		}

		//@todo: get user preferences about whether considering or not space, thing + add location

		$content .= '</Interlinks>
</Silk>';


		//		// Let's make sure the file exists and is writable first.
		//		if (is_writable($configFileName)) {
		//
		//			// In our example we're opening $filename in append mode.
		//			// The file pointer is at the bottom of the file hence
		//			// that's where $somecontent will go when we fwrite() it.
		//			if (!$handle = fopen($configFileName, 'a')) {
		//				echo "Cannot open file ($configFileName)";
		//				exit;
		//			}
		//
		//			// Write $somecontent to our opened file.
		//			if (fwrite($handle, $content) === FALSE) {
		//				echo "Cannot write to file ($configFileName)";
		//				exit;
		//			}
		//		} else {
		if (!$handle = fopen($configFileName, 'w')) {
			echo "Cannot create file ($configFileName)";
			exit;
		}else{
			$content = utf8_encode($content);
			if (fwrite($handle, $content) === FALSE) {
				echo "Cannot write to file ($configFileName)";
				exit;
			}
		}
		//		}
		$data_4html = str_ireplace("<", "&lt", $content);
		$data_4html = str_ireplace(">", "&gt", $data_4html);
		$data_4html = '<pre><code>'.$data_4html.'</pre></code>';
//		echo "Success, wrote ('.$data_4html.') to file ($configFileName)";

		fclose($handle);
	}

	function initEndpoint_Cat_List($sindice){
		$geography = array();
		$government = array();
		$media = array();
		$usergeneratedcontent = array();
		$publication = array();
		$crossdomain = array();
		$lifescience = array();
		$content = file('lib/categories-map.txt');
		foreach ($content as $line){
			$splitted = split("----", $line);
			if (sizeof($splitted) == 2){
				if ($sindice){
					$dot = '://';
					$firstdot = strpos($splitted[0], $dot);
//					echo "first pos = $firstdot <br />";
//					echo "str before = $splitted[0] <br />";
					$splitted[0] = substr($splitted[0], $firstdot+strlen($dot));
//					echo "str after = $splitted[0] <br />";
					$twodots = ':';
					$lastind = strpos($splitted[0], $twodots);
//					echo "pos of ':' = $lastind <br />";
					if ($lastind === false){
						$slash = '/';
						$lastind = strpos($splitted[0], $slash);
//						echo "pos of '/' = $lastind <br />";
					}
					$splitted[0] = substr($splitted[0], 0, $lastind);
//					echo "str after = $splitted[0] <br />";
				}
				if (preg_replace('/\s+/','',$splitted[0])){
					$uristr = array($splitted[0]);
					if (strcasecmp(trim($splitted[1]), 'geography') == 0){
						$geography = array_merge($geography, $uristr);
					}else if (strcasecmp(trim($splitted[1]), 'government') == 0 && strcasecmp("rdfabout.com", $uristr) !== 0){
						$government = array_merge($government, $uristr);
					}else if (strcasecmp(trim($splitted[1]), 'media') == 0){
						$media = array_merge($media, $uristr);
						//TEST PURPOSE
						//					$media = array($uristr);
					}else if (strcasecmp(trim($splitted[1]), 'usergeneratedcontent') == 0){
						$usergeneratedcontent = array_merge($usergeneratedcontent, $uristr);
					}else if (strcasecmp(trim($splitted[1]), 'publication') == 0){
						$publication = array_merge($publication, $uristr);
					}else if (strcasecmp(trim($splitted[1]), 'crossdomain') == 0){
						$crossdomain = array_merge($crossdomain, $uristr);
					}else if (strcasecmp(trim($splitted[1]), 'lifescience') == 0){
						$lifescience = array_merge($lifescience, $uristr);
					}
				}
			}
		}
		if ($sindice){
			$geography = array_unique($geography);
			$government = array_unique($government);
			$media = array_unique($media);
			$usergeneratedcontent = array_unique($usergeneratedcontent);
			$crossdomain = array_unique($crossdomain);
			$publication = array_unique($publication);
			$lifescience = array_unique($lifescience);
		}
		$ret_array = array('geography' => $geography,
	'government' => $government, 'media' => $media, 
	'usergeneratedcontent' => $usergeneratedcontent, 
	'publication' => $publication, 'crossdomain' => $crossdomain, 
	'lifescience' => $lifescience);
		
		return $ret_array;
	}
}

?>