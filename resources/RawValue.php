<?php
/*
 * Creates (on-the-fly) RDF triples to describe a Raw Value (Pachube Data Point) object. It
 * might belong to different Sensing Contexts, but to only one Observation and its output.
 * @author myriam.leggieri@deri.org
 */
require_once 'resources/AbstractResource.php';
class RawValue extends AbstractResource {
	private $uri;
	private $triples;

	private $id;
	private $observation_output_id;

	private $value;
	private $timestamp;

	//---shortcuts
	private $sensor_id;
	private $observation_id;

	function __construct ($id, $observationoutputuri, $value, $time)
	{
		$this->triples = "";
		$this->uri =  $this->host."rawvalue/" .$id;
		$this->triples .= '<'.$this->ssn_abbr . ':RawValue rdf:about="'.$this->uri.'" />';

		$this->triples .= '<rdf:Description rdf:about="'.$this->uri.'">
		<'.$this->dul_abbr.':partOf rdf:resource="'.$observationoutputuri.'">
		</rdf:Description>';
		
		$this->triples .= '<rdf:Description rdf:about="'.$this->uri.'">
		<'.$this->dul_abbr .":hasValue>".$value.'</'.$this->dul_abbr .':hasValue>
			</rdf:Description>';
		
		$this->triples .= '<rdf:Description rdf:about="'.$this->uri.'">
		<'.$this->ssn_abbr .":samplingTime>".$time.'</'.$this->ssn_abbr .':samplingTime>
			</rdf:Description>';
	}

	public function getTriples(){
		return $this->triples;
	}

	public function getUri(){
		return $this->uri;
	}
}

?>