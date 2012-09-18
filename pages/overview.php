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
			$file_path = $_SESSION['uploadedfilepath']; //Sanitise/Check this?
			libxml_use_internal_errors(true);
			
			include ('functions/xml_child_exists.php');
			if ($xml = simplexml_load_file($file_path)) {
				if(!xml_child_exists($xml, "//iati-organisation")) {//ignore organisation files
					$generated = $xml->attributes()->{'generated-datetime'};
					$version = $xml->attributes()->version;
					$activities = count($xml->xpath("//iati-activity"));
					$languages = $xml->xpath("//@xml:lang");
					$languages = array_unique($languages);
					$currencies = $xml->xpath("//@currency");
					
					$default_currency = $xml->xpath("//@default-currency");
					//print_r($default_currency);
					$currencies = array_merge($default_currency,$currencies);
					//print_r($currencies);
					$currencies = array_unique($currencies);
					$hierarchies = $xml->xpath("//@hierarchy");
					//print_r($hierarchies);
					$hierarchies = array_unique($hierarchies);
					
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
							if (isset($version)) {
								echo $version;
							} else {
								echo "<p class=\"text-error\">Not declared</p>";
							}
							echo '</div>';
							
							echo '<div class="well span2">';
							echo '<h3>Generated</h3>';
							if (isset($generated)) {
								echo $generated;
							} else {
								echo "<p class=\"text-error\">Not declared</p>";
							}	
							echo '</div>';
							
							echo '<div class="well span2">';
							echo '<h3>Activites</h3>';
							if (isset($activities)) {
								echo $activities;
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
							if (isset($languages) && $languages != NULL) {
								echo '<ul>';
								foreach ($languages as $language) {
									echo "<li>$language</li>";
								}
								echo '<ul>';
							} else {
								echo "<p class=\"text-error\">No languages found</p>";
							}
							echo '</div>';
							
							echo '<div class="well span2">';
							echo '<h3>Currencies</h3>';
							if (isset($currencies)) {
								echo '<ul>';
								foreach ($currencies as $currency) {
									echo "<li>$currency</li>";
								}
								echo '<ul>';
							} else {
								echo "<p class=\"text-error\">No currencies found</p>";
							}
							echo '</div>';
							echo '<div class="well span2">';
							echo '<h3>Hierarchies</h3>';
							if (isset($hierarchies)) {
								echo '<ul>';
								foreach ($hierarchies as $hierarchy) {
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
		</div>


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
