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
	
<?php if( (sizeof($_FILES)==0 || isset($error_msg) ) && !isset($file_path) ) :?>
	<h2>Give me data!</h2>
	<p>Then I can test it for you...</p>
	<div class="row">
		<div class="span9">
			<div class="span3">
				<form action="index.php" method="post" enctype="multipart/form-data"  >
					
					<label for="file">Upload a file of IATI data, </label>
					<input type="file" name="file" id="file" />
					<br />
					<input type="submit" name="submit" value="Submit" />
				</form>
			</div>
			<div class="span1">OR:</div>
			<div class="span4">
				<form method="post" action="index.php">
					<label for="url">Enter an address of an IATI compliant XML file.<br />
					<input type="text" name="url" size="80" /> <input type="submit" value="Submit" />
				</form>
			</div>
		</div>
	</div>

<?php else: ?>
		<?php
			libxml_use_internal_errors(true);
			$sxe = simplexml_load_file($file_path);
			if (!$sxe) {
				$test_result = '<div class="alert alert-error"><strong>Sorry.</strong> This is not a well-formed xml file</div>';
				$error_detail =  "Failed loading XML<br/><ul>";
				foreach(libxml_get_errors() as $error) {
					$error_detail .= "<li>" . $error->message . "</li>";
				}
				$error_detail . "</ul>";
			} else {
				$test_result = '<div class="alert alert-success"><strong>Great.</strong> This is a well formed xml file.</div>';
			}
		?>

		<ul class="nav nav-tabs" id="myTab">
		  <li class="active"><a href="#status">Status</a></li>
		  <li><a href="#file">File Details</a></li>
		  <?php if (isset($error_detail)): ?>
			<li><a href="#extra">Extra info</a></li>
		  <?php endif; ?>
		  <!--<li><a href="#settings">Settings</a></li>-->
		</ul>
		 
		<div class="tab-content">
		  <div class="tab-pane active" id="status">
			  <h3>Status: We got a file!</h3>
				<!--<div class="row">
					<div class="span9">
						<div class="span5">-->
							<h3>Well-formed check:</h3>
							<div>This check tells us if machines are going to be able to read this file.<br/><br/></div>
							<?php echo $test_result; ?>
						<!--</div>
					</div>
				</div>-->
		    </div>
			<div class="tab-pane" id="file">
			  <!--<div class="span3">-->
					<h3>File Details:</h3>
					<div><?php echo $upload_msg; ?></div>
				<!--</div>-->
			</div>
		  <?php if (isset($error_detail)): ?>
			<div class="tab-pane" id="extra">
				<?php echo $error_detail; ?>
			</div>
		  <?php endif; ?>
		  <!--<div class="tab-pane" id="settings">4</div>-->
		</div>
 
<?php endif; ?>
