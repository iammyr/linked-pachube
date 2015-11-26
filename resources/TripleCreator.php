<?php

function getPrintableCode($data){
	$data_4html = str_ireplace("<", "&lt", $data);
	$data_4html = str_ireplace(">", "&gt", $data_4html);
	$data_4html = '<pre><code>'.$data_4html.'</pre></code>';
	return $data_4html;
}

function getRDFSerialization($serialization_language, $index){

	require_once 'lib/semsol-arc2/ARC2.php';
	$ser = '';
	$serialization_language = strtolower($serialization_language);
	//	echo "serial lang = ".$serialization_language;
	if (isset($serialization_language)){
		$arc_ser = ARC2::getRDFXMLParser();
		if (strpos('xml', $serialization_language) !== false){
			//				echo ' in xml ';
			$ser = $arc_ser->toRDFXML($index);
		}else if (strpos('json', $serialization_language) !== false){
			//				echo ' in json ';
			$ser = $arc_ser->toRDFJSON($index);
		}else if (strpos('turtle', $serialization_language) !== false){
			//				echo ' in turtle ';
			$ser = $arc_ser->toTurtle($index);
		}else{
			//			echo ' in default';
			$ser = $arc_ser->toNTriples($index);
		}
		$ser = getPrintableCode($ser);
		//		echo $ser;
	}
	return $ser;
}

function getIntrinsicTriples($sensor_id_uri, $observed_property_uri, $uom_symbol, $uom_uri, $observed_value, $timestamp){
	$retarr = array();
	$retarr= getSensorTriple($sensor_id_uri);
	$retarr= getPropertyTriple($retarr, $observed_property_uri, $sensor_id_uri);
	$retarr= getUnitOfMeasurementTriple($retarr, $observed_property_uri, $uom_uri, $uom_symbol, $sensor_id_uri);
	$retarr = getValueTriple($retarr, $observed_value, $sensor_id_uri, $timestamp);

	return $retarr;
}

function getAdditionalNodeTriples($sensor_id_uri, $model_uri, $manual_uri, $stimulus_uri, $capabilities, $observed_property_uri, $conditions){
	$retarr = array();
	$retarr =  getModelTriple($retarr, $model_uri, $sensor_id_uri);
	$retarr = getManualTriple($retarr, $manual_uri, $sensor_id_uri);
	$retarr = getStimulusTriple($retarr, $stimulus_uri, $sensor_id_uri, $observed_property_uri);
	$retarr = getCapabilitiesTriple($retarr, $capabilities, $sensor_id_uri, $conditions);

	return $retarr;
}


function getAdditionalOwnerTriples($sensor_id_uri, $sensor_owner, $sensor_publisher, $sensor_location, $foi, $platform_uri, $history_uri){
	$retarr = array();
	$retarr = getOwnerTriple($retarr, $sensor_id_uri, $sensor_owner);
	$retarr =	getPublisherTriple($retarr, $sensor_id_uri, $sensor_publisher);
	$retarr =	getLocationTriple($retarr, $sensor_id_uri, $sensor_location);
	$retarr = getFOITriple($retarr, $sensor_id_uri, $foi);
	$retarr = getPlatformTriple($retarr, $sensor_id_uri, $platform_uri);
	$retarr = getHistoryTriple($retarr, $sensor_id_uri, $history_uri);

	return $retarr;
}

function getPropertyTriple($arr, $property_uri, $sensor_id_uri){
	if (isset($arr[$property_uri]) && $arr[$property_uri]){
		$arr[$property_uri] += array(
		AbstractResource::$rdfs.'type'
		=> array(array('value'=>AbstractResource::$ssn.'Property', 'type'=>'uri'))
		);
	}else{
		$arr += array(
		$property_uri
		=>
		array(
		AbstractResource::$rdfs.'type'
		=> array(array('value' => AbstractResource::$ssn.'Property', 'type'=>'uri'))
		));
	}
	if (isset($arr[$sensor_id_uri]) && $arr[$sensor_id_uri]){
		$arr[$sensor_id_uri] += array(
		AbstractResource::$ssn.'observedProperty'
		=>
		array(array('value' => $property_uri, 'type'=>'uri'))
		);
	}else{
		$arr += array($sensor_id_uri => array(
		AbstractResource::$ssn.'observedProperty'
		=>
		array(array('value' => $property_uri, 'type'=>'uri'))
		));
	}
	return $arr;
}

