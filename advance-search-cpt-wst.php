<?php

/*
Plugin Name: Advance search for CPT - WST Website
Plugin URI: https://github.com/pedrokeilerbatistarojo/advance-search-cpt-wst
Description: Advance search for CPT and Custom fields WST website
Version: 1.0
Author: Pedro Keiler Batista Rojo <pedrokeilerbatistarojo>
Author URI: https://github.com/pedrokeilerbatistarojo
*/

use includes\AdvancedSearchService;

if (!defined('ABSPATH')) exit;

define('ADVANCED_SEARCH_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('ADVANCED_SEARCH_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once ADVANCED_SEARCH_PLUGIN_PATH . 'includes/AdvancedSearchService.php';

$service = new AdvancedSearchService();

function plugin_enqueue_styles(): void
{
    if (is_singular() && has_shortcode(get_post()->post_content, 'search')) {
        wp_enqueue_style('my-styles', plugins_url('assets/css/style.css', __FILE__));
    }
}

add_action('wp_enqueue_scripts', 'plugin_enqueue_styles');




