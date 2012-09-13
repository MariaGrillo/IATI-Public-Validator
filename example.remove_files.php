<?php
/* This file can be called on a cron job to remove files older than a certain time.
 * take a copy and place it in the root of the site, rename it if you like, change your uploads directory, whatever
*/
$dir = '/path/to/uploads/directory';
$seconds = 60 * 60 * 24; //Files older than now - seconds will be deleted
//$dir = 'uploads/';
if ($handle = opendir($dir)) {

    /* This is the correct way to loop over the directory. */
    while (false !== ($file = readdir($handle))) {
        if ( filemtime($dir.$file) < time()- $seconds ) {
           unlink($dir.$file);
        }
    }

    closedir($handle);
}

?>
