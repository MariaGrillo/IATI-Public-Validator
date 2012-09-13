<?php
//User uploads a file
if(sizeof($_FILES)!=0) {
	//thanks: http://www.w3schools.com/php/php_file_upload.asp
	$allowedExts = array("xml");
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
		$upload_msg .= "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
		//$upload_msg .= "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

		//if (file_exists("upload/" . $_FILES["file"]["name"]))
		//  {
		//  echo $_FILES["file"]["name"] . " already exists. ";
		//  }
		//else
		// {
		  move_uploaded_file($_FILES["file"]["tmp_name"],
		  "upload/" . $_FILES["file"]["name"]);
		  //$upload_msg .= "Stored in: " . "upload/" . $_FILES["file"]["name"];
		  $file_path = "upload/" . $_FILES["file"]["name"];
			//Set the filepath as a session variable
			$_SESSION['uploadedfilepath']=$file_path;
			$_SESSION['wellformed']=TRUE;
			$_SESSION['upload_msg'] = $upload_msg;
		 // }
		}
	  }
	else
	  {
	  $error_msg = "We can only test XML files, and they must be smaller than 10MB<br/>Please try with a different file.";
	  }
 }
 
 //user submits file from a URL
 if (isset($_POST["url"]) && $_POST["url"]) {
	 if (filter_var($_POST["url"], FILTER_VALIDATE_URL) == TRUE) {
		$url = htmlentities($_POST["url"]);
		//$url = $_POST["url"];
		//echo $url;
	}
	
	//Fetch the data from the URL
	include "functions/fetch_data_from_url.php";
	if (fetch_data_from_url($url, "upload/" . nice_file_name($url)) == TRUE) {
		$file_path = "upload/" . nice_file_name($url);
	}
}
//echo $file_path;
?> 
