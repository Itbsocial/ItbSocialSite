<?php
/*
This script runs every time the user presses the upload button in order to inform the page that
it should process the file upload data (stored in $_FILES variable) when the page reloads 
*/
session_start();
if ( isset($_GET['shortcode_id']) ) {
	$_SESSION['iu_check_refresh_'.$_GET['shortcode_id']] = 'form button pressed';
	if ( isset($_GET['start_time']) )
		$_SESSION['iu_start_time_'.$_GET['shortcode_id']] = $_GET['start_time'];
}
die();
?>
