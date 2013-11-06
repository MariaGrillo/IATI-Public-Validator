<?php
/* We're displaying some basic info about how well the file complies to the IATI standard
 * Should work for both activity and organistaion files but won't!
*/
?>
<?php if( !isset($_SESSION['uploadedfilepath']) ) :?>
	 <?php header('Location: index.php'); ?>
<?php else: ?>
		<?php
      $file_path = $_SESSION['uploadedfilepath']; //Sanitise/Check this?
			if (file_exists($file_path . "_compliance1.json")) {
        $json = file_get_contents($file_path . "_compliance1.json");
        //echo $json;
        $json = json_decode($json);
      } else {
        require_once 'functions/get_xml.php';
        $dom = get_xml($file_path);
        if($dom === FALSE) return FALSE;

        $xml = simplexml_import_dom($dom);
        //**Test1
        //All activities should have a title
        //Fail is a fail
        //Use xpath to get all activities
        //Loop through and check
        $result["test_1"]["type"] = "PassFail";
        $result["test_1"]["text"] = "All activities should have a title";
        
        $activities = $xml->xpath("//iati-activity");
        foreach ($activities as $activity) {
          if (!$activity->xpath("//title")) {
            $result["test_1"]["result"] = 0;
            //echo (string)$activity->{"iati-identifier"};
            break;  
          }
        }
        if (!isset($result["test_1"]["result"])) {
          $result["test_1"]["result"] = 1;
        }
        
      //**Test 2
        //Titles should be between 20 and 60 characters long 
        //Fail is a warning
        //use xpath to get all titles
        //Loop through and  check
        //This gives us a count of activities
        //This xpath expression finds all titles that are children of an activity
        $min_length = 20;
        $max_length = 60;
        $result["test_2"]["type"] = "Warning";
        $result["test_2"]["text"] = "Titles should be between " . $min_length . " and " . $max_length . " characters long";
        
        $titles = $xml->xpath("//iati-activity//title");
        foreach ($titles as $title) {
          $length = strlen((string)$title);
          //echo $length . "<br/>";
          if ($length < $min_length || $length > $max_length) {
            $result["test_2"]["result"] = 0;
            break;  
          }
          
          //echo $length;
        }
        if (!isset($result["test_2"]["result"])) {
          $result["test_2"]["result"] = 1;
        }
        
        
        //Finished your tests? Then..
        unset($xml); //Destroy this now to #save memory
       
        //Save the results to json
        //print_r($result);
        $result_json = json_encode($result);
        //echo $result_json;
        file_put_contents($file_path . "_compliance1.json",$result_json);
        
        //decode again to use it below. !
        //$json = json_decode($result_json); 
        $json = json_decode($result_json); 
      }
      //Work out passes/fails/warnings
      $passes = count_results($json,"PassFail",1);
      $num_passes = count($passes);
      //print_r($passes);
      $warnings = count_results($json,"Warning",0); //failed warnings
      $num_warnings = count($warnings);
      $all_warnings = count_results($json,"Warning",2); //all warnings passed or failed
      //print_r($warnings);
      $fails = count_results($json,"PassFail",0);
      $num_fails = count($fails);
      //print_r($fails);
      //print_r($all_warnings);

		?>
		<h2>Compliance 1</h2>
		<ul class="nav nav-tabs" id="myTab">
		  <li class="active"><a href="#status">Overview</a></li>
		  <?php if ($num_passes > 0): ?>
			<li><a href="#passed">Passed</a></li>
		  <?php endif; ?>
      <?php if ($num_fails > 0): ?>
			<li><a href="#failed">Failed</a></li>
		  <?php endif; ?>
			<li><a href="#warnings">Warnings</a></li>
		</ul>
		 
		<div class="tab-content">
		  
      <div class="tab-pane active" id="status">
        <div class="row">
          <div class="span9">
            <div class="well span2">
              <h3>Tests passed</h3>
              <span style="text-align:centre;font-size:2em"><?php echo $num_passes; ?></span>
            </div>
            <div class="well span2">
              <h3>Tests failed</h3>
              <span style="text-align:centre;font-size:2em"><?php echo $num_fails; ?></span>
            </div>
            <div class="well span2">
              <h3>Warnings</h3>
              <span style="text-align:centre;font-size:2em"><?php echo $num_warnings; ?></span>
            </div>
          </div>
        </div>
      </div><!-- /tab pane-->
     
     <?php if ($num_passes > 0 ): ?>
        <div class="tab-pane" id="passed">
          <?php 
            print("<table id='errors' class='table-striped'><thead><th>Test</th><th>Result</th></thead><tbody>"); 
            echo show_results($json,$passes);
            print("</tbody></table>");		
          ?>
        </div>
      <?php endif; ?>
      
      <?php if ($num_fails > 0): ?>
        <div class="tab-pane" id="failed">
          <?php 
            print("<table id='errors' class='table-striped'><thead><th>Test</th><th>Result</th></thead><tbody>"); 
            echo show_results($json,$fails);
            print("</tbody></table>");	
          ?>
        </div>
      <?php endif; ?>
      
      <div class="tab-pane" id="warnings">
        <?php 
          print("<table id='errors' class='table-striped'><thead><th>Test</th><th>Result</th></thead><tbody>"); 
          echo show_results($json,$all_warnings,TRUE);
          print("</tbody></table>");		
        ?>
      </div>

  </div><!-- /tab-content-->
 
<?php endif; ?>
<?php
/*
 * 
 * name: count_results
 * @param array $results  An array of result data
 * @param string $type    Type of result we're interested in
 * @param int             0=fail, 1=pass, 2=all Specify if we are interested in passes or fails
 * @return
 * 
 */
      
function count_results($results,$type,$int) {
  $matching_tests = array();
  foreach ($results as $key=>$result) {
    if ($result->type == $type) {
      if ($result->result == $int || $int == 2 ) {
        $matching_tests[] = $key;
      }
    }
  }
  return $matching_tests;
}

/*
 * 
 * name: show_results
 * @param array $results          An array of result data
 * @param array $tests_to_show    An array of test keys e.g. test_1, test_2
 * @param bool  $warnings         A flag. If true then print all tests out, both pass and fail
 * @return
 * 
 */
function show_results($results,$tests_to_show,$warnings = FALSE) {
  $html = "";
  foreach ($results as $key=>$result) {
    if (in_array($key,$tests_to_show)) {
      if ($result->result == 0) {
        $result_value = "Fail";
      } else {
        $result_value = "Pass";
      }
      $html .=  '<tr>';
      $html .= "<td>" . $result->text . "</td>";
      $html .= "<td>" . $result_value . "</td>";;
      $html .= '</tr>';
    }
  }

  return $html;
}
?>
