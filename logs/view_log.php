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
//we have a number of different messages logged.
$severities = array("success","error","fail");
$types = array("upload","fetch","paste");

echo "Size: " . round((filesize("log.txt") / 1024),2) . "KB" . PHP_EOL;

foreach ($severities as $severity) {
  echo $severity . PHP_EOL;
  foreach ($types as $type) {
    echo "  " . $type . " - " .count_logs($severity, $type,1) . PHP_EOL;
  }
}

function count_logs($filter1,$filter2,$days) {
  $count = 0;
  //Thanks http://stackoverflow.com/questions/7603017/parse-apache-log-in-php-using-preg-match for the regex to parse the log files
  $regex = '/^\[([^\]]+)\] \[([^\]]+)\] \[([^\]]+)\] ?\s*(.*)$/i'; 
  
  $handle = @fopen("log.txt", "r");
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
?>
