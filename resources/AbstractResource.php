<?php
abstract class AbstractResource {
	public static $mediaConfigFileName = 'silk_configuration_media.xml';
	public static $foaf_query_access_key = 'evMRvYhxLHudRxShuV2pRPdgotcHFR0Q';
	
	public static $dc_abbr = "dc";
	public static $dcterms = "http://purl.org/dc/terms/";
	public static $dcterms_abbr = "dcterms";
	public static $dcelement = "http://dublincore.org/2010/10/11/dcelements.rdf#";
	public static $dcelement_abbr = "dcelem";
	public static $foaf = "http://xmlns.com/foaf/0.1/";
	public static $foaf_abbr = "foaf";
	public static $nepomukao = "http://www.semanticdesktop.org/ontologies/2007/08/15/nao";
	public static $nepomukao_abbr = "nepo";
	public static $dul = "http://www.loa-cnr.it/ontologies/DUL.owl#";
	public static $dul_abbr = "dul";
	public static $rdf = "http://www.w3.org/1999/02/22-rdf-syntax-ns#";
	public static $rdf_abbr = "rdf";
	public static $rdfs = "http://www.w3.org/2000/01/rdf-schema#";
	public static $rdfs_abbr = "rdfs";
	public static $uom = "http://purl.oclc.org/NET/muo/muo#";
	public static $uom_abbr = "uom";
	public static $geo = "http://www.w3.org/2003/01/geo/wgs84_pos#";
	public static $geo_abbr = "geo";	
	public static $ontology_ns = "http://spitfire-project.eu/cc/spitfireCC_n3.owl#";
	public static $ontology_ns_abbr = "ontons";
	public static $owl = "http://www.w3.org/2002/07/owl#";
	public static $owl_abbr = "owl";
	public static $resource_base = "http://spitfire-project.eu/";
	public static $resource_base_abbr = "spitbase";
	public static $ssn = "http://purl.oclc.org/NET/ssnx/ssn#";
	public static $ssn_abbr = "ssn";
	public static $event = "http://events.semantic-multimedia.org/ontology/2008/12/15/model.owl#";
	public static $event_abbr = "event";
	public static $temp_ont = "http://sweet.jpl.nasa.gov/2.1/propTemperature.owl#";
	public static $temp_ont_abbr = "temp";
	public static $physic_ont = "http://sweet.jpl.nasa.gov/2.1/procPhysical.owl#";
	public static $physic_ont_abbr = "physic";
	public static $pressure_ont = "http://sweet.jpl.nasa.gov/2.1/propPressure.owl#";
	public static $pressure_ont_abbr = "pressure";
	public static $uom_ont = "http://purl.oclc.org/NET/muo/muo#";
	public static $uom_ont_abbr = "uom";
	
	public $prefixes = array(
	'dc' => "http://purl.org/dc/elements/1.1/",
	
	'dcterms' => "http://purl.org/rss/1.0/modules/dcterms/",
	
	'foaf' => "http://xmlns.com/foaf/0.1/",
	
	'nepo' => "http://www.semanticdesktop.org/ontologies/2007/08/15/nao",
	
	'dul' => "http://www.loa-cnr.it/ontologies/DUL.owl#",
	
	'rdf' => "http://www.w3.org/1999/02/22-rdf-syntax-ns#",
	
	'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',
	
	'geo' => "http://www.w3.org/2003/01/geo/wgs84_pos#",
		
	'ontons' => "http://spitfire-project.eu/cc/spitfireCC_n3.owl#",
	
	'owl' => "http://www.w3.org/2002/07/owl#",
	
	'ssn' => "http://purl.oclc.org/NET/ssnx/ssn#",
	
	'event' => "http://events.semantic-multimedia.org/ontology/2008/12/15/model.owl#",
	
	'temp' => "http://sweet.jpl.nasa.gov/2.1/propTemperature.owl#",
	
	'physic' => "http://sweet.jpl.nasa.gov/2.1/procPhysical.owl#",
	
	'pressure' => "http://sweet.jpl.nasa.gov/2.1/propPressure.owl#",
	
	'uom' => "http://purl.oclc.org/NET/muo/muo#");
	
	public static $bnode_count = 0;
	
	
	protected $retryCount = 2;
	protected $retryPause = 1000;
	
