//iu_GetHttpRequestObject: function that returns XMLHttpRequest object for various browsers
function iu_GetHttpRequestObject() {
	var xhr = null;
	try {
		xhr = new XMLHttpRequest(); 
	}
	catch(e) { 
		try {
			xhr = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e2) {
			try {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {}
		}
	}
	if (xhr == null && window.createRequest) {
		try {
			xmlhttp = window.createRequest();
		}
		catch (e) {}
	}
	return xhr;
}

//iu_RunWPFileBaseHttpRequest: function to update WP-FileBase plugin
function iu_RunWPFileBaseHttpRequest(filebaseurl) {
	var xmlhttp = iu_GetHttpRequestObject();
	if (xmlhttp == null) return;

	xmlhttp.open("GET", filebaseurl, false);
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4) {
		}
	}
	xmlhttp.send(null);
}

//iu_FormButtonPressed: function to inform the page to process the file after reloading
function iu_FormButtonPressed(shortcode_id) {
	var xmlhttp = iu_GetHttpRequestObject();
	if (xmlhttp == null) return;

	var d = new Date();
	xmlhttp.open("GET", "/wp-content/plugins/inline-upload/iu_response.php?shortcode_id=" + shortcode_id + "&start_time=" + d.getTime(), false);
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4) {
		}
	}
	xmlhttp.send(null);
}


//iu_Redirect: function to redirect to another url
function iu_Redirect(link) {
	window.location = link;
}

//iu_uploadProgress: function to update progress bar
function iu_uploadProgress(evt) {
	if (evt.lengthComputable) {
		var progbar_width = document.getElementById('progressbar_' + this.shortcode_id).clientWidth;
		if (progbar_width == 0) progbar_width = document.getElementById('progressbar_' + this.shortcode_id).offsetWidth;
		progbar_width -= 2;
		var percentComplete = Math.round(evt.loaded * progbar_width / evt.total);
		document.getElementById('progressbar_' + this.shortcode_id + '_inner').style.width = percentComplete.toString() + 'px';
	}
	else {
		document.getElementById('progressbar_' + this.shortcode_id + '_inner').style.width = '0px';
	}
}

//iu_uploadComplete: function that is called after successfull file upload
function iu_uploadComplete(evt) {
	if (evt.target.responseText == -1) {
		var error_colors = this.fail_colors.split(",");		
		iu_ProcessUploadComplete(this.shortcode_id, error_colors[0], error_colors[1], error_colors[2], this.error_message_timelimit, this.error_message_admin_timelimit, this.error_message_unknown, "", "", this.finish_time);
	}
	else {
		JSONtext = iu_plugin_decode_string(evt.target.responseText.replace(/^\s+|\s+$/g,""));
		var JSONobj = JSON.parse(JSONtext);
		document.getElementById('inline_upload_progressbar_' + JSONobj.shortcode_id).style.display = "none";
		iu_ProcessUploadComplete(JSONobj.shortcode_id, JSONobj.color, JSONobj.bgcolor, JSONobj.borcolor, JSONobj.message, JSONobj.admin_messages, this.error_message_unknown, JSONobj.update_wpfilebase, JSONobj.redirect_link, 0);
	}
}

//iu_ProcessUploadComplete: function to perform actions after successfull upload
function iu_ProcessUploadComplete(sid, txtcolor, bgcolor, borcolor, msg, admin_msg, fail_message, upd_wpfilebase, redirect, finish_time) {
	if (finish_time > 0) {
		upd_wpfilebase = "";
		redirect = "";
		var d = new Date();
		if (d.getTime() < finish_time) {
			msg = fail_message;
			admin_msg = "";
		}
	}
	if (upd_wpfilebase != "") {
		iu_RunWPFileBaseHttpRequest(upd_wpfilebase+"/wp-admin/tools.php?page=wpfilebase&action=sync&hash_sync=1");
		//addition to support newer versions of WP-Filebase
		iu_RunWPFileBaseHttpRequest(upd_wpfilebase+"/wp-admin/admin.php?page=wpfilebase_manage&action=sync&hash_sync=1");
	}
	if (redirect != "") iu_Redirect(redirect);
	else {
		document.getElementById('fileName_' + sid).value = '';
		document.getElementById('inline_upload_message_' + sid).style.display = "block";	
		document.getElementById('messagelabel_' + sid).style.color = txtcolor;	
		document.getElementById('messagelabel_' + sid).style.backgroundColor = bgcolor;	
		document.getElementById('messagelabel_' + sid).style.borderColor = borcolor;	
		document.getElementById('messagelabel_' + sid).innerHTML = msg;	
		if (admin_msg != "") {
			alert(admin_msg);
		}
	}
}

