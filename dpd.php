<?php

define( 'DPD_VERSION', '1.1' );
define( 'DPD__PLUGIN_URL', plugin_dir_url( __FILE__ ) );

wp_register_style( 'dpd.css', DPD__PLUGIN_URL . '_inc/dpd.min.css', array(), DPD_VERSION );
wp_enqueue_style( 'dpd.css');