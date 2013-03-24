<?php
session_start();
/*
Plugin Name: Inline Upload
Plugin URI: http://www.iptanus.com/?page_id=27
Description: Simple interface to upload files from a page.
Version: 1.7.11
Author: Nickolas Bossinas
Author URI: http://www.iptanus.com
*/

/*
-------****** This plugin is a conversion of Upload Widget plugin of Monpelaud ******-------

Many thanks to the following commenters and programmers for their valuable remarks and assistance:

   - Vassilis
   - Bruno Rotondi
   - Monpelaud

*/

/*
Inline Upload (Wordpress Plugin)
Copyright (C) 2010, 2011, 2012 Nickolas Bossinas
Contact me at http://www.iptanus.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by          
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

add_shortcode("inline_upload", "inline_upload_handler");
load_plugin_textdomain('inline-upload', false, dirname(plugin_basename (__FILE__)).'/languages');
wp_enqueue_style('inline-upload-style', '/'.PLUGINDIR .'/'.dirname(plugin_basename (__FILE__)).'/css/inline_upload_style.css',false,'1.0','all');
wp_enqueue_script('json_class', WP_CONTENT_URL.'/plugins/'.dirname(plugin_basename (__FILE__)).'/include/json2.js');
wp_enqueue_script('inline_upload_script', WP_CONTENT_URL.'/plugins/'.dirname(plugin_basename (__FILE__)).'/include/inline_upload_functions.js');
add_action('wp_ajax_iu_ajax_action', 'iu_ajax_action_callback');
add_action('wp_ajax_nopriv_iu_ajax_action', 'iu_ajax_action_callback');

//define plugin defaults
DEFINE("IU_UPLOADID", "1");
DEFINE("IU_UPLOADTITLE", __('Upload a file', 'inline-upload'));
DEFINE("IU_SELECTBUTTON", __('Select File', 'inline-upload'));
DEFINE("IU_UPLOADBUTTON", __('Upload File', 'inline-upload'));
DEFINE("IU_SINGLEBUTTON", "false");
DEFINE("IU_UPLOADROLE", "administrator");
DEFINE("IU_UPLOADPATH", 'uploads');
DEFINE("IU_CREATEPATH", "false");
DEFINE("IU_UPLOADPATTERNS", "*.*");
DEFINE("IU_MAXSIZE", "10");
DEFINE("IU_ACCESSMETHOD", "normal");
DEFINE("IU_FTPINFO", "");
DEFINE("IU_USEFTPDOMAIN", "false");
DEFINE("IU_DUBLICATESPOLICY", "overwrite");
DEFINE("IU_UNIQUEPATTERN", "index");
DEFINE("IU_FILEBASELINK", "false");
DEFINE("IU_NOTIFY", "false");
DEFINE("IU_NOTIFYRECIPIENTS", "");
DEFINE("IU_NOTIFYSUBJECT", __('File Upload Notification', 'inline-upload'));
DEFINE("IU_NOTIFYMESSAGE", __("Dear Recipient,\n\n   This is an automatic delivery message to notify you that a new file has been uploaded.\n\nBest Regards", 'inline-upload'));
DEFINE("IU_ATTACHFILE", "false");
DEFINE("IU_REDIRECT", "false");
DEFINE("IU_REDIRECTLINK", "");
DEFINE("IU_ADMINMESSAGES", "false");
DEFINE("IU_SUCCESSMESSAGE", __('File %filename% uploaded successfully', 'inline-upload'));
DEFINE("IU_SUCCESSMESSAGECOLOR", "green");
DEFINE("IU_SUCCESSMESSAGECOLORS", "#006600,#EEFFEE,#006666");
DEFINE("IU_FAILMESSAGECOLORS", "#660000,#FFEEEE,#666600");
DEFINE("IU_SHOWTARGETFOLDER", "false");
DEFINE("IU_TARGETFOLDERLABEL", "Upload Directory");
DEFINE("IU_ASKFORSUBFOLDERS", "false");
DEFINE("IU_SUBFOLDERTREE", "");
DEFINE("IU_FORCECLASSIC", "false");
DEFINE("IU_TESTMODE", "false");
DEFINE("IU_WIDTHS", "");
DEFINE("IU_HEIGHTS", "");
DEFINE("IU_PLACEMENTS", "title/filename+selectbutton+uploadbutton/subfolders/progressbar/message");
//define plugin messages
DEFINE("IU_ERROR_ADMIN_FTPDIR_RESOLVE", __("Error. Could not resolve ftp target filedir. Check the domain in 'ftpinfo' attribute.", "inline-upload"));
DEFINE("IU_ERROR_ADMIN_FTPINFO_INVALID", __("Error. Invalid ftp information. Check 'ftpinfo' attribute.", "inline-upload"));
DEFINE("IU_ERROR_ADMIN_FTPINFO_EXTRACT", __("Error. Could not extract ftp information from 'ftpinfo' attribute. Check its syntax.", "inline-upload"));
DEFINE("IU_ERROR_ADMIN_FTPFILE_RESOLVE", __("Error. Could not resolve ftp target filename. Check the domain in 'ftpinfo' attribute.", "inline-upload"));
DEFINE("IU_ERROR_ADMIN_FILE_PHP_SIZE", __("Error. The upload size limit of PHP directive upload_max_filesize is preventing the upload of big files.\nPHP directive upload_max_filesize limit is: ".ini_get("upload_max_filesize").".\nTo increase the limit change the value of the directive from php.ini.\nIf you don't have access to php.ini, then try adding the following line to your .htaccess file:\n\nphp_value upload_max_filesize 10M\n\n(adjust the size according to your needs)\n\nThe file .htaccess is found in your website root directory (where index.php is found).\nIf your don't have this file, then create it.\nIf this does not work either, then contact your domain provider.", "inline-upload"));
DEFINE("IU_ERROR_ADMIN_FILE_PHP_TIME", __("The upload time limit of PHP directive max_input_time is preventing the upload of big files.\nPHP directive max_input_time limit is: ".ini_get("max_input_time")." seconds.\nTo increase the limit change the value of the directive from php.ini.\nIf you don't have access to php.ini, then add the following line to your .htaccess file:\n\nphp_value max_input_time 500\n\n(adjust the time according to your needs)\n\nThe file .htaccess is found in your website root directory (where index.php is found).\nIf your don't have this file, then create it.\nIf this does not work either, then contact your domain provider.", "inline-upload"));
DEFINE("IU_ERROR_ADMIN_DIR_PERMISSION", __("Error. Permission denied to write to target folder.\nCheck and correct read/write permissions of target folder.", "inline-upload"));
DEFINE("IU_ERROR_DIR_EXIST", __("Targer folder doesn't exist.", "inline-upload"));
DEFINE("IU_ERROR_DIR_NOTEMP", __("Upload failed! Missing a temporary folder.", "inline-upload"));
DEFINE("IU_ERROR_DIR_PERMISSION", __("Upload failed! Permission denied to write to target folder.", "inline-upload"));
DEFINE("IU_ERROR_FILE_ALLOW", __("File not allowed.", "inline-upload"));
DEFINE("IU_ERROR_FILE_PLUGIN_SIZE", __("The uploaded file exceeds the file size limit.", "inline-upload"));
DEFINE("IU_ERROR_FILE_PHP_SIZE", __("Upload failed! The uploaded file exceeds the file size limit of the server. Please contact the administrator.", "inline-upload"));
DEFINE("IU_ERROR_FILE_PHP_TIME", __("Upload failed! The duration of the upload exceeded the time limit of the server. Please contact the administrator.", "inline-upload"));
DEFINE("IU_ERROR_FILE_HTML_SIZE", __("Upload failed! The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.", "inline-upload"));
DEFINE("IU_ERROR_FILE_PARTIAL", __("Upload failed! The uploaded file was only partially uploaded.", "inline-upload"));
DEFINE("IU_ERROR_FILE_NOTHING", __("Upload failed! No file was uploaded.", "inline-upload"));
DEFINE("IU_ERROR_FILE_WRITE", __("Upload failed! Failed to write file to disk.", "inline-upload"));
DEFINE("IU_ERROR_FILE_MOVE", __("Upload failed! Error occured while moving temporary file. Please contact administrator.", "inline-upload"));
DEFINE("IU_ERROR_UPLOAD_STOPPED", __("Upload failed! A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop; examining the list of loaded extensions with phpinfo() may help.", "inline-upload"));
DEFINE("IU_ERROR_UPLOAD_FAILED_WHILE", __("Upload failed! Error occured while attemting to upload the file.", "inline-upload"));
DEFINE("IU_ERROR_UPLOAD_FAILED", __("Upload failed!", "inline-upload"));
DEFINE("IU_ERROR_UPLOAD_CANCELLED", __("Upload failed! The upload has been canceled by the user or the browser dropped the connection.", "inline-upload"));
DEFINE("IU_ERROR_UNKNOWN", __("Upload failed! Unknown error.", "inline-upload"));
DEFINE("IU_ERROR_CONTACT_ADMIN", __("Please contact the administrator.", "inline-upload"));
DEFINE("IU_WARNING_FILE_EXISTS", __("Upload skipped! File already exists.", "inline-upload"));
DEFINE("IU_NOTIFY_TESTMODE", __("Test Mode", "inline-upload"));

function inline_upload_handler($incomingfrompost) {
  //process incoming attributes assigning defaults if required
  $incomingfrompost=shortcode_atts(array(
    "uploadid" => IU_UPLOADID,
    "uploadtitle" => IU_UPLOADTITLE,
    "selectbutton" => IU_SELECTBUTTON,
    "uploadbutton" => IU_UPLOADBUTTON,
    "singlebutton" => IU_SINGLEBUTTON,
    "uploadrole" => IU_UPLOADROLE,
    "uploadpath" => IU_UPLOADPATH,
    "createpath" => IU_CREATEPATH,            
    "uploadpatterns" => IU_UPLOADPATTERNS,
    "maxsize" => IU_MAXSIZE,            
    "accessmethod" => IU_ACCESSMETHOD,            
    "ftpinfo" => IU_FTPINFO,            
    "useftpdomain" => IU_USEFTPDOMAIN,            
    "dublicatespolicy" => IU_DUBLICATESPOLICY,            
    "uniquepattern" => IU_UNIQUEPATTERN,            
    "filebaselink" => IU_FILEBASELINK,            
    "notify" => IU_NOTIFY,            
    "notifyrecipients" => IU_NOTIFYRECIPIENTS,            
    "notifysubject" => IU_NOTIFYSUBJECT,            
    "notifymessage" => IU_NOTIFYMESSAGE,            
    "attachfile" => IU_ATTACHFILE,            
    "redirect" => IU_REDIRECT,            
    "redirectlink" => IU_REDIRECTLINK,            
    "adminmessages" => IU_ADMINMESSAGES,            
    "successmessage" => IU_SUCCESSMESSAGE,            
    "successmessagecolor" => IU_SUCCESSMESSAGECOLOR,            
    "successmessagecolors" => IU_SUCCESSMESSAGECOLORS,            
    "failmessagecolors" => IU_FAILMESSAGECOLORS,            
    "showtargetfolder" => IU_SHOWTARGETFOLDER,            
    "targetfolderlabel" => IU_TARGETFOLDERLABEL,            
    "askforsubfolders" => IU_ASKFORSUBFOLDERS,            
    "subfoldertree" => IU_SUBFOLDERTREE,            
    "forceclassic" => IU_FORCECLASSIC,            
    "testmode" => IU_TESTMODE,
    "widths" => IU_WIDTHS,            
    "heights" => IU_HEIGHTS,            
    "placements" => IU_PLACEMENTS            
  ), $incomingfrompost);
  //run function that actually does the work of the plugin
  $inline_upload_output = inline_upload_function($incomingfrompost);
  //send back text to replace shortcode in post
  return $inline_upload_output;
}

function iu_upload_plugin_clean($label) {
	/**
	 * Regular expressions to the change some characters.
	 */

	$search = array ('@[eeeeEE]@i','@[aaaAA]@i','@[iiII]@i','@[uuuUU]@i','@[ooOO]@i',
	'@[c]@i','@[^a-zA-Z0-9._]@');	 
	$replace = array ('e','a','i','u','o','c','-');
	$label =  preg_replace($search, $replace, $label);
	$label = strtolower($label); // Convert in lower case
	return $label;
}

