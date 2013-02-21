<?php
/*
 * 
 * name: get_elements_from_supplied_file
 * @param string Path to an xml file
 * @return array An array of all top level iati-activity elements found in the file
 * 
 */
//$found_elements = get_elements_from_supplied_file("../tests/well_formed_PASS.xml");
//$found_elements = array_unique($found_elements);
//print_r($found_elements);
function get_elements_from_supplied_file($file) {	
	$found_elements = array();
		
	if ($xml = file_get_contents($file)) {	
		//print_r($xml);
		//SimpleXMLIterator just runs over the xml and returns the elements found in this file. I think this is just top level
		//which is what I want.
    
    //echo $xml;
    /* Some safety against XML Injection attack
     * see: http://phpsecurity.readthedocs.org/en/latest/Injection-Attacks.html
     * 
     * Attempt a quickie detection of DOCTYPE - discard if it is present (cos it shouldn't be!)
    */
      
    $collapsedXML = preg_replace("/[[:space:]]/", '', $xml);
    //echo $collapsedXML;
    if(preg_match("/<!DOCTYPE/i", $collapsedXML)) {
        //throw new InvalidArgumentException(
       //     'Invalid XML: Detected use of illegal DOCTYPE'
       // );
        //echo "fail";
      return FALSE;
    }
    $loadEntities  = libxml_disable_entity_loader(true);
    $dom = new DOMDocument;
    $dom->loadXML($xml);
    foreach ($dom->childNodes as $child) {
        if ($child->nodeType === XML_DOCUMENT_TYPE_NODE) {
            throw new Exception\ValueException(
                'Invalid XML: Detected use of illegal DOCTYPE'
            );
            libxml_disable_entity_loader($loadEntities);
            return FALSE;
        }
    }
    libxml_disable_entity_loader($loadEntities);
    
    //Iterate over elements now
		if (simplexml_import_dom($dom)) {
			$xmlIterator = new SimpleXMLIterator($xml);
			for( $xmlIterator->rewind(); $xmlIterator->valid(); $xmlIterator->next() ) {
				foreach($xmlIterator->getChildren() as $name => $data) {
				//echo "The $name is '$data' from the class " . get_class($data) . "\n";
				$found_elements[] = $name;
				}
			}
		} else {
			return FALSE;
		}
	}
	return $found_elements;
}
?>
