<?php


require_once '../resources/TripleCreator.php';
require_once '../resources/AbstractResource.php';



class IntrinsicSiderResource{


	public function handleGet($data){
		if (!$data
		|| !isset($data->sensor_id)
		|| !isset($data->uom)
		|| !isset($data->observed_property)
		|| !isset($data->observed_value)){
			return null;
		}
		$intrinsic_index = array();
		$sensor_id = str_ireplace(" ", "_", $data->sensor_id);
		$arr = split(" - ", $data->uom);
		if (sizeof($arr) == 2){
			$uom_uri = getValue('uom', $arr[0]);
			$uom_symbol = $arr[1];
		}else{
			$uom_uri = getValue('uom', $_POST['uom']);
			$uom_symbol = '';
		}
		$observed_property_uri = getValue("property", $data->observed_property);
		$observed_value = $data->observed_value;
		$timestamp = $data->timestamp;
		$sensor_id_uri = AbstractResource::$resource_base.'sensor/'.$sensor_id;
		$intrinsic_index += getIntrinsicTriples($sensor_id_uri, $observed_property_uri, $uom_symbol, $uom_uri, $observed_value, $timestamp);
		 
		//		$temp = array($sensor_id_uri => $intrinsic_index[$sensor_id_uri]);
		unset($intrinsic_index[$sensor_id_uri]);

		return $intrinsic_index;
	}


	public function __construct()
	{

	}


}

?>