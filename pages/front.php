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
	
<?php if( (sizeof($_FILES)==0 && !isset($_SESSION['uploadedfilepath']) || isset($error_msg) ) || !isset($file_path) ) :?>
	<p class="lead">Test IATI XML</p>
	<div class="row">
		<div class="span9">
			<ul class="nav nav-tabs" id="myTab">
				<li class="active"><a href="#status">Upload</a></li>
				<li><a href="#file">Fetch file from web</a></li>
				<li><a href="#extra">Paste XML</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="status">
					<div class="span6">
						<form action="index.php" method="post" enctype="multipart/form-data"  >
							<legend>Upload File</legend>
							<label for="file">File:</label>
							<input type="file" name="file" class="span5"/>
							<span class="help-block">Upload an XML file of IATI data.</span>
							<!--<input type="submit" name="submit" value="Submit" />-->
							<button type="submit" class="btn btn-primary">Upload</button>
						</form>
					</div>
				</div>
				<div class="tab-pane" id="file">
					<!--<div class="span1">OR:</div>-->
					<div class="span6">
						<form method="post" action="index.php">
							<legend>Fetch data from the Web</legend>
							<label for="url">URL of file:<br />
							<input type="text" placeholder="Paste URL here" name="url" class="span5" /> 
							<span class="help-block">Enter an address of an IATI compliant XML file.</span>
							<button type="submit" class="btn btn-primary">Fetch Data</button>
							<!--<input type="submit" value="Submit" />-->
						</form>
					</div>
				</div>
				<div class="tab-pane" id="extra">
					<div class="span6">
						<form action="index.php" method="post">
							<legend>Paste XML</legend>
							<label for="paste">XML:</label>
							<textarea rows="8" class="span5" name="paste" id="paste"></textarea>
							<span class="help-block">Paste your XML here.</span>
							<!--<input type="submit" name="submit" value="Submit" />-->
							<button type="submit" class="btn btn-primary">Submit</button>
						</form>
					</div>	
				</div>	
			</div>	
		</div>
	</div>
	<!--<hr>
	<div>OR:</div>
	<div class="row">
		<div class="span10" style="margin-left:0">
			<div class="span8">
				<form action="index.php" method="post">
					<legend>Paste XML</legend>
					<label for="file">XML:</label>
					<textarea rows="8" class="span5" name="paste" id="paste"></textarea>
					<span class="help-block">Paste your XML here.</span>
					<!--<input type="submit" name="submit" value="Submit" />-->
					<!--<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>			
		</div>
	</div>-->

<?php else: ?>
		<?php
			if (isset($_SESSION['uploadedfilepath'])) {
				$file_path = $_SESSION['uploadedfilepath']; //Sanitise/Check this?
			}
			libxml_use_internal_errors(true);
			$sxe = simplexml_load_file($file_path);
			if (!$sxe) {
				$_SESSION['wellformed'] = FALSE;
				$test_result = '<div class="alert alert-error"><strong>Sorry.</strong> This is not a well-formed xml file</div>';
				$error_detail =  "Failed loading XML<br/><ul>";
				foreach(libxml_get_errors() as $error) {
					$error_detail .= "<li>" . $error->message . "</li>";
				}
				$error_detail . "</ul>";
			} else {
				$_SESSION['wellformed'] = TRUE;
				$test_result = '<div class="alert alert-success"><strong>Great.</strong> This is a well formed xml file.</div>';
			}
		?>
		<h2>Well Formed XML test</h2>
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
			  <!--<h3>Status: We got a file!</h3>--?
				<!--<div class="row">
					<div class="span9">
						<div class="span5">-->
							<!--<h3>Well-formed check:</h3>-->
							<?php echo $test_result; ?>
							<div>This check tells us if machines are going to be able to read this file.<br/><br/></div>
							<?php if (isset($error_detail)): ?>
								<div id="intext">
									See <a href="#extra">Extra info</a> for details about the errors.
								</div>
								See <a href="<?php echo $host; ?>common_errors.php">Common errors</a> for help in understanding the errors.
							<?php endif; ?>
							
						<!--</div>
					</div>
				</div>-->
		    </div>
			<div class="tab-pane" id="file">
			  <!--<div class="span3">-->
					<!--<h3>File Details:</h3>-->
					<div><?php echo $_SESSION['upload_msg']; ?></div>
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
