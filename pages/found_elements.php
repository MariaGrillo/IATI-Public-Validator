<?php
/* $error_msg and $upload_msg are both set as global variables in:
 *  functions/fetch-data_from_urls.php
 *  functions/process_files.php
 * 
 * If the file upload is problematic, we issue and alert and present the upload form again
 * If successful we present a tabbed interface with info about the file.
*/
?>
<?php if(isset($error_msg)) :?>
		<div class="alert alert-error">
			<button type="button" class="close" data-dismiss="alert">×</button>
			<strong>Sorry.</strong> We can't test that file.<br/>
			<?php echo $error_msg; ?>
		</div>
<?php endif; ?>	
	

<?php if( !isset($_SESSION['uploadedfilepath']) ) :?>
	 <?php header('Location: index.php'); ?>
<?php else: ?>
		<?php
			include("functions/get_elements_from_schema.php");
			include("functions/get_elements_from_supplied_file.php");
			$file_path = $_SESSION['uploadedfilepath']; //Sanitise/Check this?
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
				$unique_found_elements = array_unique($found_elements);
			} else {
				$unique_found_elements = array();
			}

		?>
		<h2>Found Elements</h2>
		<ul class="nav nav-tabs" id="myTab">
		  <li class="active"><a href="#status">Overview</a></li>
		  <?php if ($found_elements): ?>
			<li><a href="#extra">Extra info</a></li>
		  <?php endif; ?>
		</ul>
		 
		<div class="tab-content">
		  <div class="tab-pane active" id="status">
			<div class="row">
				<div class="span9">
					<?php 
						if (count($unique_found_elements) > 0) {
							echo '<div class="span4">';
							echo '<h3>Found ' . count($unique_found_elements) . ' element' .  (count($unique_found_elements) == 1 ? "":"s") . '</h3>';
							echo '<ul>';
							
							foreach ($all_elements as $element) {
								if (in_array($element,$unique_found_elements)) {
									echo '<li>'. $element .'</li>';
								}
							}
							echo '</ul>';
							echo '</div>';
							
							echo '<div class="span4">';
							echo '<h3>Did not find</h3>';
							echo '<ul>';
							
							foreach ($all_elements as $element) {
								if (!in_array($element,$unique_found_elements)) {
									echo '<li>'. $element .'</li>';
								}
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
		</div>
	<?php if ($found_elements): ?>
	<div class="tab-pane" id="extra">
		<?php 
			$element_counts = array_count_values($found_elements);
			ksort($element_counts);
			print("<table id='errors' class='table-striped'><thead><th>Element</th><th>Count</th></thead><tbody>"); 
			foreach ($element_counts as $key=>$value) {
				echo '<tr>';
				echo  "<td>$key</td>";
				echo  "<td>$value</td>";
				echo '</tr>';
			}
			print("</tbody></table>");	
		 ?>
	</div>
	<?php endif; ?>

		</div>
 
<?php endif; ?>
<?php

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