	protected $host = "http://spitfire-project.eu/";
	protected $host_abbr = "defns";
	
	protected $status_live = "live";
	protected $exposure_indoor = "indoor";
	
	protected $phenomenon_onts_uri = array(
	'physical' => "http://sweet.jpl.nasa.gov/2.1/procPhysical.owl#", 
	'pressure' => "http://sweet.jpl.nasa.gov/2.1/propPressure.owl#", 
	'temperature' => "http://sweet.jpl.nasa.gov/2.1/propTemperature.owl#"
	,'phen' => "http://sweet.jpl.nasa.gov/2.1/phen.owl#", 
	'phenatmo' => "http://sweet.jpl.nasa.gov/2.1/phenAtmo.owl#"
	, 'phenatmocloud' => "http://sweet.jpl.nasa.gov/2.1/phenAtmoCloud.owl#"
	, 'phenatmofog' => "http://sweet.jpl.nasa.gov/2.1/phenAtmoFog.owl#"
	, 'phenatmofront' => "http://sweet.jpl.nasa.gov/2.1/phenAtmoFront.owl#"
	, 'lightning' => "http://sweet.jpl.nasa.gov/2.1/phenAtmoLightning.owl#"
	, 'precipitation' => "http://sweet.jpl.nasa.gov/2.1/phenAtmoPrecipitation.owl#"
	, 'atmopressure' => "http://sweet.jpl.nasa.gov/2.1/phenAtmoPressure.owl#"
	, 'atmosky' => "http://sweet.jpl.nasa.gov/2.1/phenAtmoSky.owl#"
	, 'phenAtmoTransport' => "http://sweet.jpl.nasa.gov/2.1/phenAtmoTransport.owl#"
	, 'phenAtmoWind' => "http://sweet.jpl.nasa.gov/2.1/phenAtmoWind.owl#"
	, 'phenAtmoWindMesoscale' => "http://sweet.jpl.nasa.gov/2.1/phenAtmoWindMesoscale.owl#"
	, 'phenBiol' => "http://sweet.jpl.nasa.gov/2.1/phenBiol.owl#"
	, 'phenCryo' => "http://sweet.jpl.nasa.gov/2.1/phenCryo.owl#"
	, 'phenEcology' => "http://sweet.jpl.nasa.gov/2.1/phenEcology.owl#"
	, 'phenElecMag' => "http://sweet.jpl.nasa.gov/2.1/phenElecMag.owl#"
	, 'phenEnergy' => "http://sweet.jpl.nasa.gov/2.1/phenEnergy.owl#"
	, 'envirimpact' => "http://sweet.jpl.nasa.gov/2.1/phenEnvirImpact.owl#"
	, 'fluidDynamics' => "http://sweet.jpl.nasa.gov/2.1/phenFluidDynamics.owl#"
	, 'fluidInstability' => "http://sweet.jpl.nasa.gov/2.1/phenFluidInstability.owl#"
	, 'fluidTransport' => "http://sweet.jpl.nasa.gov/2.1/phenFluidTransport.owl#"
	, 'phenGeol' => "http://sweet.jpl.nasa.gov/2.1/phenGeol.owl#"
	, 'geolFault' => "http://sweet.jpl.nasa.gov/2.1/phenGeolFault.owl#"
	, 'geoMorphology' => "http://sweet.jpl.nasa.gov/2.1/phenGeolGeomorphology.owl#"
	, 'seismicity' => "http://sweet.jpl.nasa.gov/2.1/phenGeolSeismicity.owl#"
	, 'tectonic' => "http://sweet.jpl.nasa.gov/2.1/phenGeolTectonic.owl#"
	, 'volcano' => "http://sweet.jpl.nasa.gov/2.1/phenGeolVolcano.owl#"
	, 'helio' => "http://sweet.jpl.nasa.gov/2.1/phenHelio.owl#"
	, 'hydro' => "http://sweet.jpl.nasa.gov/2.1/phenHydro.owl#"
	, 'phenmixing' => "http://sweet.jpl.nasa.gov/2.1/phenMixing.owl#"
	, 'ocean' => "http://sweet.jpl.nasa.gov/2.1/phenOcean.owl#"
	, 'coastal' => "http://sweet.jpl.nasa.gov/2.1/phenOceanCoastal.owl#"
	, 'oceanDynamics' => "http://sweet.jpl.nasa.gov/2.1/phenOceanDynamics.owl#"
	, 'planetClimate' => "http://sweet.jpl.nasa.gov/2.1/phenPlanetClimate.owl#"
	, 'planetOsciallation' => "http://sweet.jpl.nasa.gov/2.1/phenPlanetOscillation.owl#"
	, 'reaction' => "http://sweet.jpl.nasa.gov/2.1/phenReaction.owl#"
	, 'recycle' => "http://sweet.jpl.nasa.gov/2.1/phenRecycle.owl#"
	, 'solid' => "http://sweet.jpl.nasa.gov/2.1/phenSolid.owl#"
	, 'star' => "http://sweet.jpl.nasa.gov/2.1/phenStar.owl#"
	, 'system' => "http://sweet.jpl.nasa.gov/2.1/phenSystem.owl#"
	, 'wave' => "http://sweet.jpl.nasa.gov/2.1/phenWave.owl#"
	, 'waveNoise' => "http://sweet.jpl.nasa.gov/2.1/phenWaveNoise.owl#"
	);
	protected $phenomenon_onts = array("phenomenonOntologies/physical.owl", "phenomenonOntologies/propPressure.owl", "phenomenonOntologies/temperature.owl"
	, "phenomenonOntologies/phen.owl", "phenomenonOntologies/phenAtmo.owl", "phenomenonOntologies/phenAtmoCloud.owl", "phenomenonOntologies/phenAtmoFog.owl"
, "phenomenonOntologies/phenAtmoFront.owl", "phenomenonOntologies/phenAtmoLightning.owl", "phenomenonOntologies/phenAtmoPrecipitation.owl", 
"phenomenonOntologies/phenAtmoPressure.owl", "phenomenonOntologies/phenAtmoSky.owl", "phenomenonOntologies/phenAtmoTransport.owl"
, "phenomenonOntologies/phenAtmoWind.owl", "phenomenonOntologies/phenAtmoWindMesoscale.owl", "phenomenonOntologies/phenBiol.owl"
, "phenomenonOntologies/phenCryo.owl", "phenomenonOntologies/phenEcology.owl", "phenomenonOntologies/phenElecMag.owl", "phenomenonOntologies/phenEnergy.owl"
, "phenomenonOntologies/phenEnvirImpact.owl", "phenomenonOntologies/phenFluidDynamics.owl", "phenomenonOntologies/phenFluidInstability.owl"
, "phenomenonOntologies/phenFluidTransport.owl", "phenomenonOntologies/phenGeol.owl", "phenomenonOntologies/phenGeolFault.owl", "phenomenonOntologies/phenGeolGeomorphology.owl"
, "phenomenonOntologies/phenGeolSeismicity.owl", "phenomenonOntologies/phenGeolTectonic.owl", "phenomenonOntologies/phenGeolVolcano.owl", "phenomenonOntologies/phenHelio.owl"
, "phenomenonOntologies/phenHydro.owl", "phenomenonOntologies/phenMixing.owl", "phenomenonOntologies/phenOcean.owl", "phenomenonOntologies/phenOceanCoastal.owl"
, "phenomenonOntologies/phenOceanDynamics.owl", "phenomenonOntologies/phenPlanetClimate.owl", "phenomenonOntologies/phenPlanetOscillation.owl", "phenomenonOntologies/phenReaction.owl"
, "phenomenonOntologies/phenRecycle.owl", "phenomenonOntologies/phenSolid.owl", "phenomenonOntologies/phenStar.owl", "phenomenonOntologies/phenSystem.owl"
, "phenomenonOntologies/phenWave.owl", "phenomenonOntologies/phenWaveNoise.owl");
	

	
}

require_once 'resources/ConceptHandler.php';
function getValue($resource, $id, $foaf=false){
	if (startsWith($id, "http")){
		$ret = $id;
	}else{
		if (!$foaf){
			$id = conceptCleaner($id);
		}
		$ret = conceptFinder($resource, $id, $foaf);
	}
	return $ret;
}

function startsWith($haystack, $needle)
{
	$length = strlen($needle);
	return (substr($haystack, 0, $length) === $needle);
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    $start  = $length * -1; //negative
    return (substr($haystack, $start) === $needle);
}
?>