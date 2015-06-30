<?php
//$url = "http://www.aidstream.org/public/files/xml/gb-chc-1093624-activities.xml";
//fetch_data_from_url($url, "../upload/" . nice_file_name($url));
function fetch_data_from_url ($xurl, $cacheFile) {
	global $error_msg;
	global $upload_msg;
	//***This is all the collecting the feed, and saving it to a cache file.***//

		$xml_url = $xurl;
		//$url = "http://www.google.com/does/not/exist";

		//check url exists with curl. This is the first step in checking for a valid feed...
		// create a new curl resource
		$ch = curl_init();
		
		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, $xml_url);
		curl_setopt($ch,CURLOPT_TIMEOUT,300);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//Follow re-directs:
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

		// grab URL and pass it to the browser - as CURLOPT_RETURNTRANSFER is set, it returns the page
		//if true. Returns false if not valid
		$output = curl_exec($ch);

		// Get response code
		$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$file_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
		$download_size = curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);
		$retrieved = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
		 //echo $response_code;
		 curl_close($ch); // close cURL handler

		  if (empty($output)) {
		    //$last_modified = filemtime($cacheFile);
        $_SESSION['uploadedfilepath'] = $cacheFile;
        $error_msg = 'We could not fetch the data you requested.';
        $_SESSION['upload_msg'] = $error_msg;
        record_in_log('fail','fetch','Curl returned an empty output');
		  } else {
		  //echo $response_code;
		  // If the response is 200 - i.e. ok, then we proceed to parse the feed
			  if ($response_code == '200') {
				//echo "writing";
				//Write the data to a file
				//echo $cacheFile;
				file_put_contents($cacheFile,$output);
					$upload_msg = "Fetched: " . $xml_url;
					$upload_msg .= "<br/>Type: " . $file_type;
					$upload_msg .= "<br/>Size: " . $download_size;
					$upload_msg  .= "<br/>Time to fetch: " . $retrieved;
					//echo $upload_msg;
					$_SESSION['uploadedfilepath']=$cacheFile;
					$_SESSION['wellformed'] = FALSE; //Assume it's wrong then test it later!
					$_SESSION['upload_msg'] = $upload_msg;
					$_SESSION['url'] = $xml_url;
          record_in_log('success','fetch','File of size: ' . round((filesize($cacheFile) / 1024),2) . ' Kb fetched');
					return TRUE;
			 } else { //end 'if response code =200 - ie. we've refreshed the data in the cache
				//$_SESSION['uploadedfilepath'] = $cacheFile;
				$error_msg = 'We could not fetch the data you requested.';
				$_SESSION['upload_msg'] = $error_msg;
				//$_SESSION['url'] = $xml_url;
        record_in_log('fail','fetch','Curl response code was not 200');
			//print ('could not refresh the cache');
			} //end 'if response code =200 else
		  } //end if output empty else..
	return TRUE; //meaning we did not fetch data because we already have a good file
}

function nice_file_name($url) {
	$url = preg_replace("/http:\/\//","",$url);
	$url = preg_replace("/\//","_",$url);
	//echo $url;
	return $url;
}
?>
