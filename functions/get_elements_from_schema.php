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
function get_elements_from_schema($xsd) {	
	$all_elements = array('iati-activity');
	$xml = simplexml_load_file($xsd);
	//print_r($xml);
	$activity_element = $xml->xpath("//xsd:schema/xsd:element[@name='iati-activity']/xsd:complexType/xsd:choice/xsd:element");
	foreach ($activity_element as $element) {
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
