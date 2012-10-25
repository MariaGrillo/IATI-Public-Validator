<?php
include("functions/log.php");
//User uploads a file
if(sizeof($_FILES)!=0) {
	//thanks: http://www.w3schools.com/php/php_file_upload.asp
	$allowedExts = array("xml,XML");
	$extension = end(explode(".", $_FILES["file"]["name"]));
	if ((($_FILES["file"]["type"] == "text/xml")
	|| ($_FILES["file"]["type"] == "application/xml")
	|| ($_FILES["file"]["type"] == "application/x-xml"))
	&& ($_FILES["file"]["size"] < 10000000) //10MB
	&& in_array($extension, $allowedExts))
	  {
	  if ($_FILES["file"]["error"] > 0)
		{
		$error_msg = "Return Code: " . $_FILES["file"]["error"] . "<br />";
		}
	  else
		{
		$upload_msg = "File: " . $_FILES["file"]["name"] . "<br />";
		$upload_msg .= "Type: " . $_FILES["file"]["type"] . "<br />";
		$upload_msg .= "Size: " . round(($_FILES["file"]["size"] / 1024),2) . " Kb<br />";
		//$upload_msg .= "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

		//if (file_exists("upload/" . $_FILES["file"]["name"]))
		//  {
		//  echo $_FILES["file"]["name"] . " already exists. ";
		//  }
		//else
		// {
      $new_file_name = preg_replace("/\\.[^.\\s]{3,4}$/", "", $_FILES["file"]["name"]);
      $new_file_name = $new_file_name . "_" . time() . ".xml";
      //$new_file_name = $_FILES["file"]["name"];
		  move_uploaded_file($_FILES["file"]["tmp_name"],
		  "upload/" . $new_file_name);
		  //$upload_msg .= "Stored in: " . "upload/" . $_FILES["file"]["name"];
		  $file_path = "upload/" . $new_file_name;
			//Set the filepath as a session variable
			$_SESSION['uploadedfilepath']=$file_path;
			$_SESSION['upload_msg'] = $upload_msg;
      $_SESSION['wellformed'] = FALSE; //Assume it's wrong then test it!
      record_in_log('success','upload','File of size: ' . round(($_FILES["file"]["size"] / 1024),2) . ' Kb uploaded');
      
		 // }
		}
	  }
	else
	  {
	  $error_msg = "We can only test XML files, and they must be smaller than 10MB<br/>Please try with a different file.";
    record_in_log('error','upload','Rejected on upload');
	  }
 }
 
 //user submits file from a URL
 if (isset($_POST["url"]) && $_POST["url"]) {
	 if (filter_var($_POST["url"], FILTER_VALIDATE_URL) == TRUE) {
		$url = htmlentities($_POST["url"]);
		//$url = $_POST["url"];
		//echo $url;
	
    //Fetch the data from the URL
    include "functions/fetch_data_from_url.php";
    $cacheFile = "upload/" . nice_file_name($url) . "_" . time();
    if (fetch_data_from_url($url, $cacheFile) == TRUE) {
      $file_path = $cacheFile;
    }
  } else {
    $error_msg = "That does not seem to be a valid URL. Please try again.";
  }
}

//user pastes a sample into the page
 if (isset($_POST["paste"]) && $_POST["paste"]) {
	 $xml = filter_var($_POST['paste'], FILTER_SANITIZE_STRING);
	  $xml = $_POST['paste'];
	 //echo $xml;
	file_put_contents("./upload/paste_".time().".xml",$xml);
	$file_path = "upload/paste_".time().".xml";
	$_SESSION['uploadedfilepath']=$file_path;
	$_SESSION['upload_msg'] = "Pasted data";
  $_SESSION['wellformed'] = FALSE; //Assume it's wrong then test it!
  record_in_log('success','pasted','File of size: ' . round((filesize($file_path) / 1024),2) . ' Kb pasted');
}
//echo $file_path;
?> 
