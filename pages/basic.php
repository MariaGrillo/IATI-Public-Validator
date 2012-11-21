<?php
/* Once we have a file uploaded we can pull this file to give us some basic info about the data
 * Activity files:
 * Version
 * No. Activities
 * Languages found
 * Currencies found
 * Generated date Time
 * Hierarchies
 * 
 * Could add...
 * Encoding
 * Last updated date time on activities
 * Additional Namespaces
*/
?>

	

<?php if( !isset($_SESSION['uploadedfilepath']) ): //If there is not a file in memory then redirect to home page?>
  <?php header('Location: index.php'); ?>
<?php else: ?>
  <?php
    $file_path = $_SESSION['uploadedfilepath']; //Sanitise/Check this?
    
    //We may have already run this check. If so then we can pull the data from the JSON file
    if (file_exists($file_path . "_basic.json")) {
      $json = file_get_contents($file_path . "_basic.json");
      $json = json_decode($json);
    } else {
      //Parse the data from the XML and store it as json     
      libxml_use_internal_errors(true); //Save errors to memory, not the screen

      include ('functions/xml_child_exists.php');
      //Load XML into the DOM to get file info
      $xml = new DOMDocument();
      if ($xml->load($file_path)) {
        if($xml->xmlEncoding == NULL) {
          $encoding = "Encoding: Non declared";
        } else {
          $encoding = $xml->xmlEncoding;
        }
        if ($xml->xmlStandalone == 0) {
          $standalone = "no";
        } elseif ($xml->xmlStandalone == 1) {
          $standalone = "yes";
        } else {
          $standalone = NULL;
        }
        if ($xml->xmlVersion == NULL) {
          $version = "Assumed: XML 1.0";
        } else {
          $version = "Declared: XML " . $xml->xmlVersion;
        }
      }
      //Covert to simpleXML for convenience
      if ($xml = simplexml_import_dom($xml)) {
      //if ($xml = simplexml_load_file($file_path)) {
        $namespaces = $xml->getNamespaces(true);        
        
        //var_dump($namespaces);
        if(!xml_child_exists($xml, "//iati-organisation")) {//ignore organisation files
          $basic=array(); //array to store our values in. This will be encode to json
          $basic['docDeclaration'] = array( "version" => $version,
                                            "standalone" => $standalone,
                                            "encoding" => $encoding
                                            );
          
          
          $basic['generated'] = (string)$xml->attributes()->{'generated-datetime'};
          $basic['version'] = (string)$xml->attributes()->version;
          $basic['activities'] = count($xml->xpath("//iati-activity"));
          //$generated = $xml->attributes()->{'generated-datetime'};
          //$version = $xml->attributes()->version;
          //$activities = count($xml->xpath("//iati-activity"));
          
          $languages = $xml->xpath("//@xml:lang");
          //print_r($languages);
          $basic['languages'] = get_values($languages,"string");
          
          $currencies = $xml->xpath("//@currency");
          $default_currency = $xml->xpath("//@default-currency");
          //print_r($default_currency);
          $currencies = array_merge($default_currency,$currencies);
          //print_r($currencies);
          //$currencies = array_unique($currencies);
          $basic['currencies'] = get_values($currencies,"string");
          
          $hierarchies = $xml->xpath("//@hierarchy");
          $basic['hierarchies'] = get_values($hierarchies,"int");
          
          $last_updated = $xml->xpath("//@last-updated-datetime");
          $last_updated = get_values($last_updated,"string");
          foreach ($last_updated as $time) {
            $times[] = strtotime($time);
          }
          if ($times != NULL) {
            print_r($times);
            sort($times);
            $most_recent = array_pop($times);
            $most_recent = date("Y-m-d",$most_recent) . "T" . date("H:i:s",$most_recent);
            $basic['mostRecent'] = $most_recent;
          } else {
            $basic['mostRecent'] = "Not Found";
          }
          
          $basic['namespaces'] = $namespaces;

          $string = file_get_contents($file_path);
          $encoding = mb_detect_encoding($string,"UTF-8",true);
          if ($encoding != FALSE) {
            $basic['DetectEncoding'] = $encoding;
          } else {
            $basic['DetectEncoding'] = "Encoding: Not detected";
          }

          
          $basic_json = json_encode($basic);
          file_put_contents($file_path . "_basic.json",$basic_json);
          $json = json_decode($basic_json);
      }
    }
}

    ?>
		<h2>Basic Information</h2>
		<ul class="nav nav-tabs" id="myTab">
		  <li class="active"><a href="#status">Overview</a></li>
		  <?php //if ($found_elements): ?>
			<!--<li><a href="#extra">Extra info</a></li>-->
		  <?php //endif; ?>
		</ul>
		 
		<div class="tab-content">
		  <div class="tab-pane active" id="status">
			<div class="row">
				<div class="span9">
					<?php 
						
							echo '<div class="well span2">';
							echo '<h3>IATI Version</h3>';
							if (isset($json->version)) {
								echo $json->version;
							} else {
								echo "<p class=\"text-error\">Not declared</p>";
							}
							echo '</div>';
							
							echo '<div class="well span2">';
							echo '<h3>Generated</h3>';
							if (isset($json->generated)) {
								echo $json->generated;
							} else {
								echo "<p class=\"text-error\">Not declared</p>";
							}	
							echo '</div>';
							
							echo '<div class="well span2">';
							echo '<h3>Activites</h3>';
							if (isset($json->activities)) {
								echo $json->activities;
							} else {
								echo "<p class=\"text-error\">No activities found</p>";
							}	
							echo '</div>';
							
							
									
						/*} else {
							echo '<div class="span4">';
							echo '<pclass="cross>We didn\'t find any top level IATI elements in the XML supplied</p>';
							echo '</div>';
						}*/
							
					?>
				</div><!--span9-->
			</div>
			<div class="row">
				<div class="span9">
					<?php 							
							echo '<div class="well span2">';
							echo '<h3>Languages</h3>';
							if (isset($json->languages) && $json->languages != NULL) {
								echo '<ul>';
								foreach ($json->languages as $language) {
									echo "<li>$language</li>";
								}
								echo '<ul>';
							} else {
								echo "<p class=\"text-error\">No languages found</p>";
							}
							echo '</div>';
							
							echo '<div class="well span2">';
							echo '<h3>Currencies</h3>';
							if (isset($json->currencies)) {
								echo '<ul>';
								foreach ($json->currencies as $currency) {
									echo "<li>$currency</li>";
								}
								echo '<ul>';
							} else {
								echo "<p class=\"text-error\">No currencies found</p>";
							}
							echo '</div>';
							//echo '<div class="well span2" id="example" href="#popover" class="btn btn-large btn-danger" rel="popover" title="A Title" data-content="And here\'s some amazing content. It\'s very engaging. right?"><i class="icon-question-sign" style="text-align:right"></i>';
							echo '<div class="well span2">';
							echo '<h3>Hierarchies</h3>';
							if (isset($json->hierarchies)) {
								echo '<ul>';
								foreach ($json->hierarchies as $hierarchy) {
									echo "<li>$hierarchy</li>";
								}
								echo '<ul>';
							} else {
								echo "<p class=\"text-error\">No currencies found</p>";
							}
							echo '</div>';
									
						/*} else {
							echo '<div class="span4">';
							echo '<pclass="cross>We didn\'t find any top level IATI elements in the XML supplied</p>';
							echo '</div>';
						}*/
							
					?>
				</div><!--span9-->
			</div>
      <div class="row">
        <div class="span9">
            <?php 							
                echo '<div class="well span2">';
                echo '<h3>Document </h3>';
                if ($json->docDeclaration->version == "Assumed: XML 1.0") {
                  echo "<span class=\"text-error text-error-small\">" . $json->docDeclaration->version . "</span><br/>";
                } else {
                  echo  $json->docDeclaration->version . "<br/>";
                }
                if ($json->docDeclaration->encoding == "Encoding: Non declared") {
                  echo "<span class=\"text-error text-error-small\">" . $json->docDeclaration->encoding . "</span><br/>";
                } else {
                  echo "Encoding: " . $json->docDeclaration->encoding . "<br/>";
                }
                if ($json->DetectEncoding=="Encoding: Not detected") {
                  echo "<span class=\"text-error text-error-small\">" . $json->DetectEncoding . "</span><br/>";
                } else {
                  echo "Encoding detected: " . $json->DetectEncoding . "<br/>";
                }
                 if ($json->docDeclaration->standalone != NULL) {
                  echo "<span class=\"text-info text-info-small\">Standalone: " . $json->docDeclaration->standalone . "</span>";
                }
                echo '</div>';
                
                echo '<div class="well span2">';
                echo '<h3>Last Updated</h3>';
                if (isset($json->mostRecent)) {
                  echo $json->mostRecent;
                } else {
                  echo "<p class=\"text-error\">Not found</p>";
                }
                echo '</div>';
                //echo '<div class="well span2" id="example" href="#popover" class="btn btn-large btn-danger" rel="popover" title="A Title" data-content="And here\'s some amazing content. It\'s very engaging. right?"><i class="icon-question-sign" style="text-align:right"></i>';
                echo '<div class="well span2">';
                echo '<h3>Namespaces</h3>';
                if (isset($json->namespaces)) {
                  echo '<ul>';
                  foreach ($json->namespaces as $key=>$value) {
                    echo '<li><a href="' . $value . '">' . $key . '</a></li>';
                  }
                  echo '<ul>';
                } else {
                  echo "<p class=\"text-error\">No currencies found</p>";
                }
                echo '</div>';
                    
              /*} else {
                echo '<div class="span4">';
                echo '<pclass="cross>We didn\'t find any top level IATI elements in the XML supplied</p>';
                echo '</div>';
              }*/
                
            ?>
          </div><!--span9-->
        </div>
		</div>


		</div>
 
