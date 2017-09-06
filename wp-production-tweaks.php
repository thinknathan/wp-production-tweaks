<?php
/*
Plugin Name:  Production Tweaks
Plugin URI:   https://github.com/thinknathan/
Description:  Default setup for a faster WordPress install.
Version:      1.0.0
Author:       Think_Nathan
Author URI:   https://thinknathan.ca
License:      MIT License
*/

namespace Think_Nathan\Utils;


// Hides admin bar
//add_action( 'show_admin_bar', '__return_false' );


// Removes default dashboard widgets
function disable_default_dashboard_widgets() {
	global $wp_meta_boxes;
	// WordPress defaults
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
	// BBPress
	unset($wp_meta_boxes['dashboard']['normal']['core']['bbp-dashboard-right-now']);
	// Yoast Seo
	unset($wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget']);
	// Gravity Forms
	unset($wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard']);
}
add_action('wp_dashboard_setup', __NAMESPACE__ . '\\disable_default_dashboard_widgets', 999);


function default_setup() {
  // set the options to change
  $option = array(
    // change the permalink structure
    'permalink_structure'           => '/%postname%/',
    // use year/month folders for uploads 
    'uploads_use_yearmonth_folders' => 1,
    // don't use those ugly smilies
    'use_smilies'                   => 0,
    // timezone
    'gmt_offset'                   => -7,
    // hide from search engines
    'blog_public'                   => 0,
    // page on front
    'show_on_front'            => 'page',
    // use the default page
    'page_on_front '                => 2,
  );

  // change the options!
  foreach ( $option as $key => $value ) {  
    update_option( $key, $value );
  }

  // flush rewrite rules because we changed the permalink structure
  global $wp_rewrite;
  $wp_rewrite->flush_rules();

  // delete the default comment
  wp_delete_comment( 1 );
  // delete default post
  wp_delete_post( 1, TRUE );
  // delete default page
  //wp_delete_post( 2, TRUE );

  // switch to theme defined in .env file
  $default_theme = get_env('DEFAULT_THEME');
  if ($default_theme) {
    switch_theme( $default_theme );
  }
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\\default_setup' );
