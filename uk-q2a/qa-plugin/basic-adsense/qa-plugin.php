<?php

/*
	Plugin Name: Basic AdSense
	Plugin URI: 
	Plugin Description: Provides a basic widget for displaying Google AdSense ads
	Plugin Version: 1.0
	Plugin Date: 2011-03-27
	Plugin Author: Question2Answer
	Plugin Author URI: http://www.question2answer.org/
	Plugin License: GPLv2
	Plugin Minimum Question2Answer Version: 1.4
	Plugin Update Check URI: 
*/


	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../../');
		exit;
	}


	qa_register_plugin_module('widget', 'qa-basic-adsense.php', 'qa_basic_adsense', 'Basic AdSense');
	

/*
	Omit PHP closing tag to help avoid accidental output
*/
