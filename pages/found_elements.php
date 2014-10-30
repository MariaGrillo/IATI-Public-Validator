<?php
/* We're displaying some basic info about which elements have been found in the IATI XML
 * Should work for both activity and organistaion files
*/
?>
<?php if( !isset($_SESSION['uploadedfilepath']) ) :?>
	 <?php header('Location: index.php'); ?>
<?php else: ?>
		<?php
      $file_path = $_SESSION['uploadedfilepath']; //Sanitise/Check this?
      
      //We need to know if we are calling on an activity or organisation schema 
      libxml_use_internal_errors(true);
      
      //Which schema should we use - detect it in the xml!
      $xml = new DOMDocument();
      $xml->load($file_path);

      if ($xml->getElementsByTagName("iati-organisation")->length == 0) {
        $schema = "activity";
      } else {
        $schema = "organisation";
      }
    
      //We need to establish the version of the schema we are working with
      //Note there is a similar routine set in validate-xsd.php, but we can't rely on that being run yet
      //It might be an idea to set this globally much earlier on 
      require_once 'functions/detect_iati_version.php';
      $detected_version = detect_iati_version($xml);
      
      unset($xml); //Destroy this now to #save memory
      
      //Has the version been set by the user to a valid IATI version?
      //echo $_SESSION["version"];
      //if(!isset($version) || $version == "auto") { //We put this in place so tests work! tests.php specifies the version already by this point.
        if (isset($_SESSION["version"]) && in_array($_SESSION["version"],$iati_versions)) {
          //$detected_version = check_iati_version($xml);
          $version = $_SESSION["version"];
          //echo $version;
        } else {
          //..else..lets try to detect the version
          //NB if user has selected auto detect, then we end up here as well
          //Check the detected version is a valid version
          if (!in_array($detected_version,$iati_versions)) {
            $version = $current_version;
            if (isset($detected_version) && $detected_version !=NULL) {
              if (isset($error_msg)) {
                 $error_msg .= '<br/>';
              } else {
                  $error_msg = '';
              }
              $error_msg .= htmlspecialchars($detected_version) . " is not a valid version of the IATI Standard";
            }
          } else {
            $version = $detected_version;
          }
          //echo $version;
        } 
        //If by now we don't have a valid version...
        if(!isset($version) || $version==FALSE) {
          //Use the default. Default is current live version of the standard.
          //echo "true"; echo $current_version;
          $version = $current_version; //$current_version is declared at the top of index.php
        }
      //}
      //echo $version; //die;
      
      //We prefer to use cached results - so if the exist use them
      //Files get a version attached to them so that if we switch versions we regenerate the results
			if (file_exists($file_path . "_elements_" . $version . ".json")) {
        $json = file_get_contents($file_path . "_elements_" . $version . ".json");
        $json = json_decode($json);
      } else {
        //We have to generate the json files for the first time
        
        include("functions/get_elements_from_schema.php");
        include("functions/get_elements_from_supplied_file.php");
        //$file_path = $_SESSION['uploadedfilepath']; //Sanitise/Check this?
        
        
        //Set up an array of all top level elements in the schema
        if (isset($version) && in_array($version,$iati_versions)) { //This should already be safe by now, but doesn't hurt to check
          //This should always be true at this stage, so the else is just a fallback
          $all_elements = get_elements_from_schema($schema, $version);
        } else {
          $all_elements = get_elements_from_schema($schema, $current_version);
        }
        sort($all_elements);
        //print_r($all_elements);
        
        //Set up an array of all the elements found in the supplied file
        $found_elements = get_elements_from_supplied_file($file_path); //this returns all elements
        if ($found_elements) {
         
          $unique_found_elements_count = array_count_values($found_elements);
          $unique_found_elements = array_keys($unique_found_elements_count);
          //$unique_found_elements = array_unique($found_elements);
        } else {
          $unique_found_elements = array();
        }
        sort($unique_found_elements);
        //print_r($unique_found_elements);
           
        //Work out which ones are not there
        $missing_elements = array_diff($all_elements,$unique_found_elements);
        sort($missing_elements);
        
        //Save the result to json
        $elements['found'] = $unique_found_elements;
        $elements['missing'] = $missing_elements;
        $elements['counts'] = $unique_found_elements_count;
        $elements_json = json_encode($elements);
        file_put_contents($file_path . "_elements_" . $version . ".json",$elements_json);
        
        //decode again to use it below. !
        $json = json_decode($elements_json); 
      }

		?>
		<h2>Found Elements</h2>
    <div class="alert alert-info">Testing against the IATI <?php echo ucwords(htmlentities($schema)); ?> Schema version <?php echo htmlentities($version); ?></div>
		<ul class="nav nav-tabs" id="myTab">
		  <li class="active"><a href="#status">Overview</a></li>
		  <?php if ($json->counts): ?>
			<li><a href="#extra">Extra info</a></li>
		  <?php endif; ?>
		</ul>
		 
		<div class="tab-content">
		  <div class="tab-pane active" id="status">
        <div class="row">
          <div class="span9">
            <?php 
              //$transparency_elements = array("conditions","document-link","result");
              if (count($json->found) > 0) {
                //print_r($json->found);
                echo '<div class="span4">';
                echo '<h3>Found ' . count($json->found) . ' element' .  (count($json->found) == 1 ? "":"s") . '</h3>';
                echo '<ul>';
                
                foreach ($json->found as $element) {
                  echo '<li>'. $element .'</li>';
                }
                echo '</ul>';
                echo '</div>';
                
                echo '<div class="span4">';
                echo '<h3>Did not find</h3>';
                echo '<ul>';
                
                foreach ($json->missing as $element) {
                  //if (in_array($element,$transparency_elements)) {
                  //  echo '<li class="text-info-small">'. $element .'</li>';
                  //} else {
                    echo '<li>'. $element .'</li>';
                  //}
                }
                echo '</ul>';
                echo '</div>';
                    
              } else {
                echo '<div class="span4">';
                echo '<pclass="cross>We didn\'t find any top level IATI elements in the XML supplied</p>';
                echo '</div>';
              }
                
            ?>
          </div><!--span9-->
        </div>
      </div><!-- /tab pane-->
      <?php if ($json->counts): ?>
        <div class="tab-pane" id="extra">
          <?php 
            //$element_counts = array_count_values($found_elements);
            $elements = (array)$json->counts; //turn object to array
            arsort($elements); //sort by count value
            print("<table id='errors' class='table-striped'><thead><th>Element</th><th>Count</th></thead><tbody>"); 
            foreach ($elements as $key=>$value) {
              echo '<tr>';
              echo  "<td>$key</td>";
              echo  "<td>$value</td>";
              echo '</tr>';
            }
            print("</tbody></table>");	
           ?>
        </div>
      <?php endif; ?>

  </div><!-- /tab-content-->
 
<?php endif; ?>