function iu_upload_plugin_full_path( $params ) {
	$path = $params["uploadpath"];
	if ( $params["accessmethod"]=='ftp' && $params["ftpinfo"] != '' && $params["useftpdomain"] == "true" ) {
		$ftpdata_flat =  str_replace(array('\:', '\@'), array('\_', '\_'), $params["ftpinfo"]);
		$pos1 = strpos($ftpdata_flat, ":");
		$pos2 = strpos($ftpdata_flat, "@");
		if ( $pos1 && $pos2 && $pos2 > $pos1 ) {
			$ftp_username = substr($params["ftpinfo"], 0, $pos1);
			$ftp_password = substr($params["ftpinfo"], $pos1 + 1, $pos2 - $pos1 - 1);
			$ftp_host = substr($params["ftpinfo"], $pos2 + 1);
			$ftp_username = str_replace('@', '%40', $ftp_username);   //if username contains @ character then convert it to %40
			$ftp_password = str_replace('@', '%40', $ftp_password);   //if password contains @ character then convert it to %40
			$start_folder = 'ftp://'.$ftp_username.':'.$ftp_password."@".$ftp_host.'/';
		}
		else $start_folder = 'ftp://'.$params["ftpinfo"].'/';
	}
	else $start_folder = WP_CONTENT_DIR.'/';
	if ($path) {
		if ( $path == ".." || substr($path, 0, 3) == "../" ) {
			$start_folder = ABSPATH;
			$path = substr($path, 2, strlen($path) - 2);
		}
		if ( substr($path, 0, 1) == "/" ) $path = substr($path, 1, strlen($path) - 1);
		if ( substr($path, -1, 1) == "/" ) $path = substr($path, 0, strlen($path) - 1);
		$full_upload_path = $start_folder;
		if ( $path != "" ) $full_upload_path .= $path.'/';
	}
	else {
		$full_upload_path = $start_folder;
	}
	return $full_upload_path;
}

function iu_upload_plugin_directory( $path ) {
	$dirparts = explode("/", $path);
	return $dirparts[count($dirparts) - 1];
}

function iu_upload_plugin_wildcard_to_preg($pattern) {
	return '/^' . str_replace(array('\*', '\?', '\[', '\]'), array('.*', '.', '[', ']+'), preg_quote($pattern)) . '$/is';
}



function iu_upload_plugin_wildcard_match($pattern, $str) {
	$pattern = iu_upload_plugin_wildcard_to_preg($pattern);
	return preg_match($pattern, $str);
}