function getValueTriple($arr, $value, $sensor_id_uri, $timestamp){
	if (isset($arr[$sensor_id_uri]) && $arr[$sensor_id_uri]){
		$arr[$sensor_id_uri] += array(
		AbstractResource::$dul.'hasValue'
		=>
		array(array('value' => $value, 'type'=>'literal'))
		);
		$arr[$sensor_id_uri] += array(
		AbstractResource::$dcterms.'date'
		=>
		array(array('value' => $timestamp, 'type'=>'literal'))
		);
	}else{
		$arr += array($sensor_id_uri => array(
		AbstractResource::$dul.'hasValue'
		=>
		array(array('value' => $value, 'type'=>'literal'))
		));
		$arr += array($sensor_id_uri => array(
		AbstractResource::$dcterms.'date'
		=>
		array(array('value' => $timestamp, 'type'=>'literal'))
		));
	}
	return $arr;
}

function getUnitOfMeasurementTriple($arr, $property_uri, $uom_uri, $uom_symbol, $sensor_id_uri = 'default'){
	if (isset($arr[$uom_uri]) && $arr[$uom_uri]){
		$arr[$uom_uri] += array(
		AbstractResource::$rdfs.'type'
		=> array(array('value' => AbstractResource::$uom.'UnitOfMeasurement', 'type'=>'uri'))
		);
	}else{
		$arr += array(
		$uom_uri
		=>
		array(
		AbstractResource::$rdfs.'type'
		=> array(array('value' => AbstractResource::$uom.'UnitOfMeasurement', 'type'=>'uri'))
		)
		);
	}
	if ($uom_symbol){
		$arr[$uom_uri] +=
		array(AbstractResource::$uom.'prefSymbol'
		=> array(array('value' => $uom_symbol, 'type'=>'literal'))
		);
	}
	if (isset($arr[$property_uri]) && $arr[$property_uri]){
		$arr[$property_uri] += array(
		AbstractResource::$uom.'measuredIn'
		=>
		array(array('value' => $uom_uri, 'type'=>'uri'))
		);
	}else{
		$arr += array(
		$property_uri
		=>
		array(
		AbstractResource::$uom.'measuredIn'
		=>
		array(array('value' => $uom_uri, 'type'=>'uri'))
		)
		);
	}
	if (strcasecmp($sensor_id_uri, 'default') !== 0){
		if (isset($arr[$sensor_id_uri]) && $arr[$sensor_id_uri]){
			$arr[$sensor_id_uri] += array(
			AbstractResource::$ontology_ns.'uomInUse'
			=>
			array(array('value' => $uom_uri, 'type'=>'uri'))
			);
		}else{
			$arr += array($sensor_id_uri => array(
			AbstractResource::$ontology_ns.'uomInUse'
			=>
			array(array('value' => $uom_uri, 'type'=>'uri'))
			));
		}
	}
	return $arr;
}

function getSensorTriple($sensor_id_uri){
	$arr = array($sensor_id_uri
	=>
	array(
	AbstractResource::$rdfs.'type'
	=> array(array('value' => AbstractResource::$ssn.'Sensor', 'type'=>'uri'))
	));
	return $arr;
}


function getModelTriple($arr, $model_uri, $sensor_id_uri){
	if ($model_uri){
		if (isset($arr[$model_uri])&& $arr[$model_uri]){
			$arr[$model_uri] += array(
			AbstractResource::$rdfs.'subClassOf'
			=> array(array('value'  => AbstractResource::$ssn.'Sensor', 'type' => 'uri'))
			);
		}else{
			$arr += array(
			$model_uri
			=>
			array(
			AbstractResource::$rdfs.'subClassOf'
			=> array(array('value'  => AbstractResource::$ssn.'Sensor', 'type' => 'uri'))
			));
		}
		if (isset($arr[$sensor_id_uri])&& $arr[$sensor_id_uri]){
			$arr[$sensor_id_uri][AbstractResource::$rdfs.'type'] =
			array(array('value'  => $model_uri, 'type' => 'uri'));
		}else{
			$arr += array($sensor_id_uri
			=>
			array(
			AbstractResource::$rdfs.'type'
			=> array(array('value'  => $model_uri, 'type' => 'uri'))
			))
			;
		}

	}
	return $arr;
}

