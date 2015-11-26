<?php
/*
 * Creates (on-the-fly) RDF triples to describe a Context (Pachube Environment) object
 * @author myriam.leggieri@deri.org
 */
require_once 'resources/AbstractResource.php';
class SensingContext extends AbstractResource{
	private $uri;
	private $triples;

	private $where;
	private $when;

	private $id;
	private $sensing_overview_id; //$feed_id
	private $place_uri;
	private $observations = Array(); //list of included observations

	private $version;
	private $description;
	private $creator;
	private $title;
	private $website;
	private $icon;
	private $tags; // should be disambiguated to mine FoIs and/or properties
	private $location;
	private $visibility_private; //true: visible only for the feed owner; false: visible to everyone

	private $status; //frequency of the updates: live or frozen
	private $status_uri;


	function __construct ($id)
	{
		//		echo "in construct";
		$this->id = $id;
		$this->uri =  $this->host."sensing_context/" .$id;
		$this->triples = '<'.AbstractResource::$ontology_ns_abbr .':SensingContext rdf:about="'.$this->uri.'" />';
		//		echo "after spo";
	}

	public function getTitle(){
		return $this->title;
	}

	private function tagCleaning($fois){
		$all_tags = array();
		
		foreach($fois as $foi){
			$concept = $foi["name"];

			$tags_rev = array();
				
					$concept = str_ireplace("'s", " ", $concept);
					if (strpos($concept, ":") !== false){
						$concepts = split(":", $concept);
					}else if (strpos($concept, "-") !== false){
						$concepts = split("-", $concept);
					}else if (strpos($concept, ",") !== false){
						$concepts = split(",", $concept);
					}else if (strpos($concept, ";") !== false){
						$concepts = split(";", $concept);
					}else if(strpos($concept, "/") !== false){
						$concepts = split("/", $concept);
					}else if (strpos($concept, "\\") !== false){
						$concepts = split("\\", $concept);
					}else if (strpos($concept, "&") !== false){
						$concepts = split("&", $concept);
					}else if (strpos($concept, "and") !== false){
						$concepts = split("and", $concept);
					}else{
						$concepts = array($concept);
					}
					foreach ($concepts as $elem){
						$elem = ucfirst(trim($elem));
						if (preg_replace('/\s+/','',$elem)){
							$tags_rev = array_merge(array($elem), $tags_rev);
						}
					}
					$all_tags = array_merge($tags_rev, $all_tags);				
			}
		return array_unique($all_tags);
	}

	public function getFOIs(){
		$fois = Array();
		foreach ($this->observations as $obs){
			array_push($fois, $obs->getFOI());
		}

		return $this->tagCleaning($fois);
	}

	public function getLocation(){

	}

	public function getTime(){

	}

	public function getStatusUri(){
		return $this->status_uri;
	}

	public function getRDFXMLHeader(){
		$header = '<?xml version="1.0"?>
<rdf:RDF ';
		foreach ($this->prefixes as $key => $uri){
			$header .= 'xmlns:'.$key.'="'.$uri.'"
	'; 
		}
		$header .= '>';
		return $header;
	}

	public function getRDFXMLFooter(){
		$footer = "</rdf:RDF>";
		return $footer;
	}

	public function getTriples(){
		return $this->triples;
	}

	public function getContextObservationsTriples(){
		$return_array = $this->triples;
		foreach ($this->observations as $obs){
			$return_array .= $obs->getTriples();
		}
		return $return_array;
	}

	public function getUri(){
		return $this->uri;
	}

	public function setVersion($version){
		if (isset($version) && trim($version)){
			$this->version = $version;
			$this->triples .= '<rdf:Description rdf:about="'.$this->uri.'">
			<'.	AbstractResource::$dcterms_abbr .':hasVersion rdf:resource="'.
			$this->uri ."/version/". $version.'" />
			</rdf:Description>';
		}
	}

	public function setLocation($locuri){
		if (isset($locuri) && trim($locuri)){
			$this->location = $locuri;
			$this->triples .= '<rdf:Description rdf:about="'.$this->uri.'">
			<'.	AbstractResource::$dul_abbr .':hasLocation rdf:resource="'.
			$locuri.'" />
			</rdf:Description>';
		}
	}
	public function setCreator($creator){
		if (isset($creator) && trim($creator)){
			$this->creator = $creator;
			$this->triples .= '<rdf:Description rdf:about="'.$this->uri.'">
			<'.	AbstractResource::$dc_abbr .':creator';
			$pos = strpos($creator,"http://");

			if($pos === false) {
				$this->triples .= '>'.$creator.'</'.AbstractResource::$dc_abbr.':creator>
				</rdf:Description>';
			}else{
				$this->triples .=  ' rdf:resource="'.$creator.'" />
	</rdf:Description>';
			}
		}
	}