function iu_create_directory($path, $method, $ftpdata, $adminmessages) {
	$ret_message = "";
	if ( $method == "" || $method == "normal" ) {
		mkdir($path, 0777, true);
	}
	else if ( $method == "ftp" && $ftpdata != "" ) {
		$ftpdata_flat =  str_replace(array('\:', '\@'), array('\_', '\_'), $ftpdata);
		$pos1 = strpos($ftpdata_flat, ":");
		$pos2 = strpos($ftpdata_flat, "@");
		if ( $pos1 && $pos2 && $pos2 > $pos1 ) {
			$ftp_username = substr($ftpdata, 0, $pos1);
			$ftp_password = substr($ftpdata, $pos1 + 1, $pos2 - $pos1 - 1);
			$ftp_host = substr($ftpdata, $pos2 + 1);
			$conn_id = ftp_connect($ftp_host);
			$login_result = ftp_login($conn_id, $ftp_username, $ftp_password);
			if ( $conn_id && $login_result ) {
				$flat_host = preg_replace("/^(.*\.)?([^.]*\..*)$/", "$2", $ftp_host);
				$pos1 = strpos($path, $flat_host);
				if ( $pos1 ) {
					$path = substr($path, $pos1 + strlen($flat_host));
					ftp_mkdir($conn_id, $path);
					ftp_chmod($conn_id, 511, $path);
				}
				else if ( $adminmessages ) {
					$ret_message = IU_ERROR_ADMIN_FTPDIR_RESOLVE;
				}
			}
			else if ( $adminmessages ) {
				$ret_message = IU_ERROR_ADMIN_FTPINFO_INVALID;
			}
			ftp_quit($conn_id);
		}
		else if ( $adminmessages ) {
			$ret_message = IU_ERROR_ADMIN_FTPINFO_EXTRACT;
		}
	}
	return $ret_message;
}


function iu_upload_file($source, $target, $method, $ftpdata, $adminmessages) {
	$ret_array = "";
	$ret_array["uploaded"] = false;
	$ret_array["admin_message"] = "";
	$ret_message = "";
	if ( $method == "" || $method == "normal" ) {
		$ret_array["uploaded"] = move_uploaded_file($source, $target);
	}
	else if ( $method == "ftp" &&  $ftpdata != "" ) {
		$result = false;
		$ftpdata_flat =  str_replace(array('\:', '\@'), array('\_', '\_'), $ftpdata);
		$pos1 = strpos($ftpdata_flat, ":");
		$pos2 = strpos($ftpdata_flat, "@");
		if ( $pos1 && $pos2 && $pos2 > $pos1 ) {
			$ftp_username = substr($ftpdata, 0, $pos1);
			$ftp_password = substr($ftpdata, $pos1 + 1, $pos2 - $pos1 - 1);
			$ftp_host = substr($ftpdata, $pos2 + 1);
			$conn_id = ftp_connect($ftp_host);
			$login_result = ftp_login($conn_id, $ftp_username, $ftp_password);
			if ( $conn_id && $login_result ) {
				$flat_host = preg_replace("/^(.*\.)?([^.]*\..*)$/", "$2", $ftp_host);
				$pos1 = strpos($target, $flat_host);
				if ( $pos1 ) {
					$temp_fname = tempnam(dirname($target), "tmp");
					move_uploaded_file($source, $temp_fname);
					$target = substr($target, $pos1 + strlen($flat_host));
					$ret_array["uploaded"] = ftp_put($conn_id, $target, $temp_fname, FTP_BINARY);
					ftp_chmod($conn_id, 511, $target);
					unlink($temp_fname);
				}
				else if ( $adminmessages ) {
					$ret_message = IU_ERROR_ADMIN_FTPFILE_RESOLVE;
				}
			}
			else if ( $adminmessages ) {
				$ret_message = IU_ERROR_ADMIN_FTPINFO_INVALID;
			}
			ftp_quit($conn_id);
		}
		else if ( $adminmessages ) {
			$ret_message = IU_ERROR_ADMIN_FTPINFO_EXTRACT.$ftpdata_flat;
		}
	}		
	$ret_array["admin_message"] = $ret_message;
	return $ret_array;
}

function iu_plugin_encode_string($string) {
	$array = unpack('C*', $string);
	$new_string = "";	
	for ($i = 1; $i <= count($array); $i ++) {
		$new_string .= sprintf("%02X", $array[$i]);
	}
	return $new_string;
}

function iu_plugin_decode_string($string) {
	$new_string = "";	
	for ($i = 0; $i < strlen($string); $i += 2 ) {
		$new_string .= sprintf("%c", hexdec(substr($string, $i ,2)));
	}
	return $new_string;
}

function iu_plugin_parse_array($source) {
	$keys = array_keys($source);
	$new_arr = array();
	for ($i = 0; $i < count($keys); $i ++) 
		$new_arr[$keys[$i]] = wp_specialchars_decode($source[$keys[$i]]);
	return $new_arr;
}

function iu_create_random_string($len) {
	$base = 'ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz123456789';
	$max = strlen($base) - 1;
	$activatecode = '';
	mt_srand((double)microtime()*1000000);
	while (strlen($activatecode) < $len)
		$activatecode .= $base{mt_rand(0, $max)};
	return $activatecode;
}

function iu_get_user_role($user, $param_roles) {
	if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
		/* Go through the array of the roles of the current user */
		foreach ( $user->roles as $user_role ) {
			$user_role = strtolower($user_role);
			/* If one role of the current user matches to the roles allowed to upload */
			if ( in_array($user_role, $param_roles) || $user_role == 'administrator' ) {
				/*  We affect this role to current user */
				$result_role = $user_role;
				break;
			}
			else {
				/* We affect the 'visitor' role to current user */
				$result_role = 'visitor';
			}
		}
	}
	else {
		$result_role = 'visitor';
	}
	return $result_role;		
}

function iu_generate_current_params_index($shortcode_id, $user_login) {
	global $post;
	$cur_index_str = '||'.$post->ID.'||'.$shortcode_id.'||'.$user_login;
	$cur_index_str_search = '\|\|'.$post->ID.'\|\|'.$shortcode_id.'\|\|'.$user_login;
	$index_str = get_option('iu_params_index');
	$index = explode("&&", $index_str);
	foreach ($index as $key => $value) if ($value == "") unset($index[$key]);
	$index_match = preg_grep("/".$cur_index_str_search."$/", $index);
	if ( count($index_match) == 1 && $index_match[0] == "" ) unset($index_match[0]);
	if ( count($index_match) <= 0 ) {
		$cur_index_rand = iu_create_random_string(16);
		array_push($index, $cur_index_rand.$cur_index_str);
	}
	else {
		$cur_index_rand = substr(current($index_match), 0, 16);
		if ( count($index_match) > 1 ) {
			$index_match_keys = array_keys($index_match);
			for ($i = 1; $i < count($index_match); $i++) {
				$ii = $index_match_keys[$i];
				unset($index[array_search($index_match[$ii], $index, true)]);
			}
		}
	}
	if ( count($index_match) != 1 ) {
		$index_str = implode("&&", $index);
		update_option('iu_params_index', $index_str);
	}
	return $cur_index_rand;
}

function iu_get_params_fields_from_index($params_index) {
	$fields = array();
	$index_str = get_option('iu_params_index');
	$index = explode("&&", $index_str);
	$index_match = preg_grep("/^".$params_index."/", $index);
	if ( count($index_match) == 1 && $index_match[0] == "" ) unset($index_match[0]);
	if ( count($index_match) > 0 )
		list($fields['unique_id'], $fields['page_id'], $fields['shortcode_id'], $fields['user_login']) = explode("||", current($index_match));
	return $fields; 
}

function iu_encode_array_to_string($arr) {
	$arr_str = json_encode($arr);
	$arr_str = iu_plugin_encode_string($arr_str);
	return $arr_str;
}