function getManualTriple($arr, $manual_uri, $sensor_id_uri){
	//	$retarr = array();
	if ($manual_uri){
		if (isset($arr[$sensor_id_uri])&& $arr[$sensor_id_uri]){
			$arr[$sensor_id_uri] += array(
			AbstractResource::$dul.'hasObject'
			=> array(array('value'  => $manual_uri, 'type' => 'uri'))
			);
		}else{
			$arr += array( $sensor_id_uri => array(
			AbstractResource::$dul.'hasObject'
			=> array(array('value'  => $manual_uri, 'type' => 'uri'))
			));
		}
		if (isset($arr[$manual_uri])&& $arr[$manual_uri]){
			$arr[$manual_uri] +=
			array(
			AbstractResource::$rdfs.'type'
			=> array(array('value'  => AbstractResource::$ssn.'SensorDataSheet', 'type' => 'uri'))
			);
		}else{
			$arr +=array(
			$manual_uri
			=>
			array(
			AbstractResource::$rdfs.'type'
			=> array(array('value'  => AbstractResource::$ssn.'SensorDataSheet', 'type' => 'uri'))
			))
			;
		}
	}
	return $arr;
}

function getStimulusTriple($arr, $stimulus_uri, $sensor_id_uri, $property_uri){
	if ($stimulus_uri){
		if (isset($arr[$sensor_id_uri])&& $arr[$sensor_id_uri]){
			$arr[$sensor_id_uri] += array(
			AbstractResource::$ssn.'detects'
			=>
			array(array('value'  => $stimulus_uri, 'type' => 'uri'))
			);
		}else{
			$arr += array($sensor_id_uri
			=>
			array(
			AbstractResource::$ssn.'detects'
			=>
			array(array('value'  => $stimulus_uri, 'type' => 'uri'))
			));
		}
		if (isset($arr[$stimulus_uri])&& $arr[$stimulus_uri]){
			$arr[$stimulus_uri] += array(
			AbstractResource::$rdfs.'type'
			=>
			array(array('value'  => AbstractResource::$ssn.'Stimulus', 'type' => 'uri')),

			AbstractResource::$ssn.'isProxyFor'
			=>
			array(array('value'  => $property_uri, 'type' => 'uri'))
			);
		}else{
			$arr += array($stimulus_uri
			=>
			array(
			AbstractResource::$rdfs.'type'
			=>
			array(array('value'  => AbstractResource::$ssn.'Stimulus', 'type' => 'uri')),

			AbstractResource::$ssn.'isProxyFor'
			=>
			array(array('value'  => $property_uri, 'type' => 'uri'))
			));
		}
	}
	return $arr;
}

