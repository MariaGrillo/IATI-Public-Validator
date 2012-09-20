<?php
/* Log File activity
*
*/
function record_in_log($result,$type,$message) {
  global $log_file; //set this in settings.php
  //[Thu Sep 20 11:14:06 2012] [error] [client 127.0.0.1] File does not exist: /var/www/Webs/aidinfo/validate-me/assets/ico
  $log_message = "[" . date("D M d H:i:s Y") . "] [" . $result . "] [" . $type . "] " . $message . PHP_EOL;
  file_put_contents($log_file,$log_message,FILE_APPEND);
}
?>
