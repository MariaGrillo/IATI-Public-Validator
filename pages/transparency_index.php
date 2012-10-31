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
			if (file_exists($file_path . "_transparency.json")) {
        $json = file_get_contents($file_path . "_transparency.json");
        //echo $json;
        $json = json_decode($json,true);
      } else {
        include("functions/transparency_calculations.php");
        include ('functions/xml_child_exists.php');
        $corpus  = $file_path;
        $output_dir = "";
        $dir =  "upload";
        include ('functions/pretty_json.php');
        $test_meta_file = "helpers/tests_meta.json"; // A file with our testing rules in.

        //Country to Language map array
        $country_map = array();
        if (($handle = fopen("helpers/country_lang_map.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $country_map[$data[0]] = $data[2];
              }
        }
        
        $path_to_corpus = $dir . "/"; //path to directory //$dir is set in settings.php
        echo $output_dir . '<br/>';
        echo $corpus. '<br/>';
        echo $path_to_corpus. '<br/>';
        echo $file_path. '<br/>';
        format_as_json($output_dir,$corpus,$path_to_corpus,$file_path);
        $json = file_get_contents($file_path . "_transparency.json");
        //echo $json;
        $json = json_decode($json,true);        
      }

		?>
    <?php 
      $test_meta_file = "helpers/tests_meta.json"; // A file with our testing rules in.
      $test_meta = file_get_contents($test_meta_file);
      $test_meta = json_decode($test_meta,true);
      //print_r($test_meta);
      //die;
      $test_map = array("iatiIdentifier" => "1.04",
                        "language" => "1.05",
                        "activityDateStart" => "1.06",
                        "activityDateEnd" => "1.07",
                        "participatingOrgImplementing" => "1.08",
                        "participatingOrgAccountable" => "1.09",
                        "locationText" => "1.10",
                        "locationStructure" => "1.101",
                        "sector" => "1.11",
                        "budget" => "1.12",
                        "transactionTypeCommitment" => "1.15",
                        "transactionTypeDisbursementExpenditure" => "1.16",
                        "transactionTracability" => "1.17",
                        "documentLink" => "1.18",
                        "conditions" => "1.19",
                        "result" => "1.20"
                        );
      function test_meta_lookup($test) {
        global $test_meta;
        //print_r($test_meta); die;
        global $test_map;
        $id = $test_map[$test];
        //echo $id . "<br/>";
        foreach ($test_meta["test"] as $key=>$value) {
          //print_r($key);
          if ($value["id"] == $id) {
            return array("name" => $key,
                          "threshold" => $value["threashold"]
                          );
            break;
          }
        }
      }
    ?>

		<h2>Transparency indicator</h2>
		<ul class="nav nav-tabs" id="myTab">
		  <li class="active"><a href="#status">Overview</a></li>
		</ul>
		 
		<div class="tab-content">
		  
      <div class="tab-pane active" id="status">
        <div class="row">
          <div class="span9">
            <div class="span8">
              <h3>Testing <?php echo $json["activityCount"]; ?> activities at hierarchy <?php echo $json["hierarchy"]; ?></h3>
            </div>
            <div class="span8">
              <table class="table table-striped">  
        <thead>  
          <tr>  
            <th>Test</th>  
            <th>Threshold (%)</th>  
            <th>Score</th>  
            <th>Count</th>  
            <th>Percentage</th>
          </tr>  
        </thead>  
        <tbody>  
          <?php 
            $total = 0;
            foreach ($json["tests"] as $test=>$value) {
               $name_and_threshold = test_meta_lookup($test);
              echo "<tr>";
                echo "<td>" . $name_and_threshold["name"] . "</td>";  
                echo "<td>" . $name_and_threshold["threshold"] . "</td>"; 
                echo "<td>" . $value["score"] . "</td>"; 
                $total += $value["score"];
                echo "<td>" . $value["count"] . "</td>"; 
                echo "<td>" . $value["percentage"] . "</td>";
              echo "</tr>";
            }
          ?>
        </tbody>  
      </table>  
            </div>
            <div class="well span2">
              <h3>Score</h3>
              <span style="text-align:centre;font-size:2em"><?php echo $total; ?></span>
            </div>
          </div>
        </div>
      </div><!-- /tab pane-->
     
    
      
      

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

