<?php
function get_xml($file_path) {
  libxml_use_internal_errors(true);
  //load the xml SAFELY
  /* Some safety against XML Injection attack
   * see: http://phpsecurity.readthedocs.org/en/latest/Injection-Attacks.html
   * 
   * Attempt a quickie detection of DOCTYPE - discard if it is present (cos it shouldn't be!)
  */
  $xml = file_get_contents($file_path);
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
  //echo $dom->saveXML();
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
  return $dom;
}

