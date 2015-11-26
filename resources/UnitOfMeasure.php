<?php
require_once 'resources/AbstractResource.php';
class UnitOfMeasure extends  AbstractResource {
private $uri;
	private $triples;
//	
//	private $id;
//	private $symbol;
//	private $type; //complex or basic
//	private $sub_uom_ids; //list of other uom involved (in case this is a complex uom)

	function __construct($unit, $unit_type='', $unit_symbol){
		$this->triples = "<";
		$this->uri =  $this->host."uom/" .$unit_symbol;
		if (!empty($unit_type)){		
			$this->triples .= $this->ontology_ns_abbr.':'.ucfirst($unit_type);
		}else{
			$this->triples .= $this->dul_abbr . ":UnitOfMeasure";
		}
		$this->triples .= ' rdf:about="'.$this->uri.'" />';
		
		$this->triples .='<rdf:Description rdf:about="'.$this->uri.'">
		<'.$this->uom_ont_abbr . ':prefSymbol>'.$unit_symbol.'</'.$this->uom_ont_abbr .'>
		</rdf:Description>';
		
		$this->triples .='<rdf:Description rdf:about="'.$this->uri.'">
		<'.$this->uom_ont_abbr . ":preferredUnit>".$unit.'</'.$this->uom_ont_abbr . ':preferredUnit>
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