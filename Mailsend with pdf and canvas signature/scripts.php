<?php

add_action( 'wp_enqueue_scripts', 'codexin_canvas_scripts',999);

function codexin_canvas_scripts () {
	
	if (is_order_received_page()) {

		wp_enqueue_style( 'codexin-canvas', get_template_directory_uri() . '/vendors/canvas/css/signature-pad.css', false, '1.0','all');

		wp_enqueue_script( 'codexin-signature_pad-script', get_template_directory_uri() . '/vendors/canvas/js/signature_pad.umd.js', array ( 'jquery' ), 1.1, true);

		wp_enqueue_script( 'codexin-canvas-script', get_template_directory_uri() . '/vendors/canvas/js/app.js', array ( 'jquery' ), 1.1, true);
	
	}

}