function iu_decode_array_from_string($arr_str) {
	$arr_str = iu_plugin_decode_string($arr_str);
	$arr = json_decode($arr_str, true);
	return $arr;
}

function iu_decode_dimensions($dimensions_str) {
	$dimensions["title"] = "";
	$dimensions["filename"] = "";
	$dimensions["selectbutton"] = "";
	$dimensions["uploadbutton"] = "";
	$dimensions["subfolders_label"] = "";
	$dimensions["subfolders_select"] = "";
	$dimensions["progressbar"] = "";
	$dimensions["message"] = "";
	$dimensions_raw = explode(",", $dimensions_str);
	foreach ( $dimensions_raw as $dimension_str ) {
		$dimension_raw = explode(":", $dimension_str);
		$item = strtolower(trim($dimension_raw[0]));
		foreach ( array_keys($dimensions) as $key ) {
			if ( $item == $key ) $dimensions[$key] = trim($dimension_raw[1]);
		}
	}
	return $dimensions;
}

function iu_add_div() {
	$items_count = func_num_args();
	if ( $items_count == 0 ) return "";
	$items_raw = func_get_args();
	$items = array( );
	foreach ( $items_raw as $item_raw ) {
		if ( is_array($item_raw) ) array_push($items, $item_raw);
	}
	$items_count = count($items);
	if ( $items_count == 0 ) return "";
	$div = "";
	$div .= "\n\t".'<div class="file_div_clean">';  
	$div .= "\n\t\t".'<table class="file_table_clean">';
	$div .= "\n\t\t\t".'<tbody>';
	$div .= "\n\t\t\t\t".'<tr>';  
	for ( $i = 0; $i < $items_count; $i++ ) {
		$div .= "\n\t\t\t\t\t".'<td class="file_td_clean"';  
		if ( $i < $items_count - 1 ) $div .= ' style="padding: 0 4px 0 0"';
		$div .= '>';
		$div .= "\n\t\t\t\t\t\t".'<div id="'.$items[$i]["title"].'" class="file_div_clean"';  
		if ( $items[$i]["hidden"] ) $div .= ' style="display: none"';
		$div .= '>';
		$item_lines_count = count($items[$i]) - 2;
		for ( $k = 1; $k <= $item_lines_count; $k++ ) {
			if ( $items[$i]["line".$k] != "" ) $div .= "\n\t\t\t\t\t\t\t".$items[$i]["line".$k];
		}
		$div .= "\n\t\t\t\t\t\t\t".'<div class="file_space_clean" />';  
		$div .= "\n\t\t\t\t\t\t".'</div>';  
		$div .= "\n\t\t\t\t\t".'</td>';  
	}
	$div .= "\n\t\t\t\t".'</tr>';  
	$div .= "\n\t\t\t".'</tbody>';
	$div .= "\n\t\t".'</table>';
	$div .= "\n\t".'</div>';  
	return $div;
}

//use wp_specialchars_decode so html is treated as html and not text
//use wp_specialchars when outputting text to ensure it is valid html

