<?php

/*

Plugin Name: Castlegate IT Yoast Import
Plugin URI: https://github.com/castlegateit/cgit-wp-yoast-import
Description: Import titles and descriptions into Yoast from ACF SEO and SEO Redux.
Version: 1.0.2
Author: Castlegate IT
Author URI: https://www.castlegateit.co.uk/
Network: true

Copyright (c) 2018 Castlegate IT. All rights reserved.

*/

if (!defined('ABSPATH')) {
    wp_die('Access denied');
}

define('CGIT_YOAST_IMPORT_PLUGIN', __FILE__);

require_once __DIR__ . '/classes/autoload.php';

add_action('plugins_loaded', function () {
    $plugin = new \Cgit\YoastImport\Plugin;

    do_action('cgit_yoast_import_plugin', $plugin);
    do_action('cgit_yoast_import_loaded');
});
