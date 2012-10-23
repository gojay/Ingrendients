<?php
/*
Plugin Name: AFR Ingredient
Plugin URI: http://codex.wordpress.org
Description: Search substitutions of ingredient.
Version: 1.0
Author: Dani Gojay
Author URI: http://converion-hub.com
License: GPL2
*/

/**
 * Instructions:
 * 1. active this plugin
 * 2. create ingredients categories/alphabets [ingredient -> categories]
 * 3. create substitutions [ingredient -> add new]
 * 4. creeate page -> title: Ingredient & slug: ingredient
 * 5. open the url http://asiafoodrecipe.com/ingredient
 */

global $wp_version;
define( 'AFR_REQUIRED_WP_VERSION', '3.0' );

/**
 * enable debug mode in wordpress
 */
// wp_debug_mode(true);
define( 'AFR_DEBUG', false  );
define( 'AFR_PAGE_DEBUG', false  );

/**
 * Check minimum wordpress version required > 3.0
 * if lastest wordpress version < 3.0, send message for updating wordpress version
 */ 
$exit_msg = 'AFR Ingredient for WordPress requires WordPress '. AFR_REQUIRED_WP_VERSION .' or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please update!</a>';
if ( version_compare( $wp_version, AFR_REQUIRED_WP_VERSION, "<" ) ) {
    exit($exit_msg);
}

/**
 *
 * Global Define
 *
 * AFR_PLUGIN_BASENAME = plugin basename -> "webroot\wp-content\plugins"
 * AFR_PLUGIN_NAME = plugin name -> "ingredient"
 * AFR_PLUGIN_DIR = plugin directory -> "webroot\wp-content\plugins\ingredient"
 * AFR_PLUGIN_URL = plugin URL -> "http://asiafoodrecipe.com/wp-content/plugins/"
 * AFR_PLUGIN_INC_DIR = plugin directory folder includes -> "webroot\wp-content\plugins\ingredient\includes"
 * AFR_PLUGIN_TPL_DIR = plugin directory folder templates  -> "webroot\wp-content\plugins\ingredient\templates"
 * 
 */
if ( ! defined( 'AFR_PLUGIN_BASENAME' ) )
    define( 'AFR_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

if ( ! defined( 'AFR_PLUGIN_NAME' ) )
    define( 'AFR_PLUGIN_NAME', trim( dirname( AFR_PLUGIN_BASENAME ), '/' ) );

if ( ! defined( 'AFR_PLUGIN_DIR' ) )
    define( 'AFR_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . AFR_PLUGIN_NAME );

if ( ! defined( 'AFR_PLUGIN_URL' ) )
    define( 'AFR_PLUGIN_URL', WP_PLUGIN_URL . '/' . AFR_PLUGIN_NAME );

if ( ! defined( 'AFR_PLUGIN_INC_DIR' ) )
    define( 'AFR_PLUGIN_INC_DIR', AFR_PLUGIN_DIR . '/includes' );

if ( ! defined( 'AFR_PLUGIN_TPL_DIR' ) )
    define( 'AFR_PLUGIN_TPL_DIR', AFR_PLUGIN_DIR . '/templates' );

/** ===================================================================================== *
 * ====================================== FUNCTIONS ===================================== *
 ** ===================================================================================== */

 /**
  * the function for developing
  *
  * dumping the data
  * @param String title,
  * @param Array/Object data,
  * @return String
  */
function dump( $title, $data )
{
    echo '<h1>'. $title .'</h1><pre>'. print_r($data, 1).'</pre>';
}

function _number_format( $float_number )
{
    $_number = number_format( $float_number, 2 );
    list( $number, $comma ) = explode( '.', $_number );
    
    if( $comma == '00' )
      return $number;
    
    return $_number;
}
 
/**
 * get alphabeth
 *
 * get all terms (alpahbet) from the taxonomy "ingredient_taxonomy"
 * used di templates/ingredient.php
 * 
 * @return Object
 */
function get_alphabeth()
{  
    global $wpdb;
    
    $query = '
    SELECT DISTINCT *   
    FROM wp_terms t   
    INNER JOIN wp_term_taxonomy tax   
    ON tax.term_id = t.term_id  
    WHERE ( tax.taxonomy = "ingredient_taxonomy")
    ORDER BY t.name ASC';                      
    
    return $wpdb->get_results($query);                  
}

/**
 * hooks add_filter "query_posts"
 * add query WHERE post_title LIKE '%something%'
 * 
 * use: add arguments "search_title"
 * example:
 * $args = array(
 *	'post_type' => 'ingredient',
 *	'search_title' => 'Beef' // get title LIKE '%Beef'
 *	// another args
 * );
 * $posts = new WP_Query( $args ); // or
 * query_posts( $args );
 *
 * @param $where
 * @param $wp_query
 * @return $where
 */
function afr_posts_where( $where, &$wp_query )
{
    global $wpdb;
    
    if ( $search_title = $wp_query->get( 'search_title' ) ) {
        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'' . esc_sql( like_escape( $search_title ) ) . '%\'';
    }
    
    return $where;
}
add_filter( 'posts_where', 'afr_posts_where', 10, 2 );

/** ===================================================================================== *
 * ====================================== REGISTER ===================================== *
 ** ===================================================================================== */

 /**
  * Global variable for ingredient settings
  *
  * @return array(
  * 	'display' => <INT> 	// the number of results displayed substitutes
  * 	'original' => <BOOLEAN> // show the original ingredients recipe? true or false?
  * );
  * 
  */
$ingredient_settings = unserialize(get_option('ingredient_settings'));

/**
 * register AFRIngredient class
 *
 * The main class for the control of this plugin
 * Hooks WP API
 * - init register_post_type 
 * - init register_taxonomy
 * - template_redirect
 * - admin_print_scripts
 * - wp_enqueue_scripts
 * - add_meta_box
 * - add_submenu_page
 * - AJAX HANDLER 
 */
include 'includes/AFRIngredient.php';

if( class_exists('AFRIngredient') )
{
    $ingredient = new AFRIngredient();
    /**
     * register_activation_hook
     * @param file
     * @param callback
     */
    register_activation_hook( __FILE__, array( &$ingredient, 'install' ) );
}