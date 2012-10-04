<?php
/* Parse the log files.
 * Set up to run as a php_cli script
 * 
 * The supplied regex gives us 5 matches
 * $matches[0] = the entire log line e.g. [Thu Sep 20 11:01:59 2012] [fail] [fetch] Curl returned an empty output
 * $matches[1] = Date and time,           
 * $matches[2] = severity (success,fail,error)
 * $matches[3] = type (upload,paste,fetch from url) 
 * $matches[4] = log message
 *
*/
include ("../settings.php"); //Gets our path to the log file

//Allow users to specify a number of days in the url ?days=
//If missing or invalid set days = 1
if (isset($_GET['days'])) {
  $days = filter_var($_GET['days'], FILTER_SANITIZE_NUMBER_INT);
  if(!filter_var($days,FILTER_VALIDATE_INT)) {
    $days = 1;
  }
} else {
  $days = 1;
}

echo "<h2>Days: " . $days . "</h2>";
echo 'Upload Directory Size: ' . round((getDirSize($upload_dir)/ 1024),2) . "KB"  . "<br/>";
//echo $log_file . "<br/>";

//we have a number of different messages logged.
$severities = array("success","error","fail");
$types = array("upload","fetch","pasted");
$upload_dir =  "../upload";

echo "Log Size: " . round((filesize("log.txt") / 1024),2) . "KB"  . "<br/>";

foreach ($severities as $severity) {
  echo "<h3>" . $severity . "</h3>";
  foreach ($types as $type) {
    echo "  " . $type . " - " .count_logs($severity, $type,$days,$log_file) . "<br/>";
  }
}

/*
 * Summarises info in the log files by counting occurances of terms
 * name: count_logs
 * @param string $filter1   The first term to filter logs by, this should be a severity tag
 * @param string $filter1   The second term to filter logs by, this should be a term tag
 * @param in $days          The number of days to look back on, e.g. last 2 days
 * @return int $count
 * 
 */

function count_logs($filter1,$filter2,$days,$log_file) {
  $count = 0;
  //Thanks http://stackoverflow.com/questions/7603017/parse-apache-log-in-php-using-preg-match for the regex to parse the log files
  $regex = '/^\[([^\]]+)\] \[([^\]]+)\] \[([^\]]+)\] ?\s*(.*)$/i'; 
  
  $handle = fopen($log_file, "r");
  if ($handle) {
    while (($buffer = fgets($handle, 4096)) !== false) {
      //echo  $buffer;
      preg_match($regex,$buffer,$matches);
      //print_r($matches);
      if (strtotime($matches[1]) > (time() - ($days * 60 *60 *24))) {
        if ($filter1 == $matches[2] && $filter2 == $matches[3]) {
          $count++;
        }
      }
    }
    if (!feof($handle)) {
      echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
  }
  return $count;
}

//Thanks to http://stackoverflow.com/questions/478121/php-get-directory-size
//This is a unix only solution
function getDirSize($path)
{
    $io = popen('/usr/bin/du -sb '.$path, 'r');
    $size = intval(fgets($io,80));
    pclose($io);
    return $size;
}
?>
