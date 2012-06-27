<?php 
/*-------------------------------------------------------------------
Plugin Name: Translator Box
Plugin URI: http://www.translatorbox.com/
Description: This plugin adds translation functionality to posts and pages. For information how to use it please check the <a href="options-general.php?page=translation_box_options" title="Translation Box">Help page</a>
Author: Ivan Kazandzhiev
Version: 0.1
Author URI: http://www.kazandjiev.com/
--------------------------------------------------------------------*/

require_once('includes.php');

add_action('wp_enqueue_scripts', 'tr_box_scripts');
add_action('admin_menu','translation_box_options');
add_action( 'wp_ajax_nopriv_tr-box-request', 'tr_box_ajax_call' );
add_action( 'wp_ajax_tr-box-request', 'tr_box_ajax_call' );
add_shortcode('translation_box','tr_box_translate');

/* EOF */