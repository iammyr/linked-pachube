<?php


require_once '../resources/TripleCreator.php';
require_once '../resources/AbstractResource.php';



class NodeDependentResource{


	public function handleGet($data){
		if (!$data
		|| !isset($data->sensor_id)
		|| !isset($data->observed_property)){
			return null;
		}
		$index = array();
		$needle = "http";
		$needle_length = strlen($needle);

		$sensor_id = str_ireplace(" ", "_", $data->sensor_id);
		$sensor_id_uri = AbstractResource::$resource_base.'sensor/'.$sensor_id;

		$observed_property_uri = getValue('property', $data->observed_property);

		if(isset($data->capabilities)){
			$capabilities = $data->capabilities;
		}
		if (isset($data->conditions)){
			$conditions = $data->conditions;
		}
		if (isset($data->sensor_model)){
			$model = $data->sensor_model;
			$model_uri = '';
			if ($model){
				$model_uri = getValue('sensor_model', $model);
			}
		}
		if (isset($data->sensor_manual)){
			$manual_uri = '';
			$manual = $data->sensor_manual;
			if ($manual){
				$manual_uri = getValue('sensor_manual', $manual);
			}
		}
		if (isset($data->sensor_stimulus)){
			$stimulus_uri = '';
			$stimulus = $data->sensor_stimulus;
			if ($stimulus){
				$stimulus_uri = getValue('sensor_stimulus', $stimulus);
			}
		}
		$additional_node_index = getAdditionalNodeTriples($sensor_id_uri, $model_uri, $manual_uri, $stimulus_uri, $capabilities, $observed_property_uri, $conditions);
		return $additional_node_index;
	}

	public function __construct()
	{

	}


}

?>