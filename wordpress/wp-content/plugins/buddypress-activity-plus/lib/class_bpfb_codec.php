<?php
/**
 * Bpfb shortcode coder/decoder class.
 *
 * Responsible for handling all things shortcode:
 * 1) Resgisters shortcode decoding procedures
 * 2) Decodes shortcodes and creates proper markup on post render
 * 3) Encodes requests into shortcodes on post save
 */
class BpfbCodec {

	/**
	 * Processes images-type shortcode and create proper markup.
	 */
	function process_images_tag ($atts, $content) {
		$images = explode("\n", trim(strip_tags($content)));
		//return var_export($images,1);
		$activity_id = bp_get_activity_id();
		global $blog_id;
		$activity_blog_id = $blog_id;
		$use_thickbox = defined('BPFB_USE_THICKBOX') ? esc_attr(BPFB_USE_THICKBOX) : 'thickbox';
		if ($activity_id) {
			$activity_blog_id = bp_activity_get_meta($activity_id, 'bpfb_blog_id');
		}
		ob_start();
		$out = ob_get_clean();
		return $out;
	}


	/**
	 * Registers shotcode processing procedures.
	 */
	function register () {
		$me = new BpfbCodec;
		add_shortcode('bpfb_images', array($me, 'process_images_tag'));

		// A fix for Ray's "oEmbed for BuddyPress" and similar plugins
		add_filter('bp_get_activity_content_body', 'do_shortcode', 1);
		// RSS feed processing
		add_filter('bp_get_activity_feed_item_description', 'do_shortcode');
	}
}