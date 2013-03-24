<?php

global $postTypes;
$postTypes = array (
	'Courses' => array(
		'labels' => array(
			'name'			=> _x('Courses', 'post type general name'),
			'singular_name' 	=> _x('Courses', 'post type singular name'),
			'add_new' 		=> _x('Add New', 'Course'),
			'search_items' 		=> __('Search Courses'),
			'not_found'		=>  __('No Courses Found'),
			'not_found_in_trash' 	=> __('No Courses Found in Trash'),
	
			'menu_name' 		=> 'Courses'
		),
		'public' 		=> true,
		'publicly_queryable' 	=> true,
		'show_in_menu' 		=> true,
		'show_in_nav_menus' 	=> true,
		'hierarchical' => true,
		'capability_type' 	=> 'page',
		'menu_position' 	=> 21,
		'supports' 		=> array('title', 'editor', 'excerpt', 'page-attributes')
	),

);

?>
