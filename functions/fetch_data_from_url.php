<?php
//$url = "http://www.aidstream.org/public/files/xml/gb-chc-1093624-activities.xml";
//fetch_data_from_url($url, "../upload/" . nice_file_name($url));
function fetch_data_from_url ($xurl, $cacheFile,$freshness = 0) {
	global $error_msg;
	global $upload_msg;
	//***This is all the collecting the feed, and saving it to a cache file.***//
	//Checks the cached file if less than 'freshness' mins old then check the feed and re-populate the cache with fresh data
	//if ($freshness == 0) {
	//  $freshness = 720;
	//} 
	//echo $freshness;
	$seconds = (60*$freshness);

	//if ( filemtime( "cache/cache_plingstoday.txt" ) < (time()-$seconds) ) {
	if ( filemtime( $cacheFile ) < (time()-$seconds) || filemtime($cacheFile) == FALSE) {

		//Overide xml variable for now
		//$xml_plings = 'http://feeds.plings.net/xml.activity.php/1/la/00BS';
		//$xml_url = $xml_plings;
		$xml_url = $xurl;

		//check url exists with curl. This is the first step in checking for a valid feed...
		// create a new curl resource
		$ch = curl_init();
		//$url = "http://www.google.com/does/not/exist";


		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, $xml_url);
		curl_setopt($ch,CURLOPT_TIMEOUT,30);
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
		  $last_modified = filemtime($cacheFile);

			$error_msg = 'Sorry, we can\'t grab the data you requested.';
		  } else {
		  //echo $response_code;
		  // If the response is 200 - i.e. ok, then we proceed to parse the feed
			  if ($response_code == '200') {
				//echo "writing";
				//Write the data to a file
				echo $cacheFile;
				file_put_contents($cacheFile,$output);
					$upload_msg = "Fetched: " . $xml_url;
					$upload_msg .= "<br/>Type: " . $file_type;
					$upload_msg .= "<br/>Size: " . $download_size;
					$upload_msg  .= "<br/>Time to fetch: " . $retrieved;
					//echo $upload_msg;
					$_SESSION['uploadedfilepath']=$cacheFile;
					$_SESSION['wellformed']=TRUE;
					$_SESSION['upload_msg'] = $upload_msg;
					return TRUE;
			 } else { //end 'if response code =200 - ie. we've refreshed the data in the cache
			  //print ('could not refresh the cache');
			} //end 'if response code =200 else
		  } //end if output empty else..
	  } //end of 'if cache file is too old then refresh it
	return TRUE; //meaning we did not fetch data because we already have a good file
}

function nice_file_name($url) {
$url = preg_replace("/http:\/\//","",$url);
$url = preg_replace("/\//","_",$url);
echo $url;
return $url;
}
?>
