<?php
/*
 * Creates (on-the-fly) RDF triples to describe an Observation (Pachube DatStream) object
 * @author: myriam.leggieri@deri.org
 */
require_once 'resources/AbstractResource.php';
class Observation extends AbstractResource {
	private $uri;
	private $triples;

	private $id;
	private $result_time;
	private $sampling_time;
	private $tags; // should be disambiguated to mine FoIs and/or properties
	private $status; //frequency of the updates: live or frozen
	private $status_uri;

	//only one output, foi and property per observation are allowed by the SSN-XG ontology
	private $observation_output;
	private $feature_of_interest;
	private $observed_property;
	private $sensor_id; //(observedBy) Pachube API does not provide this info
	private $stimulus; //(dul:includesEvent)

	//---shortcuts
	private $value_id; //only one output value (but with no restriction) is allowed by the SSN-XG ontology
	private $min_val; //minimum value collected in the correspondent output since the last reset
	private $max_val; //maximum value collected in the correspondent output since the last reset
	private $curr_val; //current value collected in the correspondent output

	function __construct ($id, $contexturi)
	{
		require_once 'resources/ObservationOutput.php';
		$this->triples = Array();
		$this->uri =  $this->host."observation/" .$id;
		$this->observation_output = new ObservationOutput($id."_output", $this->uri);

		$this->triples = '<'.$this->ssn_abbr.':Observation rdf:about="'.$this->uri.'" />';

		$this->triples .= '<rdf:Description rdf:about="'.$this->uri.'">
		<'.$this->dul_abbr.':partOf rdf:resource="'.$contexturi.'" />
		</rdf:Description>';
	}

	public function getStatusUri(){
		return $this->status_uri;
	}

	public function getTriples(){
		return $this->triples;
	}

	public function getUri(){
		return $this->uri;
	}

	public function setStatus($status){
		if (isset($status) && trim($status)){
			$this->status = $status;
			if (strcmp($status, $this->status_live == 0)){
				$this->status_uri = $this->ontology_ns_abbr.':LiveObservation';
			}else{
				$this->status_uri = $this->ontology_ns_abbr.'FrozenObservation';
			}
			$this->triples .= '<'.$this->status_uri.' rdf:about="'.$this->uri.'" />';
		}
	}

	public function setSamplingTime($updated){
		if (isset($updated) && trim($updated)){
			$this->sampling_time = $updated;
			$this->triples .= '<rdf:Description rdf:about="'.$this->getUri().'">
			<'.$this->ssn_abbr.":observationSamplingTime>".$updated.'</'.$this->ssn_abbr.':observationSamplingTime>
			</rdf:Description>';
		}
	}

	public function setCurrValue($updated){
		if (isset($updated) && trim($updated)){
			$this->curr_val = $updated;
			$this->observation_output->setCurrValue($updated);
			$this->triples .= '<rdf:Description rdf:about="'.$this->getUri().'">
			<'.$this->ontology_ns_abbr.":hasLastValue>".$updated.'</'.$this->ontology_ns_abbr.':hasLastValue>
			</rdf:Description>';
		}
	}

	public function setMinValue($updated){
		if (isset($updated) && trim($updated)){
			$this->min_val = $updated;
			$this->observation_output->setMinValue($updated);
			$this->triples .= '<rdf:Description rdf:about="'.$this->getUri().'">
			<'.$this->ontology_ns_abbr.":hasMinValue>".$updated.'</'.$this->ontology_ns_abbr.':hasMinValue>
			</rdf:Description>';
		}
	}

	public function setMaxValue($updated){
		if (isset($updated) && trim($updated)){
			$this->max_val = $updated;
			$this->observation_output->setMaxValue($updated);
			$this->triples .= '<rdf:Description rdf:about="'.$this->getUri().'">
			<'.$this->ontology_ns_abbr.":hasMaxValue>".$updated.'</'.$this->ontology_ns_abbr.':hasMaxValue>
			</rdf:Description>';
		}
	}

	public function setRawValues($datapoints){
		if (isset($datapoints)){
			foreach ($datapoints as $dp){
				$this->observation_output->addRawValue($dp['at'], $dp['value']);
			}
		}
	}

	public function getFOI(){
		return $this->feature_of_interest;
	}


