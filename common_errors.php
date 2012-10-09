<?php
include "settings.php";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>IATI Public Validator - Common Errors</title>
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
	  legend {
		  display:none;
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
    <?php if (isset($google_analytics_code)) { echo $google_analytics_code; } ?>
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
              <li><a href="<?php echo $host ?>"><i class="icon-home"></i> Home</a></li>
              <li class="active"><a href="<?php echo $host ?>common_errors.php"><i class="icon-asterisk"></i> Common errors</a></li>
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

		
		
	  <!--ABOUT-->
	  <div class="row">
        <div class="span12">
			<h2>Common Errors</h2>
			<p>Don't panic!</p>
			<p>Errors in your XML files are pretty common. Often what looks like a whole load of errors can easily be fixed. Below are the most common errors we encounter in people's files.</p>
			<hr>
			<h3>Well Formed XML</h3>
			<p>Well Formed XML means that machines will be able to read your data. If it is not well formed, they can't and we need to fix it. Some common problems are outlined below:</p>
			<h4>Opening and ending tag mismatch</h4>
			<p>Usually this is down to a typo somewhere. Each tag or element must be consistent. <br/>&lt;iati-activities&gt;&lt;/iati-activities&gt; is good &lt;iatiactivities&gt;&lt;/iati-activities&gt; is bad.</p>
			<hr>
			<h3>Validation Errors</h3>
			<h4>attribute 'iso-date': '' is not a valid value of the atomic type 'xs:date'.</h4>
			<p>Dates in IATI should look like YYYY-MM-DD, e.g. the 22nd August 2012 would be written as: 2012-08-22.</p>
			<h4> attribute 'generated-datetime': '2011-10-17 14:00:00' is not a valid value of the atomic type 'xs:dateTime'.</h4>
			<p>A datetime (as opposed to a date) specifies both a time and a date. A common error is to miss out the 'T' that separates the date from the time. <br/>
			So in the example above, this would validate: 2011-10-17T14:00:00<br/>
			Other common errors are not providing the Date as YYYY-MM-DD or the time as HH:MM:SS<br/>
			You can also specify a timezone and sometimes people don't get that bit quite right.</p>
			<h4>attribute 'percentage': '' is not a valid value of the atomic type 'xs:positiveInteger'.</h4>
			<p>In some circumstances we require you to supply just a number. People sometimes include commas, currency symbols, percentage signs, or write their number as a decimal. If you want to say twenty percent, just write 20.</p>
			<h4>The attribute '' is not allowed.</h4>
			<p>This means you've put something in that doesn't need to be there. Simply remove it!</p>
			<h4>Character content other than whitespace is not allowed because the content type is 'element-only'.</h4>
			<p>This usually means something has been typed where it shouldn't have been. All content should be inside the IATI element tags. This is usually a sign that something has strayed outside!</p>
			
			
		</div>
	</div>
	<hr>
	<div class="row">
        <div class="span12">
			 <h3>Other IATI Sites</h3>
		</div>
	</div>
      <!-- Example row of columns -->
      <div class="row">
		 
        <div class="span4">
          <h4>IATI Standard</h4>
          <p>Documentation about the IATI standard can be found at <a href="http://iatistandard.org/">http://iatistandard.org/</a>.<br/> We also have a wiki at: <a href="http://wiki.iatistandard.org/">http://wiki.iatistandard.org/</a>. </p>
          <!--<p><a class="btn" href="#">View details &raquo;</a></p>-->
        </div>
        <div class="span4">
          <h4>IATI Data</h4>
          <p>Published IATI data can be found on the Registry at <a href="http://iatiregistry.org">http://iatiregistry.org</a>.</p>
          <!--<p><a class="btn" href="#">View details &raquo;</a></p>-->
       </div>
        <div class="span4">
          <h4>Support</h4>
          <p>The IATI knowledge base and support system can be found at <a href="http://support.iatistandard.org">http://support.iatistandard.org</a>.</p>
          <!--<p><a class="btn" href="#">View details &raquo;</a></p>-->
        </div>
      </div>
	</div>
      <hr>

       <!-- Footer
    ================================================== -->
    <footer class="footer">
      <div class="container">
        <p class="pull-right"><a href="#">Back to top</a></p>
        <p>IATI-Public Validator is free software. <br/>Source on <a href="https://github.com/caprenter/IATI-Public_Validator">GitHub</a>. <a href="https://github.com/caprenter/IATI-Public_Validator/issues?state=open">Submit issues</a>.</p>
        <p>
          Built with <a href="http://twitter.github.com/bootstrap">Bootstrap</a> Bootstrap is licensed under the <a href="http://www.apache.org/licenses/LICENSE-2.0">Apache License v2.0</a>.<br/>
          Icons from <a href="http://glyphicons.com">Glyphicons Free</a>, licensed under <a href="http://creativecommons.org/licenses/by/3.0/">CC BY 3.0</a>.
        </p>
        <!--<ul class="footer-links">
          <li><a href="http://blog.getbootstrap.com">Read the blog</a></li>
          <li><a href="https://github.com/caprenter/IATI-Public_Validator/issues?state=open">Submit issues</a></li>
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


  </body>
</html>