	public function setDescription($desc){
		if (isset($desc) && trim($desc)){
			$this->description = $desc;
			$this->triples .= '<rdf:Description rdf:about="'.$this->uri.'">
			<'.AbstractResource::$dc_abbr .':description>'.$desc.'</'.AbstractResource::$dc_abbr .':description>
			</rdf:Description>';
		}
	}

	public function setTitle($title){
		if (isset($title) && trim($title)){
			$this->title = $title;
			$this->triples .= '<rdf:Description rdf:about="'.$this->uri.'">
			<'.AbstractResource::$dc_abbr .":title>".$title.'</'.AbstractResource::$dc_abbr .":title>
			</rdf:Description>";
		}
	}

	public function setStatus($status){
		if (isset($status) && trim($status)){
			$this->status = $status;
			if (strcmp($status, $this->status_live == 0)){
				$this->status_uri=AbstractResource::$ontology_ns_abbr .':LiveContext';
			}else{
				$this->status_uri=AbstractResource::$ontology_ns_abbr .':FrozenContext';
			}
			$this->triples .= '<'.$this->status_uri.' rdf:about="'. $this->uri.'" />';
		}
	}

	public function setWebsite($website){
		if (isset($website) && trim($website)){
			$this->website = $website;
			$this->triples .= '<rdf:Description rdf:about="'.$this->uri.'">
			<'.	AbstractResource::$foaf_abbr .':homepage>'.$website.'</'.AbstractResource::$foaf_abbr .':homepage>
			</rdf:Description>';
		}
	}

	public function setLastModified($updated){
		if (isset($updated) && trim($updated)){
			$this->when = $updated;
			$this->triples .= '<rdf:Description rdf:about="'.$this->uri.'">
			<'.	AbstractResource::$dcterms_abbr .":modified>".$updated.'</'.AbstractResource::$dcterms_abbr .':modified>
			</rdf:Description>';
		}
	}


	public function setTags($tags){
		if (isset($tags)){
			foreach ($tags as $tag){
				//				$tag = trim($tag); //<--doesn't work...
				$tag = str_ireplace(" ", "", $tag);
				$tag_uri = $this->host ."tag/". ucfirst($tag);
				$this->triples .= '<'.AbstractResource::$nepomukao_abbr .':Tag rdf:about="'
				.$tag_uri.'" />';

				$this->triples .= '<rdf:Description rdf:about="'.$this->uri.'">
			<'.AbstractResource::$nepomukao_abbr.':hasTag rdf:resource="'.$tag_uri.'" />
	</rdf:Description>';
			}
		}
	}

	public function setPlace($place_uri){
		if (isset($place_uri) && trim($place)){
			//			$p = new Place($place);
			$this->triples .= '<rdf:Description rdf:about="'.$this->uri.'">
			<'.AbstractResource::$dul_abbr .':hasLocation rdf:resource="'.$place_uri.'" />
	</rdf:Description>';
			$this->place_uri = $place_uri;
			//			return $p;
		}
		//		return NULL;
	}



	public function setObservations($feed_datastreams, $pachube, $start, $end, $interval){
		//		echo "# of datastreams = ".count($feed_datastreams)."<br />";
		require_once 'resources/Observation.php';
		require_once 'resources/UnitOfMeasure.php';
		foreach ( $feed_datastreams as $ds )
		{
			//print_r ($result);
			$id = $ds["id"];
			$time = $ds["at"];
			$tags = $ds["tags"];
			$currval = $ds["current_value"];
			$maxval = $ds["max_value"];
			$minval = $ds["min_value"];

			$obj = new Observation($this->id."_".$id, $this->uri);
			$obj->setSamplingTime($time);
			
			$obj->setFOI($tags);
			$obj->setStatus($this->status);

			//			echo "<h1>datastream ID ".$id."</h1>";	//datastreamID(+contextId)->observationId
			//			echo "when: ". $time ."<br />";
			//			echo "tags: ";
			//			foreach($tags as $tag){
			//				echo $tag .", ";
			//			}

			//TODO: create all the methods that follow here
			//			echo "<br />current_value = ". $currval ."<br />";
			$obj->setCurrValue($currval); //associated with ObservationOutput and Observation (as a shortcut)
			//			echo "max_value= ". $maxval ."<br />"; //associated with ObservationOutput
			//			echo "min_value= ". $minval ."<br />"; //associated with ObservationOutput
			$obj->setMinValue($minval);
			$obj->setMaxValue($maxval);
			//to get datapoints
			$datap = $pachube->datapoints($this->id, $id, $start, $end, $interval);
			//		or 			http://api.pachube.com/v2/feeds/<feedid>/datastreams/<datastreamid>/datapoints?start=<timestamp>&end=
			$obj->setRawValues($datap); //TODO: add to observationoutput hasValue per each rawvalue

			//where is the UoM (to be associated with ObservationOutput)?
			$unit = $ds['unit'];
			$uom = new UnitOfMeasure($unit['label'], $unit['type'], $unit['symbol']);
			$obj->setUnitOfMeasurement($uom->getUri());
			array_push($this->observations, $obj);
		}
	}
}
?>