function inline_upload_function($incomingfromhandler) {
	global $post;
//	global $blog_id;
	$params = iu_plugin_parse_array($incomingfromhandler);
	$user = wp_get_current_user();
	$widths = iu_decode_dimensions($params["widths"]);
	$heights = iu_decode_dimensions($params["heights"]);

	$uploadform = 'uploadform_'.$params["uploadid"];
	$uploadedfile = 'uploadedfile_'.$params["uploadid"];
	$upfile = 'upfile_'.$params["uploadid"];
	$selectsubdir = 'selectsubdir_'.$params["uploadid"];
	$hiddeninput = 'hiddeninput_'.$params["uploadid"];
	$upload = 'upload_'.$params["uploadid"];
	$upload_type = "button";
	$upload_clickaction = 'iu_redirect_to_classic('.$params["uploadid"].', 0);';
	$upload_directaction = $upload_clickaction.'this.form.submit();';
	$textfile = 'fileName_'.$params["uploadid"];
	$input = 'input_'.$params["uploadid"];
	$message_label = 'messagelabel_'.$params["uploadid"];
	$progress_bar = 'progressbar_'.$params["uploadid"];

	//check if user is allowed to view plugin, otherwise do not generate it
	$uploadroles = explode(",", $params["uploadrole"]);
	foreach ( $uploadroles as &$uploadrole ) {
		$uploadrole = strtolower(trim($uploadrole));
	}
	$plugin_upload_user_role = iu_get_user_role($user, $uploadroles);		
	if ( !in_array($plugin_upload_user_role, $uploadroles) && $plugin_upload_user_role != 'administrator' && $params["uploadrole"] != 'all' ) return;

	$params["adminmessages"] = ( $params["adminmessages"] == "true" && $plugin_upload_user_role == 'administrator' );

	/* Define dynamic upload path from variables */
	$search = array ('/%username%/', '/%blogid%/');	 
	if ( is_user_logged_in() ) $replace = array ($user->user_login, $blog_id);
	else $replace = array ("guests", $blog_id);
	$params["uploadpath"] =  preg_replace($search, $replace, $params["uploadpath"]);

	/* Prepare information about directory or selection of target subdirectory */
	$subfolders_item = null;
	$styles1 = "";
	if ( $widths["subfolders_label"] != "" ) $styles1 .= 'width: '.$widths["subfolders_label"].'; ';
	if ( $heights["subfolders_label"] != "" ) $styles1 .= 'height: '.$heights["subfolders_label"].'; ';
	if ( $styles1 != "" ) $styles1 = ' style="'.$styles1.'"';
	$styles2 = "border: 1px solid; border-color: #BBBBBB;";
	if ( $widths["subfolders_select"] != "" ) $styles2 .= 'width: '.$widths["subfolders_select"].'; ';
	if ( $heights["subfolders_select"] != "" ) $styles2 .= 'height: '.$heights["subfolders_select"].'; ';
	$styles2 = ' style="'.$styles2.'"';
	if ( $params["testmode"] == "true" ) {
		$subfolders_item["title"] = 'inline_upload_subfolders_'.$params["uploadid"];
		$subfolders_item["hidden"] = false;
		$subfolders_item["line1"] = '<span class="file_item_clean"'.$styles1.'>'.$params["targetfolderlabel"].' </span>';
		$subfolders_item["line2"] = '<select class="file_item_clean"'.$styles2.' id="'.$selectsubdir.'" onchange="javascript: document.getElementById(\''.$hiddeninput.'\').value = document.getElementById(\''.$selectsubdir.'\').selectedIndex;">';

		$subfolders_item["line3"] = "\t".'<option>'.IU_NOTIFY_TESTMODE.'</option>';
		$subfolders_lastline = 4;
		$subfolders_item["line".$subfolders_lastline] = '</select>';
	}
	elseif ( $params["askforsubfolders"] == "true" ) {
		$subfolders = explode(",", $params["subfoldertree"]);
		if ( count($subfolders) == 0 ) { $subfolders = array ( iu_upload_plugin_directory($params["uploadpath"]) ); }
		if ( count($subfolders) == 1 && trim($subfolders[0]) == "" ) { $subfolders = array ( iu_upload_plugin_directory($params["uploadpath"]) ); }
		$subfolders_item["title"] = 'inline_upload_subfolders_'.$params["uploadid"];
		$subfolders_item["hidden"] = false;
		$subfolders_item["line1"] = '<span class="file_item_clean"'.$styles1.'>'.$params["targetfolderlabel"].' </span>';
		$subfolders_item["line2"] = '<select class="file_item_clean"'.$styles2.' id="'.$selectsubdir.'" onchange="javascript: document.getElementById(\''.$hiddeninput.'\').value = document.getElementById(\''.$selectsubdir.'\').selectedIndex;">';
		$subfolders_lastline = 3;
		$dir_levels = array ( iu_upload_plugin_directory($params["uploadpath"]) );
		$subfolder_paths = array ( );
		$prev_level = 0;
		foreach ($subfolders as $subfolder) {
			$subfolder = trim($subfolder);			
			$star_count = 0;
			$start_spaces = "";
			while ( $star_count < strlen($subfolder) ) {
				if ( substr($subfolder, $star_count, 1) == "*" ) {
					$star_count ++;
					$start_spaces .= "&nbsp;&nbsp;&nbsp;";
				}
				else break;
			}
			if ( $star_count - $prev_level <= 1 ) {
				$subfolder = substr($subfolder, $star_count, strlen($subfolder) - $star_count);
				$subfolder_items = explode('/', $subfolder);
				if ( $subfolder_items[1] != "" ) {
					$subfolder_dir = $subfolder_items[0];
					$subfolder_label = $subfolder_items[1];
				}
				else {
					$subfolder_dir = $subfolder;
					$subfolder_label = $subfolder;
				}
				if ( count($dir_levels) > $star_count ) $dir_levels[$star_count] = $subfolder_dir;
				else array_push($dir_levels, $subfolder_dir);
				$subfolder_path = "";
				for ( $i_count = 1; $i_count <= $star_count; $i_count++) {
					$subfolder_path .= $dir_levels[$i_count].'/';
				}
				array_push($subfolder_paths, $subfolder_path);
				$subfolders_item["line".$subfolders_lastline] = "\t".'<option>'.$start_spaces.$subfolder_label.'</option>';
				$subfolders_lastline ++;
				$prev_level = $star_count;
			}
		}
		$subfolders_item["line".$subfolders_lastline] = '</select>';
	}
	else if ( $params["showtargetfolder"] == "true" ) {
		$upload_directory = iu_upload_plugin_directory($params["uploadpath"]);
		$subfolders_item["title"] = 'inline_upload_subfolders_'.$params["uploadid"];
		$subfolders_item["hidden"] = false;
		$subfolders_item["line1"] = '<span'.$styles1.'>'.$params["targetfolderlabel"].': <strong>'.$upload_directory.'</strong></span>';
	}

//	below this line no other changes to params array are allowed
//____________________________________________________________________________________________________________________________________________________________________________________

	if ( $params['forceclassic'] != "true" ) {	
//**************section to put additional options inside params array**************
		$params['subfoldersarray'] = $subfolder_paths;
		$params['subdir_selection_index'] = "-1";
//**************end of section of additional options inside params array**************


//**************section to save params as Wordpress options**************
//		every params array is indexed (uniquely identified) by three fields:
//			- the page that contains the shortcode
//			- the id of the shortcode instance (because there may be more than one instances of the shortcode inside a page)
//			- the user that views the plugin (because some items of the params array are affected by the user name)
//		the wordpress option "iu_params_index" holds an array of combinations of these three fields, together with a randomly generated string that corresponds to these fields.
//		the wordpress option "iu_params_xxx", where xxx is the randomly generated string, holds the params array (encoded to string) that corresponds to this string.
//		the structure of the "iu_params_index" option is as follows: "a1||b1||c1||d1&&a2||b2||c2||d2&&...", where
//			- a is the randomly generated string (16 characters)
//			- b is the page id
//			- c is the shortcode id
//			- d is the user name
		$params_index = iu_generate_current_params_index($params["uploadid"], $user->user_login);
		$params_str = iu_encode_array_to_string($params);
		update_option('iu_params_'.$params_index, $params_str);
		$upload_type = "button";
		$ajax_params['shortcode_id'] = $params["uploadid"];
		$ajax_params['params_index'] = $params_index;
		$ajax_params['max_time_limit'] = ini_get("max_input_time");
		$ajax_params["fail_colors"] = $params["failmessagecolors"];
		$ajax_params["error_message_failed"] = IU_ERROR_UPLOAD_FAILED_WHILE;
		$ajax_params["error_message_cancelled"] = IU_ERROR_UPLOAD_CANCELLED;
		$ajax_params["error_message_unknown"] = IU_ERROR_UNKNOWN." ".IU_ERROR_CONTACT_ADMIN;
		$ajax_params["error_message_timelimit"] = IU_ERROR_FILE_PHP_TIME;
		$ajax_params["error_message_admin_timelimit"] = IU_ERROR_ADMIN_FILE_PHP_TIME;
		$ajax_params["open_url"] = site_url().'/wp-admin/admin-ajax.php';
		$ajax_params_str = iu_encode_array_to_string($ajax_params);
		$upload_clickaction = 'iu_HTML5UploadFile('.$params["uploadid"].', \''.$ajax_params_str.'\')';
		$upload_directaction = $upload_clickaction;
	}
	$upload_onclick = ' onclick="'.$upload_clickaction.'"';

	/* Prepare the title */
	$title_item = null;
	if ( $params["uploadtitle"] ) {
		$title_item["title"] = 'inline_upload_title_'.$params["uploadid"];
		$title_item["hidden"] = false;
		$styles = "";
		if ( $widths["title"] != "" ) $styles .= 'width: '.$widths["title"].'; ';
		if ( $heights["title"] != "" ) $styles .= 'height: '.$heights["title"].'; ';
		if ( $styles != "" ) $styles = ' style="'.$styles.'"';
		$title_item["line1"] = '<span class="file_title_clean"'.$styles.'>'.$params["uploadtitle"].'</span>';
	}
	/* Prepare the upload form */
	$textbox_item["title"] = 'inline_upload_textbox_'.$params["uploadid"];
	$textbox_item["hidden"] = false;
	$styles = "";
	if ( $widths["filename"] != "" ) $styles .= 'width: '.$widths["filename"].'; ';
	if ( $heights["filename"] != "" ) $styles .= 'height: '.$heights["filename"].'; ';
	if ( $styles != "" ) $styles = ' style="'.$styles.'"';
	$textbox_item["line1"] = '<input type="text" id="'.$textfile.'" class="file_input_textbox"'.$styles.' readonly="readonly"/>';
	$uploadform_item["title"] = 'inline_upload_form_'.$params["uploadid"];
	$uploadform_item["hidden"] = false;
	$styles_form = "";
	$styles = "";
	if ( $widths["selectbutton"] != "" ) $styles .= 'width: '.$widths["selectbutton"].'; ';
	if ( $heights["selectbutton"] != "" ) $styles .= 'height: '.$heights["selectbutton"].'; ';
	if ( $styles != "" ) $styles_form = ' style="'.$styles.'"';
	$uploadform_item["line1"] = '<form class="file_input_uploadform" id="'.$uploadform.'" name="'.$uploadform.'" method="post" enctype="multipart/form-data"'.$styles_form.'>';
	if ( $params["testmode"] == "true" ) $styles .= 'z-index: 500;';
	if ( $styles != "" ) $styles = ' style="'.$styles.'"';
	if ( $params["testmode"] == "true" ) $uploadform_item["line2"] = "\t".'<input align="center" type="button" id="'.$input.'" value="'.$params["selectbutton"].'" class="file_input_button"'.$styles.' onmouseout="javascript: document.getElementById(\''.$input.'\').className = \'file_input_button\'" onmouseover="javascript: document.getElementById(\''.$input.'\').className = \'file_input_button_hover\'" onclick="alert(\''.IU_NOTIFY_TESTMODE.'\');" />';
	else $uploadform_item["line2"] = "\t".'<input align="center" type="button" id="'.$input.'" value="'.$params["selectbutton"].'" class="file_input_button"'.$styles.'/>';
	if ( $params["singlebutton"] == "true" )
		$uploadform_item["line3"] = "\t".'<input type="file" class="file_input_hidden" name="'.$uploadedfile.'" id="'.$upfile.'" tabindex="1" onchange="javascript: document.getElementById(\''.$textfile.'\').value = this.value.replace(/c:\\\\fakepath\\\\/i, \'\'); if (this.value != \'\') {'.$upload_directaction.'}" onmouseout="javascript: document.getElementById(\''.$input.'\').className = \'file_input_button\'" onmouseover="javascript: document.getElementById(\''.$input.'\').className = \'file_input_button_hover\'" onclick="javascript: document.getElementById(\''.$message_label.'\').innerHTML = \'\'; document.getElementById(\'inline_upload_message_'.$params["uploadid"].'\').style.display=\'none\'; this.value = \'\'; document.getElementById(\''.$textfile.'\').value = \'\';" />';
	else
		$uploadform_item["line3"] = "\t".'<input type="file" class="file_input_hidden" name="'.$uploadedfile.'" id="'.$upfile.'" tabindex="1" onchange="javascript: document.getElementById(\''.$textfile.'\').value = this.value.replace(/c:\\\\fakepath\\\\/i, \'\');" onmouseout="javascript: document.getElementById(\''.$input.'\').className = \'file_input_button\'" onmouseover="javascript: document.getElementById(\''.$input.'\').className = \'file_input_button_hover\'" onclick="javascript: document.getElementById(\''.$message_label.'\').innerHTML = \'\'; document.getElementById(\'inline_upload_message_'.$params["uploadid"].'\').style.display=\'none\'; this.value = \'\'; document.getElementById(\''.$textfile.'\').value = \'\';" />';
	$uploadform_item["line4"] = "\t".'<input type="hidden" id="'.$hiddeninput.'" name="'.$hiddeninput.'" value="" />';
	$uploadform_item["line5"] = '</form>';
	$submit_item["title"] = 'inline_upload_submit_'.$params["uploadid"];
	$submit_item["hidden"] = false;
	$styles = "";
	if ( $widths["uploadbutton"] != "" ) $styles .= 'width: '.$widths["uploadbutton"].'; ';
	if ( $heights["uploadbutton"] != "" ) $styles .= 'height: '.$heights["uploadbutton"].'; ';
	if ( $styles != "" ) $styles = ' style="'.$styles.'"';
	if ( $params["testmode"] == "true" ) $submit_item["line1"] = '<input align="center" type="'.$upload_type.'" id="'.$upload.'" name="'.$upload.'" value="'.$params["uploadbutton"].'" class="file_input_submit" onclick="alert(\''.IU_NOTIFY_TESTMODE.'\');"'.$styles.' />';
	else $submit_item["line1"] = '<input align="center" type="'.$upload_type.'" id="'.$upload.'" name="'.$upload.'" value="'.$params["uploadbutton"].'" class="file_input_submit"'.$upload_onclick.$styles.' />';
	/* Prepare the progress bar */
	$progressbar_item["title"] = 'inline_upload_progressbar_'.$params["uploadid"];
	$progressbar_item["hidden"] = ( $params["testmode"] != "true" );
	$styles = "";
	if ( $widths["progressbar"] != "" ) $styles .= 'width: '.$widths["progressbar"].'; ';
	if ( $heights["progressbar"] != "" ) $styles .= 'height: '.$heights["progressbar"].'; ';
	if ( $styles != "" ) $styles = ' style="'.$styles.'"';
	$progressbar_item["line1"] = '<div id="'.$progress_bar.'" class="file_progress_bar"'.$styles.'>';
	$progressbar_item["line2"] = "\t".'<img id="'.$progress_bar.'_inner'.'" class="file_progress_bar_inner" style="width:100%;" />';
	$progressbar_item["line3"] = '</div>';
	/* Prepare the message */
	$message_item["title"] = 'inline_upload_message_'.$params["uploadid"];
	$message_item["hidden"] = ( $params["testmode"] != "true" );
	$styles = "";
	if ( $widths["message"] != "" ) $styles .= 'width: '.$widths["message"].'; ';
	if ( $heights["message"] != "" ) $styles .= 'height: '.$heights["message"].'; ';
	if ( $styles != "" ) $styles = ' style="'.$styles.'"';
	if ( $params["testmode"] == "true" ) $message_item["line1"] = '<span id="'.$message_label.'" class="file_messagebox_inner"'.$styles.'><strong>This is a test message. This is a test message. This is a test message. This is a test message.</strong></span>';
	else $message_item["line1"] = '<span id="'.$message_label.'" class="file_messagebox_inner"'.$styles.'></span>';

	/* Compose the html code for the plugin */
	$inline_upload_output = "";
	$inline_upload_output .= '<div id="inline_upload_block_'.$params["uploadid"].'" class="file_div_clean">';  
	$itemplaces = explode("/", $params["placements"]);
	foreach ( $itemplaces as $section ) {
		$items_in_section = explode("+", trim($section));
		$section_array = array( );
		foreach ( $items_in_section as $item_in_section ) {
			$item_in_section = strtolower(trim($item_in_section));
			if ( $item_in_section == "title" ) array_push($section_array, $title_item);
			elseif ( $item_in_section == "filename" ) array_push($section_array, $textbox_item);
			elseif ( $item_in_section == "selectbutton" ) array_push($section_array, $uploadform_item);
			elseif ( $item_in_section == "uploadbutton" && $params["singlebutton"] != "true" ) array_push($section_array, $submit_item);
			elseif ( $item_in_section == "subfolders" ) array_push($section_array, $subfolders_item);
			elseif ( $item_in_section == "progressbar" ) array_push($section_array, $progressbar_item);
			elseif ( $item_in_section == "message" ) array_push($section_array, $message_item);
		}
		$inline_upload_output .= call_user_func_array("iu_add_div", $section_array);
	}
	$inline_upload_output .= '</div>';  

//	The plugin uses sessions in order to detect if the page was loaded due to file upload or
//	because the user pressed the Refresh button (or F5) of the page.
//	In the second case we do not want to perform any file upload, so we abort the rest of the script.
	if ( $_SESSION['iu_check_refresh_'.$params["uploadid"]] != "form button pressed" ) {
		$_SESSION['iu_check_refresh_'.$params["uploadid"]] = 'do not process';
		return $inline_upload_output."\n";
	}
	$_SESSION['iu_check_refresh_'.$params["uploadid"]] = 'do not process';
	$params["upload_start_time"] = $_SESSION['iu_start_time_'.$params["uploadid"]];

//	The plugin uses two ways to upload the file:
//		- The first one uses classic functionality of an HTML form (highest compatibility with browsers but few capabilities).
//		- The second uses ajax (HTML5) functionality (medium compatibility with browsers but many capabilities, like no page refresh and progress bar).
//	The plugin loads using ajax functionality by default, however if it detects that ajax functionality is not supported, it will automatically switch to classic functionality. 
//	The next line checks to see if the form was submitted using ajax or classic functionality.
//	If the uploaded file variable stored in $_FILES ends with "_redirected", then it means that ajax functionality is not supported and the plugin must switch to classic functionality. 
	if ( isset($_FILES[$uploadedfile.'_redirected']) ) $params['forceclassic'] = "true";

	if ( $params['forceclassic'] != "true" ) return $inline_upload_output."\n";

//	The section below is executed when using classic upload methos
	$params['subdir_selection_index'] = -1;
	if ( isset( $_POST[$hiddeninput] ) ) $params['subdir_selection_index'] = $_POST[$hiddeninput];

	$iu_process_file_array = iu_process_file($params, 'no_ajax');

	$message_flattened = str_replace(array('"', "\n"), array('\'', "\\n"), trim($iu_process_file_array["message"]));
	$admin_message_flattened = str_replace(array('"', "\n"), array('\'', "\\n"), trim($iu_process_file_array["admin_messages"]));
	$fail_message_flattened = str_replace(array('"', "\n"), array('\'', "\\n"), trim(IU_ERROR_UNKNOWN." ".IU_ERROR_CONTACT_ADMIN));

	$ProcessUploadComplete_functiondef = 'function(){iu_ProcessUploadComplete('.$params["uploadid"].', "'.$iu_process_file_array["color"].'", "'.$iu_process_file_array["bgcolor"].'", "'.$iu_process_file_array["borcolor"].'", "'.$message_flattened.'", "'.$admin_message_flattened.'", "'.$fail_message_flattened.'", "'.$iu_process_file_array["update_wpfilebase"].'", "'.$iu_process_file_array["redirect_link"].'", "'.$iu_process_file_array["upload_finish_time"].'");}';
	$inline_upload_output .= '<script type="text/javascript">window.onload='.$ProcessUploadComplete_functiondef.'</script>';
	

	return $inline_upload_output."\n";
}

