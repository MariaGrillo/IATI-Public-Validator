<?php
// Returns the dom object for the XML file at the given $file_path
// Performs necessarry security checks for untrusted XML files
function get_xml($file_path) {
  libxml_use_internal_errors(true);
  //load the xml SAFELY
  /* Some safety against XML Injection attack
   * see: http://phpsecurity.readthedocs.org/en/latest/Injection-Attacks.html
  */
  $loadEntities  = libxml_disable_entity_loader(true);
  $dom = new DOMDocument;
  $dom->loadXML(file_get_contents($file_path));
  foreach ($dom->childNodes as $child) {
      if ($child->nodeType === XML_DOCUMENT_TYPE_NODE) {
          throw new Exception (
              'Invalid XML: Detected use of illegal DOCTYPE'
          );
          libxml_disable_entity_loader($loadEntities);
          return FALSE;
      }
  }
  // Reset entity loading to it's previous value
  libxml_disable_entity_loader($loadEntities);
  return $dom;
}

