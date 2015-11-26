<?php
/*
 * Creates (on-the-fly) RDF triples to describe an ObservationOutput
 * (content of a Pachube DataStream) object.
 * This is a "sort of" (because it contains also non-static info) template for the output.
 * @author: myriam.leggieri@deri.org
 */
require_once 'resources/AbstractResource.php';
class ObservationOutput extends AbstractResource{
	private $uri;
	private $triples;

	private $id;
	private $sensor_id; //(isProducedBy) Pachube API does not provide this info

	private $unit_of_measure_id;

	//the following information change as updates occurr
	private $min_val; //since the last reset
	private $max_val; //since the last reset
	private $curr_val; //the most recent raw value collected
	private $raw_value_ids; //A collection of timestamped values

	function __construct ($id, $observationuri)
	{
		$this->triples = "";
		$this->uri =  $this->host."observationoutput/" .$id;
		$this->triples .= '<'.$this->ssn_abbr .':ObservationOutput rdf:about="'.$this->uri.'" />';

		$this->triples .= '<rdf:Description rdf:about="'.$this->uri.'">
		<'.$this->dul_abbr.':partOf rdf:resource="'.$observationuri.'" />
		</rdf:Description>';

		$this->raw_value_ids = Array();
	}

	public function addRawValue($time, $value){
		require_once 'resources/RawValue.php';
		$raw = new RawValue($time, $this->uri, $value, $time);
		array_push($this->raw_value_ids, $raw->getUri());

		$this->triples .= '<rdf:Description rdf:about="'.$this->uri.'">
		<'.$this->ontology_ns_abbr.':hasRawValue rdf:resource="'.$raw->getUri().'" />
		</rdf:Description>';
	}

	public function getRawValueIds(){
		return $this->raw_value_ids;
	}

	public function getTriples(){
		return $this->triples;
	}

	public function getUri(){
		return $this->uri;
	}

	public function setCurrValue($updated){
		if (isset($updated) && trim($updated)){
			$this->curr_val = $updated;
			$this->triples .= '<rdf:Description rdf:about="'.$this->uri.'">
		<'.$this->ontology_ns_abbr.':hasLastValue>'.$updated.'</'.$this->ontology_ns_abbr.':hasLastValue>
			</rdf:Description>';
		}
	}

	public function setUnitOfMeasurement($updated){
		if (isset($updated) && trim($updated)){
			$this->unit_of_measure_id = $updated;
			$this->triples .= '<rdf:Description rdf:about="'.$this->uri.'">
		<'.$this->dul_abbr.":isParametrizedBy>".$this->unit_of_measure_id.'</'.$this->dul_abbr.':isParametrizedBy>
			</rdf:Description>';
		}
	}

	public function setMinValue($updated){
		if (isset($updated) && trim($updated)){
			$this->min_val = $updated;
			$this->triples .= '<rdf:Description rdf:about="'.$this->uri.'">
		<'.$this->ontology_ns_abbr.":hasMinValue>".$updated.'</'.$this->ontology_ns_abbr.':hasMinValue>
			</rdf:Description>';
		}
	}

	public function setMaxValue($updated){
		if (isset($updated) && trim($updated)){
			$this->max_val = $updated;
			$this->triples .= '<rdf:Description rdf:about="'.$this->uri.'">
		<'.$this->ontology_ns_abbr.":hasMaxValue>".$updated.'</'.$this->ontology_ns_abbr.':hasMaxValue>
			</rdf:Description>';
		}
	}

}
?>