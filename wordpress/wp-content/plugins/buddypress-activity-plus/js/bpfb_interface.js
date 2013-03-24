var _bpfbActiveHandler = false;

(function($){
$(function() {

var $form;
var $text;
var $textContainer;

/**
 * Photos insertion/preview handler.
 */
var BpfbPhotoHandler = function () {
	$container = $(".bpfb_controls_container");
	
	var createMarkup = function () {
		var html = '<div id="bpfb_tmp_photo"> </div>' +
			'<ul id="bpfb_tmp_photo_list"></ul>' + 
			'<input type="button" id="bpfb_add_remote_image" value="' + l10nBpfb.add_remote_image + '" /><div id="bpfb_remote_image_container"></div>' +
			'<input type="button" id="bpfb_remote_image_preview" value="' + l10nBpfb.preview + '" />';
		$container.append(html);
		
		var uploader = new qq.FileUploader({
			"element": $('#bpfb_tmp_photo')[0],
			"listElement": $('#bpfb_tmp_photo_list')[0],
			"allowedExtensions": ['jpg', 'jpeg', 'png', 'gif'],
			"action": ajaxurl,
			"params": {
				"action": "bpfb_preview_photo"
			},
			"onSubmit": function (id) {
				if (!parseInt(l10nBpfb._max_images)) return true; // Skip check
				id = parseInt(id);
				if (!id) id = $("img.bpfb_preview_photo_item").length;
				if (!id) return true;
				if (id < parseInt(l10nBpfb._max_images)) return true;
				if (!$("#bpfb-too_many_photos").length) $("#bpfb_tmp_photo").append(
					'<p id="bpfb-too_many_photos">' + l10nBpfb.images_limit_exceeded + '</p>'
				);
				return false;
			},
			"onComplete": createPhotoPreview,
			template: '<div class="qq-uploader">' + 
                '<div class="qq-upload-drop-area"><span>' + l10nBpfb.drop_files + '</span></div>' +
                '<div class="qq-upload-button">' + l10nBpfb.upload_file + '</div>' +
                '<ul class="qq-upload-list"></ul>' + 
             '</div>'
		});
		
		$("#bpfb_remote_image_preview").hide();
		$("#bpfb_tmp_photo").click(function () {
			if ($("#bpfb_add_remote_image").is(":visible")) $("#bpfb_add_remote_image").hide();
		});
		$("#bpfb_add_remote_image").click(function () {
			if (!$("#bpfb_remote_image_preview").is(":visible")) $("#bpfb_remote_image_preview").show();
			if ($("#bpfb_tmp_photo").is(":visible")) $("#bpfb_tmp_photo").hide();
			$("#bpfb_add_remote_image").val(l10nBpfb.add_another_remote_image);
			$("#bpfb_remote_image_container").append(
				'<input type="text" class="bpfb_remote_image" size="64" value="" /><br />'
			);
			$("#bpfb_remote_image_container .bpfb_remote_image").width($container.width());
		});
		$("#bpfb_remote_image_container .bpfb_remote_image").live('change', createRemoteImagePreview);
		$("#bpfb_remote_image_preview").click(createRemoteImagePreview);
	};
	
	var createRemoteImagePreview = function () {
		var imgs = [];
		$("#bpfb_remote_image_container .bpfb_remote_image").each(function () {
			imgs[imgs.length] = $(this).val();
		});
		$.post(ajaxurl, {"action":"bpfb_preview_remote_image", "data":imgs}, function (data) {
			var html = '';
			$.each(data, function() {
				html += '<img class="bpfb_preview_photo_item" src="' + this + '" width="80px" />' +
				'<input type="hidden" class="bpfb_photos_to_add" name="bpfb_photos[]" value="' + this + '" />';;
			});
			$('.bpfb_preview_container').html(html);
		});
		$('.bpfb_action_container').html(
			'<p><input type="button" class="button-primary bpfb_primary_button" id="bpfb_submit" value="' + l10nBpfb.add_photos + '" /> ' +
			'<input type="button" class="button" id="bpfb_cancel" value="' + l10nBpfb.cancel + '" /></p>'
		);
		$("#bpfb_cancel_action").hide();
	};
	
	var createPhotoPreview = function (id, fileName, resp) {
		if ("error" in resp) return false;
		var html = '<img class="bpfb_preview_photo_item" src="' + _bpfbTempImageUrl + resp.file + '" width="80px" />' +
			'<input type="hidden" class="bpfb_photos_to_add" name="bpfb_photos[]" value="' + resp.file + '" />';
		$('.bpfb_preview_container').append(html);
		$('.bpfb_action_container').html(
			'<p><input type="button" class="button-primary bpfb_primary_button" id="bpfb_submit" value="' + l10nBpfb.add_photos + '" /> ' +
			'<input type="button" class="button" id="bpfb_cancel" value="' + l10nBpfb.cancel + '" /></p>'
		);
		$("#bpfb_cancel_action").hide();
	};
	
	var removeTempImages = function (rti_callback) {
		var $imgs = $('input.bpfb_photos_to_add');
		if (!$imgs.length) return rti_callback();
		$.post(ajaxurl, {"action":"bpfb_remove_temp_images", "data": $imgs.serialize().replace(/%5B%5D/g, '[]')}, function (data) {
			rti_callback();
		});
	};
	
	var processForSave = function () {
		var $imgs = $('input.bpfb_photos_to_add');
		var imgArr = [];
		$imgs.each(function () {
			imgArr[imgArr.length] = $(this).val();
		});
		return {
			"bpfb_photos": imgArr//$imgs.serialize().replace(/%5B%5D/g, '[]')
		};
	};
	
	var init = function () {
		$container.empty();
		$('.bpfb_preview_container').empty();
		$('.bpfb_action_container').empty();
		$('#aw-whats-new-submit').hide();
		createMarkup();
	};
	
	var destroy = function () {
		removeTempImages(function() {
			$container.empty(); 
			$('.bpfb_preview_container').empty(); 	
			$('.bpfb_action_container').empty();
			$('#aw-whats-new-submit').show();
		});
	};
	
	removeTempImages(init);
	
	return {"destroy": destroy, "get": processForSave};
};


/* === End handlers  === */


/**
 * Main interface markup creation.
 */
function createMarkup () {
	var html = '<div class="bpfb_actions_container">' +
		'<div class="bpfb_toolbar_container">' +
			'<a href="#photos" class="bpfb_toolbarItem" title="' + l10nBpfb.add_photos + '" id="bpfb_addPhotos"><span>' + l10nBpfb.add_photos + '</span></a>' +
			'&nbsp;' +
		'</div>' +
		'<div class="bpfb_controls_container">' +
		'</div>' +
		'<div class="bpfb_preview_container">' +
		'</div>' +
		'<div class="bpfb_action_container">' +
		'</div>' +
		'<input type="button" id="bpfb_cancel_action" value="' + l10nBpfb.cancel + '" style="display:none" />' +
	'</div>';
	$form.wrap('<div class="bpfb_form_container" />');
	$textContainer.after(html);
}


/**
 * Initializes the main interface.
 */
function init () {
	$form = $("#whats-new-form");
	$text = $form.find('textarea[name="whats-new"]');
	$textContainer = $form.find('#whats-new-textarea');
	createMarkup();
	$('#bpfb_addPhotos').click(function () {
		if (_bpfbActiveHandler) _bpfbActiveHandler.destroy();
		_bpfbActiveHandler = new BpfbPhotoHandler();
		$("#bpfb_cancel_action").show();
		return false;
	});
	
	$('#bpfb_cancel_action').click(function () {
		_bpfbActiveHandler.destroy();
		$("#bpfb_cancel_action").hide();
		return false;
	});
	$('#bpfb_submit').live('click', function () {
		var params = _bpfbActiveHandler.get();
		var group_id = $('#whats-new-post-in').length ? $('#whats-new-post-in').val() : 0;
		$.post(ajaxurl, {
			"action": "bpfb_update_activity_contents", 
			"data": params, 
			"content": $text.val(), 
			"group_id": group_id
		}, function (data) {
			_bpfbActiveHandler.destroy();
			$text.val('');
			$('#activity-stream').prepend(data.activity);
			/**
			 * Handle image scaling in previews.
			 */
			$(".bpfb_final_link img").each(function () {
				$(this).width($(this).parents('div').width());
			});
		});
	});
	$('#bpfb_cancel').live('click', function () {
		_bpfbActiveHandler.destroy();
	});
}


// Only initialize if we're supposed to.
if (!('ontouchstart' in document.documentElement)) {
	if ($("#whats-new-form").is(":visible")) init();
}


/**
 * Handle image scaling in previews.
 */
$(".bpfb_final_link img").each(function () {
	$(this).width($(this).parents('div').width());
});

});
})(jQuery);