	public function setFOI($foi){
		if (isset($foi)){
			foreach($foi as $tag){
				if (!isset($this->feature_of_interest) && !is_numeric($tag)
				//				&&(strcasecmp($tag, "Temperature") == 0
				//				|| strcasecmp($tag, "Humidity") == 0
				//				|| strcasecmp($tag, "Pressure") == 0)
				){
					$this->triples .= '<rdf:Description rdf:about="'.$this->getUri().'">
						<'.$this->ssn_abbr.':featureOfInterest';
//			*************NO SEARCH IN SWEET (search will be done dynamically among cross-domain datasets)		
//					$phenom_uri = $this->getPhenomenonFromTag($tag);
//					if (!is_null($phenom_uri)){
//						$this->feature_of_interest = $phenom_uri;
//						$this->triples .= ' rdf:resource="'.$this->feature_of_interest.'" />
//		</rdf:Description>';
//					}else{
						$tagtrim = trim($tag);
						$this->feature_of_interest = array('uri' => '', 'name' => $tagtrim, 'source' => 'none');
						$this->triples .= '>'.$this->temp_ont.$tagtrim.'</'.$this->ssn_abbr.':featureOfInterest>
			</rdf:Description>';
//					}
				}
				
			}
		}
	}


	private function getPhenomenonFromTag($tag){
		$returnstr = NULL;
		$source="";
		for ($yind = 0; $yind<sizeof($this->phenomenon_onts) && is_null($returnstr); $yind++){
			$ont = $this->phenomenon_onts[$yind];
			$ontcontent = file_get_contents($ont);
			//			$printableontcontent = getPrintableCode($ontcontent);
			//			echo "ont=$ont; CONTENT:<br />$printableontcontent";
			//first check the words with no space
			$tagtrim = trim($tag);
			$foundind = -1;
			$prev = 'Class rdf:about="#';
			$succ = '"';
			//echo "search for $prev.$tagtrim.$succ ...";
			$ind1=stripos($ontcontent, $prev.$tagtrim.$succ);
			//			echo "IND1=$ind1<br />";
			if($ind1 === false){ //if nothing is found
				//then check each single token
				//				echo "...nothing found.<br />";

				$toks = split(" ", $tag);
				$pos = -1;
				for($ind = 0; $ind<sizeof($toks)&& ($pos=stripos($ontcontent, $prev.$toks[$ind].$succ)) === false; $ind++){
					//					echo "Searching for $prev+(toks[$ind]=".$toks[$ind].") while stripos=$pos<br />";
				}
				if ($pos !== false){
					$foundind = $pos;
					//					echo "!!found=$foundind<br />";
					$source = "SWEET";
				}else{
					//@todo: check on dbpedia (/sumo)
					//$source="DBPEDIA";
				}
			}else{
				$foundind = $ind1;
				//				echo "!!found=$foundind<br />";
				$source = "SWEET";
			}
				
			if ($foundind != -1){
				$row = substr($ontcontent, $foundind+strlen($prev));
				//				echo "ROW=".getPrintableCode($row)."<br />";
				$foundend = strpos($row, '>');
				//				echo "!!foundend=$foundend<br />";
				$concept_id = substr($row, 0, $foundend-strlen($succ));

				$prev_content = "<rdfs:comment ";
				$succ_content = ">";
				$pos_content = stripos($row, $prev_content);
				$content="";
				if ($pos_content != false){
					$content_substr= substr($row, $pos_content+strlen($prev_content));
					$foundend = strpos($content_substr, $succ_content);
					$content = substr($content_substr, 0, $foundend-strlen($succ_content));	
				}
				
					
				//				echo "!!concept id = $concept_id<br />";
				$returnstr = array('uri' => $this->phenomenon_onts_uri[$yind].$concept_id, 'name' => $concept_id, 'source' => $source, 'content' => $content);
				//				echo "!!return str = $returnstr<br />";
			}else{
				//				echo "no foundind<br />";
			}
		}
		return $returnstr;
	}

	public function setUnitOfMeasurement($updated){
		$this->observation_output->setUnitOfMeasurement($updated);
	}

	public function getRawValueIds(){
		return $this->observation_output->getRawValueIds();
	}


}
?>
<html>
<head>
<title>Prova</title>
</head>
<body>

</body>
</html>
