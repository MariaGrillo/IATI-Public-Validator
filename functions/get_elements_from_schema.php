<?php
/*
 * 
 * name: get_elements_from_schema
 * @param string Path to an xsd file (actually this is ghard coded to the activities file
 * @return array An array of all top level iati-activity elements
 * 
 */

//$all_elements = get_elements_from_schema("../iati-schema/iati-activities-schema.xsd");
//print_r($all_elements);
function get_elements_from_schema($schema, $version) {	
	switch ($schema) {
		case "activity":
			$elements = array('iati-activity'); //We need to include this here, as it's not included in the results using the xpath below
			$xsd = "iati-schemas/$version/iati-activities-schema.xsd";
			$xpath = "//xsd:schema/xsd:element[@name='iati-activity']/xsd:complexType/xsd:choice/xsd:element";
			break;
		case "organisation":
			$elements = array('iati-organisation');
			$xsd = "iati-schemas/$version/iati-organisations-schema.xsd";
			$xpath = "//xsd:schema/xsd:element[@name='iati-organisation']/xsd:complexType/xsd:choice/xsd:element";
			break;
		default:
			break;
		}
	$xml = simplexml_load_file($xsd); //this is fairly safe from XXE attack as we have hardcoded links and files hopefully - trusted source?
	//print_r($xml);
	$elements = $xml->xpath($xpath);
	foreach ($elements as $element) {
		//echo $element->attributes()->ref .PHP_EOL;
		$name = (string)$element->attributes()->ref;
		$all_elements[] = $name;
	}
	/*die;
	$nodeList = $xml->getElementsbytagname('//xsd:schema/xsd:element');

	foreach ($xml->getElementsByTagNameNS('http://www.w3.org/2001/XMLSchema', 'element') as $element) {
		//echo 'local name: ', $element->localName, ', prefix: ', $element->prefix, ', name: ',$element->getAttribute('name'), "\n";
		$name = $element->getAttribute('ref');
		if ($name != NULL) {
			$all_elements[] = $name;
		}
    }*/
return $all_elements;
}
?>
