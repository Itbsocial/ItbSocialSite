<?php


/*
Plugin Name: EasyLearning
Description: Allows teachers and students to share educational information
Author: Johnathon Daly
*/

include_once('config.php');

add_action('init', 'el_initiate');

function el_initiate () {

	EasyLearning::createPostTypes();

	do_action('el_initiate');
}


register_activation_hook(__FILE__, array('EasyLearning', 'install') );

register_deactivation_hook(__FILE__, array('EasyLearning', 'uninstall'));
?>