//iu_uploadFailed: function that is called if uploading fails
function iu_uploadFailed(evt) {
	alert(this.error_message_failed);
}

//iu_uploadCanceled: function that is called if uploading is cancelled
function iu_uploadCanceled(evt) {
	alert(this.error_message_cancelled);
} 

//iu_plugin_decode_string: function that decodes an encoded string
function iu_plugin_decode_string(str) {
	var i = 0;
	var newstr = "";
	var hex = "";
	for (i = 0; i < str.length; i += 2) {
		hex = str[i] + str[i + 1];
		newstr += String.fromCharCode(parseInt(hex, 16));
	}
	return newstr;
}

//iu_redirect_to_classic: function that switches to classic functionality (HTML upload form) if HTML5 is not supported
function iu_redirect_to_classic(shortcode_id, flag) {
	if (document.getElementById('fileName_' + shortcode_id).value == "")
		return;

	iu_FormButtonPressed(shortcode_id);
	if (flag == 1) document.getElementById('upfile_' + shortcode_id).name = 'uploadedfile_' + shortcode_id + '_redirected';
	document.getElementById('uploadform_' + shortcode_id).submit();
}

//iu_HTML5UploadFile: function that is called if the plugin is not using classic functionality
function iu_HTML5UploadFile(sid, JSONtext) {
	if (document.getElementById('fileName_' + sid).value == "")
		return;

	var xhr = iu_GetHttpRequestObject();
	if (xhr == null) {
		iu_redirect_to_classic(sid, 1);
		return;
	}

	JSONtext = iu_plugin_decode_string(JSONtext.replace(/^\s+|\s+$/g,""));
	var JSONobj = null;
	try {
		var JSONobj = JSON.parse(JSONtext);
	}
	catch(e) {}
	if (JSONobj == null) {
		iu_redirect_to_classic(sid, 1);
		return;
	}

	var fd = null;
	try {
		var fd = new FormData();
	}
	catch(e) {}
	if (fd == null) {
		iu_redirect_to_classic(sid, 1);
		return;
	}

	var subdir_sel_index = -1;
	if (document.getElementById('selectsubdir_' + sid) != null)
		subdir_sel_index = document.getElementById('selectsubdir_' + sid).selectedIndex;

	fd.append("uploadedfile_" + sid, document.getElementById('upfile_' + sid).files[0]);
	fd.append("action", "iu_ajax_action");
	fd.append("params_index", JSONobj.params_index);
	fd.append("subdir_sel_index", subdir_sel_index);

	// define variables
	var d = new Date();
	xhr.shortcode_id = sid;
	xhr.finish_time = d.getTime() + JSONobj.max_time_limit * 1000;
	xhr.fail_colors = JSONobj.fail_colors;
	xhr.error_message_failed = JSONobj.error_message_failed;
	xhr.error_message_cancelled = JSONobj.error_message_cancelled;
	xhr.error_message_unknown = JSONobj.error_message_unknown;
	xhr.error_message_timelimit = JSONobj.error_message_timelimit;
	xhr.error_message_admin_timelimit = JSONobj.error_message_admin_timelimit;
	xhr.upload.shortcode_id = sid;

	// event listners
	xhr.upload.addEventListener("progress", iu_uploadProgress, false);
	xhr.addEventListener("load", iu_uploadComplete, false);
	xhr.addEventListener("error", iu_uploadFailed, false);
	xhr.addEventListener("abort", iu_uploadCanceled, false);

	document.getElementById('inline_upload_progressbar_' + JSONobj.shortcode_id).style.display = "block";
	document.getElementById('progressbar_' + JSONobj.shortcode_id + '_inner').style.width = '0px';

//	xhr.open("POST", "/wp-admin/admin-ajax.php");
	xhr.open("POST", JSONobj.open_url);
	xhr.send(fd);
}

