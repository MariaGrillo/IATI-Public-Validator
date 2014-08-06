<?php
/*
 * 
 * name: check_iati_version
 * @param $xml - XML DOM returned from the get_xml function (see get_xml.php)
 * @return a valid IATI version number or FALSE
 * 
 */
function check_iati_version ($xml) {
  include './vars.php'; //to bring in $iati_versions. Use this rather than global so that tests.php still works
  //global $iati_versions;
  //Get all iati-activities elements. There should only be one!
  $version = $xml->getElementsByTagName( "iati-activities" );
  //Check there is only one. If there is more then one, return false
  if ($version->length != 1) {
    //echo "FALSE";
    global $error_msg;
    $error_msg .= "more than one iatiactivities element found - cannot check version";
    return FALSE;
    
  }
  //Find the supplied version
  $version = $version->item(0)->getAttribute("version");
  //Check this is a valid version
  if (!in_array($version,$iati_versions)) {
    return FALSE;
  }
  //echo $version;
  return $version;   
  }
?>
