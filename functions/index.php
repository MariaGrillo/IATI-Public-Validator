<?php

// Returns the filename of the appropriate PHP page to include for a given $test
// (probably taken from $_GET['test'])
function get_page($test) {
    global $tests;
    if ($test == 'reset') {
        unset($_SESSION['uploadedfilepath']);
        unset ($_SESSION['wellformed']);
        unset($_SESSION['upload_msg']);
        if (isset($_SESSION['url'])) {
                unset($_SESSION['url']);
        }
        return "pages/front.php";
    }
    else if (isset($_SESSION['uploadedfilepath']) && array_key_exists($test, $tests)) {
        return $tests[$test];
    }
    else {
        return "pages/front.php";
    }
}



//This little routine gives us the name of the file being tested to display to the user
//It could be a file name or the URL or pasted code
function uploaded_file_info() {
  if (isset($_SESSION['url'])) { //Is it a URL?
    if (filter_var($_SESSION['url'], FILTER_VALIDATE_URL) == TRUE) {
      //Note this should have already been sanitised, so this is an additional (uneccesary?) check
      $testing_file_name = htmlentities($_SESSION['url']);
    }
  } elseif (isset($_SESSION['uploadedfilepath'])) { //Has it been either uploaded or pasted
    if (strstr($_SESSION['uploadedfilepath'], "paste")) { //Pasted code is saved with the filename like pasted.time()
      $testing_file_name = "Pasted code";
    } else {
      //Filename of type basename_time().xml
      $testing_file_name = basename($_SESSION['uploadedfilepath']);
      $extension = explode(".", $testing_file_name);
      $extension = end($extension);
      $testing_file_name = explode("_",$testing_file_name);
      array_pop($testing_file_name);
      $testing_file_name = implode("_",$testing_file_name) . "." . $extension;
    }
  }
  //Finally only display the HTML if we have a file 
  if (isset($_SESSION['uploadedfilepath'])) {
    $day_time = get_time(basename($_SESSION['uploadedfilepath']));
    $day = $day_time[1];
    $time = $day_time[0];
    $today = date("z");
    if ($today == $day) {
      $day = "Today";
    } elseif ($today - $day == 1) {
      $day = "Yesterday";
    } else {
      $day = $today - $day . " days ago";
    }
      
    return '<div class="alert alert-info"><strong>Testing:</strong> ' . $testing_file_name . '<br/><strong>Uploaded:</strong> ' . $day . ' at ' . $time . ' GMT</div>';
  }
}


/*
 * 
 * name: get_time
 * @param $file_path The path to a file saved via upload
 * @return $time A string of hours, mins and seconds 
 * 
 */
function get_time($file_path) {
  $time = explode("_",$file_path);
  $time = array_pop($time);
  $time = trim($time,".xml"); //for the paste case!
  $day = date("z",$time);
  $time = date("H:i:s",$time);
  
  return array($time,$day);
}
