<?php
//error_reporting(0);
session_start();
include "functions/process_files.php";
include "settings.php";
$tests = array('default','reset','xsd','basic');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>IATI Public Validator</title>
    <!--<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">-->

    <!-- Le styles -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    
    <style type="text/css">
      body {
        /*padding-top: 60px;*/
        padding-bottom: 40px;
      }
      .navbar-static-top {
		  padding-bottom: 20px;
	  }
    </style>
    <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="assets/css/validate-me.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <!--<link rel="shortcut icon" href="assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">-->
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-static-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <!--<a class="brand" href="<?php echo $host ?>">IATI Public Validator</a>-->
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="<?php echo $host ?>">Home</a></li>
              <!--<li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="#">Action</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something else here</a></li>
                  <li class="divider"></li>
                  <li class="nav-header">Nav header</li>
                  <li><a href="#">Separated link</a></li>
                  <li><a href="#">One more separated link</a></li>
                </ul>
              </li>-->
            </ul>
            <!--<form class="navbar-form pull-right">
              <input class="span2" type="text" placeholder="Email">
              <input class="span2" type="password" placeholder="Password">
              <button type="submit" class="btn">Sign in</button>
            </form>-->
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
	<header class="jumbotron subhead" id="overview">
	  <div class="container">
		  <div class="row">
			<div class="span10">
				<p class="lead">
					<a href="<?php echo $host; ?>"><img src="assets/img/logo.png" width="" height="" alt="IATI Logo" /></a>
					IATI Public Validator
				</p>
			</div>
			<div span="2" class="reset">
				<?php if (isset($_SESSION['uploadedfilepath'])) :?>
					<p class="lead"><br/><a href="<?php echo $host; ?>?test=reset"" class="btn btn-large btn-success">Load New File</a></p>
				<?php endif; ?>
			</div>
		</div>
	  </div>
	</header>
	<div class="container">
		<div class="row">
			<div class="span2">
				<div class="well sidebar-nav">
					<ul class="nav nav-list">
					  <li class="nav-header">Sidebar</li>
					  <li><a href="<?php echo $host; ?>">Well Formed</a></li>
					  <li><a href="<?php echo $host; ?>?test=xsd">Validate</a></li>
					  <!--<li><a href="#">Link</a></li>
					  <li class="nav-header">Sidebar</li>-->
					  <!--<li><a href="#">Link</a></li>
					  <li><a href="#">Link</a></li>
					  <li><a href="#">Link</a></li>
					  <li><a href="#">Link</a></li>
					  <li><a href="#">Link</a></li>
					  <li><a href="#">Link</a></li>
					  <li class="nav-header">Sidebar</li>
					  <li><a href="#">Link</a></li>
					  <li><a href="#">Link</a></li>
					  <li><a href="#">Link</a></li>-->
					</ul>
				  </div><!--/.well -->
			</div>
			<div class="span10">
				<!-- Main hero unit for a primary marketing message or call to action -->
				<div class="hero-unit">
					<?php 
						if (isset($_GET['test'])) {
							$test = filter_var($_GET['test'], FILTER_SANITIZE_STRING);
						} else {
							$test = "default";
						}

					switch ($test) {
						case "xsd":
							echo "validate";
							if (isset($_SESSION['uploadedfilepath'])) {
								include "pages/validate-xsd.php";
							} else {
								echo "validate";
								include "pages/front.php";
							}
							break;
						case "reset";
							unset($_SESSION['uploadedfilepath']);
							unset($_SESSION['upload_msg']);
							echo "reset";
							include "pages/front.php"; 
							break;
						default:
							echo "home";
							include "pages/front.php"; 
							break;
					}
					/*if (isset($_SESSION['uploadedfilepath'])) {
						include "pages/validate-xsd.php";
					} else {
						include "pages/front.php";
					}*/
						
					//include "pages/front.php"; 
					?>
						
				</div>
			</div>
		</div>
    
    
      <!-- Example row of columns -->
      <div class="row">
        <div class="span4">
          <h2>Heading</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn" href="#">View details &raquo;</a></p>
        </div>
        <div class="span4">
          <h2>Heading</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn" href="#">View details &raquo;</a></p>
       </div>
        <div class="span4">
          <h2>Heading</h2>
          <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
          <p><a class="btn" href="#">View details &raquo;</a></p>
        </div>
      </div>
	</div>
      <hr>

       <!-- Footer
    ================================================== -->
    <footer class="footer">
      <div class="container">
        <p class="pull-right"><a href="#">Back to top</a></p>
        <p>IATI-Public Validator is free software. Source on <a href="https://github.com/caprenter/IATI-Public_Validator">GitHub</a></p>
        <p>Built with <a href="http://twitter.github.com/bootstrap">Bootstrap</a> Bootstrap is licensed under the <a href="http://www.apache.org/licenses/LICENSE-2.0">Apache License v2.0</a>. </p>
        <!--<ul class="footer-links">
          <li><a href="http://blog.getbootstrap.com">Read the blog</a></li>
          <li><a href="https://github.com/twitter/bootstrap/issues?state=open">Submit issues</a></li>
          <li><a href="https://github.com/twitter/bootstrap/wiki">Roadmap and changelog</a></li>
        </ul>-->
      </div>
    </footer>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap-transition.js"></script>
    <script src="assets/js/bootstrap-alert.js"></script>
    <script src="assets/js/bootstrap-modal.js"></script>
    <script src="assets/js/bootstrap-dropdown.js"></script>
    <script src="assets/js/bootstrap-scrollspy.js"></script>
    <script src="assets/js/bootstrap-tab.js"></script>
    <script src="assets/js/bootstrap-tooltip.js"></script>
    <script src="assets/js/bootstrap-popover.js"></script>
    <script src="assets/js/bootstrap-button.js"></script>
    <script src="assets/js/bootstrap-collapse.js"></script>
    <script src="assets/js/bootstrap-carousel.js"></script>
    <script src="assets/js/bootstrap-typeahead.js"></script> -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script>
		//This is the tabs
		$('#myTab a').click(function (e) {
		  e.preventDefault();
		  $(this).tab('show');
		})
		$('#intext a').click(function (e) {
		  e.preventDefault();
		  $('#myTab a[href="#extra"]').tab('show');
		})
		$(function () {
			$('#myTab a[href="#status"]').tab('show');
			$('#myTab a[href="#file"]').tab();
			$('#myTab a[href="#extra"]').tab();
			//$('#myTab a[href="#settings"]').tab();
		})
	</script>

  </body>
</html>