function getCapabilitiesTriple($retarr, $capabilities, $sensor_id_uri, $conditions){
	if (count($capabilities) > 0 && $capabilities[0]){
		$capab_base = AbstractResource::$resource_base.'measurement_capability/capab';
		$sensor_id = '';
		$model_id = '';
		$model_uri = '';
		//retrieve sensor_id and (if it exists) model_id
		$last_slash = strrpos($sensor_id_uri, '/');
		if ($last_slash !== false){
			$sensor_id = substr($sensor_id_uri, $last_slash+1);
		}else{
			$sensor_id = 'NN_Error';
		}
		//if the sensor model exists, retrieve it
		$sensorset = isset($retarr[$sensor_id_uri])&& $retarr[$sensor_id_uri];
		if ($sensorset){
			$sensor_types = $retarr[$sensor_id_uri][AbstractResource::$rdfs.'type'];
			$tot = sizeof($sensor_types);
			for ($ind=0; $ind<$tot && !$model_id; $ind++){
				$model_uri = $sensor_types[$ind]['value'];
				if (strcmp($model_uri, AbstractResource::$ssn.'Sensor') !== 0){
					$last_slash = strrpos($model_uri, '/');
					if ($last_slash !== false){
						$model_id = substr($model_uri, $last_slash+1);
					}else{
						$model_id = 'NN_Error';
					}
				}
			}
		}

		//--Capabilities URI creation
		if ($model_id){
			$capab_class_uri = $capab_base.$model_id;
		}else{
			$capab_class_uri = $sensor_id_uri.'/capabilities';
		}
		$capab_instance_uri = $capab_class_uri.'_'.$sensor_id;
		//--Capabilities class triple creation
		$retarr += array(
		$capab_class_uri
		=>
		array(
		AbstractResource::$rdfs.'subClassOf'
		=>
		array(array('value'  => AbstractResource::$ssn.'MeasurementCapability', 'type' => 'uri'))
		));
		//--if the sensor model exists, create restriction on its hasMeasurementCapabilities
		if ($model_uri){
			$retarr[$model_uri] += array(
			AbstractResource::$rdfs.'subClassOf'
			=>
			array(array('value'  => 'bnode'.(AbstractResource::$bnode_count++), 'type' => 'bnode'))
			);
			$retarr += array(
			$count
			=>
			array(
			AbstractResource::$rdf.'type'
			=>
			array(array('value'  => AbstractResource::$owl.'Restriction', 'type' => 'uri')),
			AbstractResource::$owl.'onProperty'
			=>
			array(array('value'  => AbstractResource::$ssn.'hasMeasurementCapabilities', 'type' => 'uri')),
			AbstractResource::$owl.'someValuesFrom'
			=>
			array(array('value'  => $capab_class_uri, 'type' => 'uri')),
			)
			);
		}

		//--Instance(instance1) of the Capabilities class triple creation
		$retarr += array(
		$capab_instance_uri
		=>
		array(
		AbstractResource::$rdfs.'type'
		=>
		array(array('value'  => $capab_class_uri, 'type' => 'uri'))
		));
		//--<sensor_instance hasMeasurementCapabilities instace1> triple creation
		if ($sensorset){
			$retarr[$sensor_id_uri] += array(
			AbstractResource::$ssn.'hasMeasurementCapability'
			=>
			array(array('value'  => $capab_instance_uri, 'type' => 'uri'))
			);
		}else{
			$retarr += array($sensor_id_uri
			=>
			array(
			AbstractResource::$ssn.'hasMeasurementCapability'
			=>
			array(array('value'  => $capab_uri, 'type' => 'uri'))
			));
		}
		//--Capabilities class restriction triple creation
		$cond_count= 1;
		foreach ($capabilities as $capab) {
			$input = extractPropUomRange($capab);
			$uom_uri = getValue('uom', $input['uom']);
			$prop_uri = getValue('property', $input['prop']);
			$retarr = getUnitOfMeasurementTriple($retarr, $prop_uri, $uom_uri, $input['uom_symbol']);

			$retarr += array(
			$prop_uri
			=>
			array(
			AbstractResource::$rdfs.'type'
			=>
			array(array('value'  => AbstractResource::$ssn.'MeasurementProperty', 'type' => 'uri'))
			));
			$retarr = getRestrictionTriple($retarr, $capab_class_uri, false, $prop_uri, $uom_uri, $input['max'], $input['min']);

			if (isset($conditions[$cond_count]) && count($conditions[$cond_count]) > 0 && $conditions[$cond_count] ){
				foreach ($conditions[$cond_count] as $cond){
					$cond_input = extractPropUomRange($cond);
					$cond_uom_uri = getValue('uom', $cond_input['uom']);
					$cond_prop_uri = getValue('property', $cond_input['prop']);
					$retarr = getUnitOfMeasurementTriple($retarr, $cond_prop_uri, $cond_uom_uri, $cond_input['uom_symbol']);

					$retarr += array(
					$cond_prop_uri
					=>
					array(
					AbstractResource::$rdfs.'type'
					=>
					array(array('value'  => AbstractResource::$ssn.'MeasurementProperty', 'type' => 'uri'))
					));
					$retarr = getRestrictionTriple($retarr, $capab_class_uri, true, $cond_prop_uri, $cond_uom_uri, $cond_input['max'], $cond_input['min']);
				}
				$cond_count++;
			}
		}
	}
	return $retarr;
}

/**
 *
 * @param string $capab in the form <Property - min/max - unitOfMeasurement,symbol>
 */
function extractPropUomRange($capab){
	$propr = '';
	$uom='';
	$uom_symbol='';
	$min='';
	$max='';
	$splitarr = split(' - ', $capab);
	if (sizeof($splitarr) === 3){
		$splitarr1 = split('/', $splitarr[1]);
		if (sizeof($splitarr1) === 2){
			$prop = $splitarr[0];
			$uom = $splitarr[2];

			$min = $splitarr1[0];
			$max = $splitarr1[1];

			$uom_split = split(",", $uom);
			if (sizeof($uom_split) === 2){
				$uom = $uom_split[0];
				$uom_symbol = $uom_split[1];
			}else{
				$uom_symbol = '';
			}
		}
	}
	$retarr = array('uom'=>$uom, 'prop'=>$prop, 'uom_symbol'=>$uom_symbol, 'min'=>$min, 'max'=>$max);
	return $retarr;
}