<?php endif; ?>
<?php
/*
 * Take an object creted by an xpath expression and returns an array of unique values
 * name: get_values
 * @param $objetcs An object returned from a simplexml xpath query
 * @param $format whether or not the expacted value is a string or integer 
 * @return $values an array of unique values
 * 
 */

function get_values($objects,$format) {
  $values = array();
  foreach ($objects as $object) {
    foreach ($object as $key=>$value) {
      //echo (string)$value;
      if ($format == "int") {
      $values[] = (int)$value;
      } else {
        $values[] = (string)$value;
      }
    }
  }
  $values = array_unique($values);
  //print_r($values);
  return $values;
}

function libxml_display_all_errors() {
    $errors = libxml_get_errors();
    $codes = array();
    print("<table id='errors' class='table-striped'><thead><th>Line</th><th>Severity and code</th><th>Message</th></thead><tbody>");
    $i=1;
    if ($i % 2 == 0) {
		$class = 'even';
	} else {
		$class ='odd';
	}
    foreach ($errors as $error) {
		$code = $error->code; 
		//if (!in_array($code,$codes)) {
			$codes[] = $code;
			if ($i % 2 == 0) {
				$class = 'even';
			} else {
				$class ='odd';
			}
			$i++;
			print libxml_display_error($error,$class);
		//}
    }
    print("</tbody></table>");
    libxml_clear_errors();
}

function libxml_display_error($error,$class) {
	//print_r($error);
    $return = '<tr>';
     $return .= "<td>$error->line</td>";
    switch ($error->level) {
        case LIBXML_ERR_WARNING:
            $return .= "<td class='warning'><b>Warning $error->code</b></td>";
            break;
        case LIBXML_ERR_ERROR:
            $return .= "<td class='error'><b>Error $error->code</b></td>";
            break;
        case LIBXML_ERR_FATAL:
            $return .= "<td class='fatal'><b>Fatal Error $error->code</b></td>";
            break;
    }
    $return .= "<td>" . trim($error->message) . "</td>";
    //if ($error->file) {
       // $return .=    " in <b>" . basename($error->file) . "</b>";
    //}
    $return .= "</tr>";

    return $return;
}
?>
