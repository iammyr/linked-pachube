<?php
require_once 'resources/AbstractResource.php';
class Place extends AbstractResource  {
	private $uri;
	private $triples;

	private $id;
	private $domain; //physical or virtual
	private $exposure; //indoor or outdoor
	private $disposition; //mobile or static
	private $latitude;
	private $longitude;
	private $elevation;

	function __construct($id, $exposure, $disposition){
		$this->triples = "<";
		if (isset($exposure) && 
			(strcasecmp($exposure, $this->exposure_indoor) == 0)){
			$this->triples .= $this->ontology_ns_abbr .":IndoorPlace";
		}else{
			$this->triples .= $this->dul_abbr .":PhysicalPlace";
		}
		$this->triples .= ' rdf:about="'.$this->uri.'" />';
	}

	public function getTriples(){
		return $this->triples;
	}

	public function getUri(){
		return $this->uri;
	}

	public function setDomain($domain){

	}
}
?>