function getRestrictionTriple($retarr, $capab_class_uri, $isCondition, $property, $uom, $max, $min){
	$restriction_bnode = 'bnode'.(AbstractResource::$bnode_count++);
	$bnode1 = 'bnode'.(AbstractResource::$bnode_count++);
	$bnode2 = 'bnode'.(AbstractResource::$bnode_count++);
	$bnode3 = 'bnode'.(AbstractResource::$bnode_count++);
	$bnode4 = 'bnode'.(AbstractResource::$bnode_count++);
	$bnode5 = 'bnode'.(AbstractResource::$bnode_count++);
	$bnode6 = 'bnode'.(AbstractResource::$bnode_count++);
	$bnode7 = 'bnode'.(AbstractResource::$bnode_count++);

	$retarr[$capab_class_uri] += array(
	AbstractResource::$rdfs.'subClassOf'
	=>
	array(array('value'  => $restriction_bnode, 'type' => 'bnode'))
	);
	$retarr += array(
	$restriction_bnode
	=>
	array(
	AbstractResource::$rdf.'type'
	=>
	array(array('value'  => AbstractResource::$owl.'Restriction', 'type' => 'uri')),
	AbstractResource::$owl.'onProperty'
	=>
	array(array('value'  => $isCondition?AbstractResource::$ssn.'inCondition':AbstractResource::$ssn.'hasMeasurementProperty', 'type' => 'uri')),
	AbstractResource::$owl.'someValuesFrom'
	=>
	array(array('value'  => $bnode1, 'type' => 'bnode'))
	),
	$bnode1
	=>
	array(
	AbstractResource::$rdf.'type'
	=>
	array(array('value'  => AbstractResource::$owl.'Class', 'type' => 'uri')),
	AbstractResource::$owl.'intersectionOf'
	=>
	array(array('value'  => $property, 'type' => 'uri'),
	array('value'  => $bnode2, 'type' => 'bnode'))
	),
	$bnode2
	=> array(
	AbstractResource::$rdf.'type'
	=>
	array(array('value'  => AbstractResource::$owl.'Restriction', 'type' => 'uri')),
	AbstractResource::$owl.'onProperty'
	=>
	array(array('value'  => AbstractResource::$ssn.'hasValue', 'type' => 'uri')),
	AbstractResource::$owl.'someValuesFrom'
	=>
	array(array('value'  => $bnode3, 'type' => 'bnode'))
	),
	$bnode3
	=> array(
	AbstractResource::$rdf.'type'
	=>
	array(array('value'  => AbstractResource::$owl.'Class', 'type' => 'uri')),
	AbstractResource::$owl.'intersectionOf'
	=>
	array(array('value'  => AbstractResource::$ssn.'ValueRange', 'type' => 'uri'),
	array('value'  => $bnode4, 'type' => 'bnode'),
	array('value'  => $bnode5, 'type' => 'bnode'),
	array('value'  => $bnode6, 'type' => 'bnode'))
	),
	$bnode4
	=> array(
	AbstractResource::$rdf.'type'
	=>
	array(array('value'  => AbstractResource::$owl.'Restriction', 'type' => 'uri')),
	AbstractResource::$owl.'onProperty'
	=>
	array(array('value'  => AbstractResource::$uom.'measuredIn', 'type' => 'uri')),
	AbstractResource::$owl.'someValuesFrom'
	=>
	array(array('value'  => $bnode7, 'type' => 'bnode'))
	),
	$bnode7
	=> array(
	AbstractResource::$rdf.'type'
	=>
	array(array('value'  => AbstractResource::$owl.'Class', 'type' => 'uri')),
	AbstractResource::$owl.'oneOf'
	=>
	array(array('value'  => $uom, 'type' => 'uri')),
	),
	$bnode5
	=> array(
	AbstractResource::$rdf.'type'
	=>
	array(array('value'  => AbstractResource::$owl.'Restriction', 'type' => 'uri')),
	AbstractResource::$owl.'onProperty'
	=>
	array(array('value'  => AbstractResource::$ontology_ns.'hasMinimumValue', 'type' => 'uri')),
	AbstractResource::$owl.'hasValue'
	=>
	array(array('value'  => $min, 'type' => 'literal'))
	),
	$bnode6
	=> array(
	AbstractResource::$rdf.'type'
	=>
	array(array('value'  => AbstractResource::$owl.'Restriction', 'type' => 'uri')),
	AbstractResource::$owl.'onProperty'
	=>
	array(array('value'  => AbstractResource::$ontology_ns.'hasMaximumValue', 'type' => 'uri')),
	AbstractResource::$owl.'hasValue'
	=>
	array(array('value'  => $max, 'type' => 'literal'))
	)
	);

	$retarr[$capab_class_uri] += array(
	AbstractResource::$rdfs.'subClassOf'
	=>
	array(array('value'  => $restriction_bnode, 'type' => 'bnode'))
	);
	return $retarr;
}

