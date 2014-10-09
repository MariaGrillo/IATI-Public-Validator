<?php
//$host = $_SERVER['HTTP_HOST'];
$host = "http://localhost/Webs/validate-me/";
define('DEVELOPMENT', true);
date_default_timezone_set('GMT');
//in the logs/ directory there is a sample log viewer that can be run from the command line.
//you may want to store your log files in that directory, but feel free to put them elsewhere
$log_file = "path/to/log/file"; //e.g. /var/www/yourapplication/logs/log.txt
$google_analytics_code = ""; //paste your google analytics code here - remember to escape double quotes e.g. <script type=\"text/javascript\">
?>
