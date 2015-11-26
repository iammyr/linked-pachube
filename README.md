# What

Generate a RDF description for single sensor nodes.
The returned description is split into sections according to the dependency of the included information, and "where" to store it is suggested. This service is available through either the manually fillable form here
or a RESTful API.

# Why
The unambiguity and schema-indepenedency features that characterize RDF, are the key to enable 
* plug-and-play sensor nodes</li>
* interoperability among different sensors</li>

*inContextSensing* is a system that shows how users with no expertise can benefit of a Linked Data representation to make sense of raw sensor data. This is based on the following considerations: 
* these users are becoming the main consumers of sensor data, but sensors conceptualisation do not consider their point of view; 
* so far, no application dynamically creates Linked Data for sensors, since the linked datasets are usually predefined.

A DEMO paper has been published about this at the International Semantic Web Conference 2011 (ISWC2011):  http://iswc2011.semanticweb.org/fileadmin/iswc/Papers/PostersDemos/iswc11pd_submission_100.pdf (inContext Sensing: LOD augmented sensor data)

*RDF4Sensors* is a generator of RDF descriptions for sensor metadata and sensor observations. It accepts input sent either manually by filling an online form or through a REST API (see "apiDocumentation.php" for the REST API specification). 

*inContextSensing* and *RDF4Sensors* later evolved into the ld4sensors project: https://github.com/iammyr/ld4sensors