function getOwnerTriple($arr, $sensor_id_uri, $sensor_owner){
	if ($sensor_owner){
		if (isset($arr[$sensor_id_uri])&& $arr[$sensor_id_uri]){
			$arr[$sensor_id_uri] += array(
			AbstractResource::$ontology_ns.'owner'
			=>
			array(array('value'  => $sensor_owner, 'type' => 'literal')));
		}else{
			$arr += array(
			$sensor_id_uri
			=>
			array(
			AbstractResource::$ontology_ns.'owner'
			=>
			array(array('value'  => $sensor_owner, 'type' => 'literal'))));
		}
	}
	return $arr;
}

function getPublisherTriple($arr, $sensor_id_uri, $sensor_publisher){
	if ($sensor_publisher){
		if (isset($arr[$sensor_id_uri])&& $arr[$sensor_id_uri]){
			$arr[$sensor_id_uri] += array(
			AbstractResource::$dcelement.'publisher'
			=>
			array(array('value'  => $sensor_publisher, 'type' => 'literal')));
		}else{
			$arr += array(
			$sensor_id_uri
			=>
			array(
			AbstractResource::$dcelement.'publisher'
			=>
			array(array('value'  => $sensor_publisher, 'type' => 'literal'))));
		}
	}
	return $arr;
}

function getLocationTriple($arr, $sensor_id_uri, $sensor_location){
	if ($sensor_location){
		if (isset($arr[$sensor_id_uri])&& $arr[$sensor_id_uri]){
			$arr[$sensor_id_uri] += array(
			AbstractResource::$dul.'hasLocation'
			=>
			array(array('value'  => $sensor_location, 'type' => 'literal')));
		}else{
			$arr += array(
			$sensor_id_uri
			=>
			array(
			AbstractResource::$dul.'hasLocation'
			=>
			array(array('value'  => $sensor_location, 'type' => 'literal'))));
		}
	}
	return $arr;
}

function getFOITriple($arr, $sensor_id_uri, $foi){
	if ($foi){
		if (isset($arr[$sensor_id_uri])&& $arr[$sensor_id_uri]){
			$arr[$sensor_id_uri] += array(
			AbstractResource::$ssn.'featureOfInterest'
			=>
			array(array('value'  => $foi, 'type' => 'literal')));
		}else{
			$arr += array(
			$sensor_id_uri
			=>
			array(
			AbstractResource::$ssn.'featureOfInterest'
			=>
			array(array('value'  => $foi, 'type' => 'literal'))));
		}
	}
	return $arr;
}

function getPlatformTriple($arr, $sensor_id_uri, $platform_uri){
	if ($platform_uri){
		if (isset($arr[$sensor_id_uri])&& $arr[$sensor_id_uri]){
			$arr[$sensor_id_uri]  += array(
			AbstractResource::$ssn.'attachedPlatform'
			=>
			array(array('value'  => $platform_uri, 'type' => 'uri')));
		}else{
			$arr += array($sensor_id_uri =>
			array(
			AbstractResource::$ssn.'attachedPlatform'
			=>
			array(array('value'  => $platform_uri, 'type' => 'uri'))));
		}
	}
	return $arr;
}

function getHistoryTriple($arr, $sensor_id_uri, $history_uri){
	$retarr = array();
	if ($history_uri){
		if (isset($arr[$sensor_id_uri])&& $arr[$sensor_id_uri]){
			$arr[$sensor_id_uri]  += array(
			AbstractResource::$ontology_ns.'historicalArchive'
			=>
			array(array('value'  => $history_uri,  'type' => 'uri')));
		}else{
			$arr += array(
			$sensor_id_uri
			=>
			array(
			AbstractResource::$ontology_ns.'historicalArchive'
			=>
			array(array('value'  => $history_uri, 'type' => 'uri'))
			));
		}
	}
	return $arr;
}

?>