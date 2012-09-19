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
			if (file_exists($file_path . "_elements.json")) {
        $json = file_get_contents($file_path . "_elements.json");
        $json = json_decode($json);
      } else {
      
        include("functions/get_elements_from_schema.php");
        include("functions/get_elements_from_supplied_file.php");
        //$file_path = $_SESSION['uploadedfilepath']; //Sanitise/Check this?
        libxml_use_internal_errors(true);
        
        //Which schema should we use - detect it in the xml!
        $xml = new DOMDocument();
        $xml->load($file_path);
          
        if ($xml->getElementsByTagName("iati-organisation")->length == 0) {
          $schema = "activity";
        } else {
          $schema = "organisation";
        }
        unset($xml); //Destroy this now to #save memory
        
        //Set up an array of all top level elements in the schema
        $all_elements = get_elements_from_schema($schema);
        sort($all_elements);
        
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
           
        //Work out which ones are not there
        $missing_elements = array_diff($all_elements,$unique_found_elements);
        sort($missing_elements);
        
        //Save the result to json
        $elements['found'] = $unique_found_elements;
        $elements['missing'] = $missing_elements;
        $elements['counts'] = $unique_found_elements_count;
        $elements_json = json_encode($elements);
        file_put_contents($file_path . "_elements.json",$elements_json);
        
        //decode again to use it below. !
        $json = json_decode($elements_json); 
      }

		?>
		<h2>Found Elements</h2>
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
                  echo '<li>'. $element .'</li>';
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