function iu_process_file($params, $method) {
	$user = wp_get_current_user();
	$upload_path_ok = false;
	$allowed_file_ok = false;
	$size_file_ok = false;
	$uploadedfile = 'uploadedfile_'.$params["uploadid"];
	$hiddeninput = 'hiddeninput_'.$params["uploadid"];
	$allowed_patterns = explode(",",$params["uploadpatterns"]);
	$params_output_array['shortcode_id'] = $params["uploadid"];
	$params_output_array['color'] = "black";
	$params_output_array['bgcolor'] = "#F5F5F5";
	$params_output_array['borcolor'] = "#D3D3D3";
	$params_output_array['message'] = "";
	$params_output_array['message_type'] = "";
	$params_output_array['update_wpfilebase'] = "";
	$params_output_array['redirect_link'] = "";
	$params_output_array['admin_messages'] = "";
	$params_output_array['upload_finish_time'] = "";

	if ( isset($_FILES[$uploadedfile.'_redirected']) ) $uploadedfile .= '_redirected';

	if ( $params["askforsubfolders"] == "true" && $params['subdir_selection_index'] >= 0 ) {
		if ( substr($params["uploadpath"], -1, 1) == "/" ) $params["uploadpath"] .= $params['subfoldersarray'][$params['subdir_selection_index']];
		else $params["uploadpath"] .= '/'.$params['subfoldersarray'][$params['subdir_selection_index']];
	}

	/* Get uploaded file size in Mbytes */
	$upload_file_size = filesize($_FILES[$uploadedfile]['tmp_name']) / 1024 /1024;

	if ( $upload_file_size > 0 ) {

		/* Check if upload path exist */
		if ( is_dir( iu_upload_plugin_full_path($params) ) ) {		
			$upload_path_ok = true;
		}
		else if ( $params["createpath"] == "true" ) {
			$iu_create_directory_ret = iu_create_directory(iu_upload_plugin_full_path($params), $params["accessmethod"], $params["ftpinfo"], $params["adminmessages"]);
			if ( $iu_create_directory_ret != "" ) {
				$params_output_array['admin_messages'] .= $iu_create_directory_ret."\n";
			}
			if ( is_dir( iu_upload_plugin_full_path($params) ) ) {		
				$upload_path_ok = true;
			}
		}

		/* File name control */
		foreach ($allowed_patterns as $allowed_pattern) {
			if ( iu_upload_plugin_wildcard_match( $allowed_pattern, $_FILES[$uploadedfile]['name']) ) {
				$allowed_file_ok = true;
				break ;
			}
		}

		/* File size control */
		if ( $upload_file_size <= $params["maxsize"] ) {
			$size_file_ok = true;
		}
	
		if ( !$upload_path_ok or !$allowed_file_ok or !$size_file_ok ) {
			$params_output_array['message_type'] = "error";
			$params_output_array['message'] = IU_ERROR_UPLOAD_FAILED;

			if ( !$upload_path_ok ) $params_output_array['message'] .= "<br />".IU_ERROR_DIR_EXIST;
			if ( !$allowed_file_ok ) $params_output_array['message'] .= "<br />".IU_ERROR_FILE_ALLOW;
			if ( !$size_file_ok ) $params_output_array['message'] .= "<br />".IU_ERROR_FILE_PLUGIN_SIZE;
		}
	}
	else {
//		This block is executed when there is an error
		$upload_error = $_FILES[$uploadedfile]['error'];
		if ( $upload_error == 1 ) {
			$message_text = IU_ERROR_FILE_PHP_SIZE;
			if ( $params["adminmessages"] ) $params_output_array['admin_messages'] .= IU_ERROR_ADMIN_FILE_PHP_SIZE."\n";
		}
		elseif ( $upload_error == 2 ) $message_text = IU_ERROR_FILE_HTML_SIZE;
		elseif ( $upload_error == 3 ) $message_text = IU_ERROR_FILE_PARTIAL;
		elseif ( $upload_error == 4 ) $message_text = IU_ERROR_FILE_NOTHING;
		elseif ( $upload_error == 6 ) $message_text = IU_ERROR_DIR_NOTEMP;
		elseif ( $upload_error == 7 ) $message_text = IU_ERROR_FILE_WRITE;
		elseif ( $upload_error == 8 ) $message_text = IU_ERROR_UPLOAD_STOPPED;
		else {
			$upload_time_limit = ini_get("max_input_time");
			$params_output_array['upload_finish_time'] = $params["upload_start_time"] + $upload_time_limit * 1000;
			$message_text = IU_ERROR_FILE_PHP_TIME;
			if ( $params["adminmessages"] ) $params_output_array['admin_messages'] .= IU_ERROR_ADMIN_FILE_PHP_TIME."\n";
		}
		$params_output_array['message_type'] = "error";
		$params_output_array['message'] .= $message_text."check!!!";
	}

	if ( $upload_path_ok and $allowed_file_ok and $size_file_ok ) {

		if ( is_uploaded_file($_FILES[$uploadedfile]['tmp_name']) ) {
	
			if ( $allowed_file_ok and $size_file_ok ) {
				$file_copied = false;
				$message_processed = false;
				$source_path = $_FILES[$uploadedfile]['tmp_name'];
				$only_filename = iu_upload_plugin_clean( $_FILES[$uploadedfile]['name'] );
				$target_path = iu_upload_plugin_full_path($params).$only_filename;

				$search = array ('/%filename%/', '/%filepath%/');	 
				$replace = array ($only_filename, $target_path);
				$success_message =  preg_replace($search, $replace, $params["successmessage"]);

				if ($source_path) {
					$file_exists = file_exists($target_path);
					if ( !$file_exists || $params["dublicatespolicy"] == "" || $params["dublicatespolicy"] == "overwrite" ) {
						//redirect echo in internal buffer to receive and process any unwanted warning messages from move_uploaded_file
						ob_start();
						ob_clean();
						$iu_upload_file_ret = iu_upload_file($source_path, $target_path, $params["accessmethod"], $params["ftpinfo"], $params["adminmessages"]);
						$file_copied = $iu_upload_file_ret["uploaded"];
						//process warning messages from move_uploaded_file
						$echo_message = ob_get_contents();
						if ( $echo_message != "" && !$file_copied ) {
							$params_output_array['message_type'] = "error";
							if ( stristr($echo_message, "warning") && stristr($echo_message, "permission denied") && stristr($echo_message, "unable to move") ) {
								$params_output_array['message'] = IU_ERROR_DIR_PERMISSION;
								if ( $params["adminmessages"] ) $params_output_array['admin_messages'] .= IU_ERROR_ADMIN_DIR_PERMISSION."\n";
							}
							else { 
								$params_output_array['message'] = IU_ERROR_FILE_MOVE;
								if ( $params["adminmessages"] ) $params_output_array['admin_messages'] .= strip_tags($echo_message)."\n";
							}
							$message_processed = true;
						}
						//finish redirecting of echo to internal buffer
						ob_end_clean();
						if ( $iu_upload_file_ret["admin_message"] != "" ) {
							$params_output_array['admin_messages'] .= $iu_upload_file_ret["admin_message"]."\n";
						}
					}
					else if ( $file_exists && $params["dublicatespolicy"] == "maintain both" ) {
						$full_path = iu_upload_plugin_full_path($params);
						$name_part = $only_filename;
						$ext_part = "";
						$dot_pos = strrpos($name_part, ".");
						if ( $dot_pos ) {
							$ext_part = substr($name_part, $dot_pos);
							$name_part = substr($name_part, 0, $dot_pos);
						}
						if ( $params["uniquepattern"] != "datetimestamp" ) {
							$unique_id = 1;
							do {
								$unique_id += 1;
								$only_filename = $name_part . "(" . $unique_id . ")" . $ext_part;
								$target_path = $full_path . $only_filename;
							}
							while ( file_exists($target_path) );
						}
						else {
							$current_datetime = gmdate("U") - 1;
							do {
								$current_datetime += 1;
								$only_filename = $name_part . "-" . gmdate("YmdHis", $current_datetime) . $ext_part;
								$target_path = $full_path . $only_filename;
							}
							while ( file_exists($target_path) );
						}
						//redirect echo in internal buffer to receive and process any unwanted warning messages from move_uploaded_file
						ob_start();
						ob_clean();
						$iu_upload_file_ret = iu_upload_file($source_path, $target_path, $params["accessmethod"], $params["ftpinfo"], $params["adminmessages"]);
						$file_copied = $iu_upload_file_ret["uploaded"];
						//process warning messages from move_uploaded_file
						$echo_message = ob_get_contents();
						if ( $echo_message != "" && !$file_copied ) {
							$params_output_array['message_type'] = "error";
							if ( stristr($echo_message, "warning") && stristr($echo_message, "permission denied") && stristr($echo_message, "unable to move") ) {
								$params_output_array['message'] = IU_ERROR_DIR_PERMISSION;
								if ( $params["adminmessages"] ) $params_output_array['admin_messages'] .= IU_ERROR_ADMIN_DIR_PERMISSION."\n";
							}
							else { 
								$params_output_array['message'] = IU_ERROR_FILE_MOVE;
								if ( $params["adminmessages"] ) $params_output_array['admin_messages'] .= strip_tags($echo_message)."\n";
							}
							$message_processed = true;
						}
						//finish redirecting of echo to internal buffer
						ob_end_clean();
						if ( $iu_upload_file_ret["admin_message"] != "" ) {
							$params_output_array['admin_messages'] .= $iu_upload_file_ret["admin_message"]."\n";
						}
						if ( $file_copied ) {
							$message_text = '<strong>'.$success_message.'</strong>';
							$params_output_array['message_type'] = "success";
							$params_output_array['message'] .= $message_text;
							$message_processed = true;
						}
					}
					else {
						$message_text = IU_WARNING_FILE_EXISTS;
						$params_output_array['message_type'] = "error";
						$params_output_array['message'] .= $message_text;
						$message_processed = true;
						$file_copied = false;
					}
				}

				if ( $file_copied ) {
					if ( $params["filebaselink"] == "true" ) {			
						$filebaseurl = WP_SITEURL;
						if ( substr($filebaseurl, -1, 1) == "/" ) $filebaseurl = substr($filebaseurl, 0, strlen($filebaseurl) - 1);


//						$filebaseurl .= "/wp-admin/tools.php";					
						$params_output_array['update_wpfilebase'] = $filebaseurl;
					} 
	
					if ( $params["notify"] == "true" && $params["notifyrecipients"] != "" ) {				
						$search = array ('/%username%/', '/%useremail%/', '/%filename%/', '/%filepath%/');	 
						$replace = array ($user->user_login, $user->user_email, $only_filename, $target_path);
						$notifysubject =  preg_replace($search, $replace, $params["notifysubject"]);
						$notifymessage =  preg_replace($search, $replace, $params["notifymessage"]);
						$notifyrecipients =  preg_replace('/%useremail%/', $user->user_email, $params["notifyrecipients"]);
						if ( $params["attachfile"] == "true" ) {
							$attachments = array ($target_path);
							wp_mail($notifyrecipients, $notifysubject, $notifymessage, '', $attachments); 
						}
						else {
							wp_mail($notifyrecipients, $notifysubject, $notifymessage); 
						}
					} 

					if ( $params["redirect"] == "true" && $params["redirectlink"] != "" ) {
						/* Define dynamic redirect link from variables */
						$search = array ('/%filename%/');	 
						$replace = array ($only_filename);
						$params_output_array['redirect_link'] =  preg_replace($search, $replace, $params["redirectlink"]);
					}
					else if ( !$message_processed ) {
						$message_text = '<strong>'.$success_message.'</strong>';
						$params_output_array['message_type'] = "success";
						$params_output_array['message'] .= $message_text;
					}
				}
				else if ( !$message_processed ) {
					$message_text = IU_ERROR_UNKNOWN;
					$params_output_array['message_type'] = "error";



					$params_output_array['message'] .= $message_text;
				}

				/* Delete temporary file (in "/xampp/tmp/") */
//				unlink($source_path);			
			}
		}
		else {
			$message_text = IU_ERROR_UNKNOWN;
			$params_output_array['message_type'] = "error";
			$params_output_array['message'] .= $message_text;
		}
	}

	if ( strstr( $params_output_array['message_type'], "error" ) ) {
		$color_array = explode(",", $params['failmessagecolors']);
		$params_output_array['color'] = $color_array[0];
		$params_output_array['bgcolor'] = $color_array[1];
		$params_output_array['borcolor'] = $color_array[2];
	}
	elseif ( strstr( $params_output_array['message_type'], "warning" ) ) {
		$color_array = explode(",", $params['failmessagecolors']);
		$params_output_array['color'] = $color_array[0];
		$params_output_array['bgcolor'] = $color_array[1];
		$params_output_array['borcolor'] = $color_array[2];
	}
	elseif ( strstr( $params_output_array['message_type'], "success" ) ) {
		$color_array = explode(",", $params['successmessagecolors']);
		$params_output_array['color'] = $color_array[0];
		$params_output_array['bgcolor'] = $color_array[1];
		$params_output_array['borcolor'] = $color_array[2];
	}

	if ( $method == 'no_ajax' ) return $params_output_array;
	else return iu_encode_array_to_string($params_output_array);
}

function iu_ajax_action_callback() {
	$user = wp_get_current_user();
	$arr = iu_get_params_fields_from_index($_POST['params_index']);
	if ( $user->user_login != $arr['user_login'] ) die();

	$params_str = get_option('iu_params_'.$arr['unique_id']);
	$params = iu_decode_array_from_string($params_str);
	$params['subdir_selection_index'] = $_POST['subdir_sel_index'];
	$_SESSION['iu_check_refresh_'.$params["uploadid"]] = 'do not process';

	echo iu_process_file($params, 'ajax');
	die(); 
}

?>
