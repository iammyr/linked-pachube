<?php


require_once '../resources/TripleCreator.php';
require_once '../resources/AbstractResource.php';



class OwnerDependentResource{


	public function handleGet($data){
		if (!$data
		|| !isset($data->sensor_id)){
			return null;
		}
		$index = array();
		$sensor_id = str_ireplace(" ", "_", $data->sensor_id);
		$observed_property_uri = getValue('property', $data->observed_property);
		$sensor_id_uri = AbstractResource::$resource_base.'sensor/'.$sensor_id;

		if (isset($data->sensor_owner)){
			$sensor_owner = $data->sensor_owner;
			if ($sensor_owner){
				$sensor_owner = getValue('sensor_owner', $sensor_owner, true);
			}
		}
		if (isset($data->sensor_publisher)){
			$sensor_publisher = $data->sensor_publisher;
			if ($sensor_publisher){
				$sensor_publisher = getValue('sensor_publisher', $sensor_publisher, true);
			}
		}
	if (isset($data->sensor_location)){
			$sensor_location = $data->sensor_location;
			if ($sensor_location){
				$sensor_location = getValue('sensor_location', $sensor_location);
			}
		}
		if(isset($data->sensor_foi)){
			$foi = $data->sensor_foi;
			if ($foi){
				$foi = getValue('foi', $foi);
			}
		}
		if (isset($data->sensor_platform)){
			$platform_uri = $data->sensor_platform;
			if ($platform_uri){
				$platform_uri = getValue('sensor_platform', $platform_uri);
			}
		}
		if (isset($data->sensor_history)){
			$history_uri = $data->sensor_history;
			if ($history_uri){
				$history_uri = getValue('sensor_history', $history_uri);
			}
		}
		$additional_owner_index = getAdditionalOwnerTriples($sensor_id_uri, $sensor_owner, $sensor_publisher, $sensor_location, $foi, $platform_uri, $history_uri);
		

		return $additional_owner_index;
	}


	public function __construct()
	{

	}

	 
}

?>