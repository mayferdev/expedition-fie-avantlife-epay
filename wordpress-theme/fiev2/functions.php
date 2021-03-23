<?php
// prevent api errors
header("Access-Control-Allow-Origin: *");
@ini_set( 'upload_max_size' , '256M' );
@ini_set( 'post_max_size', '256M');
@ini_set( 'max_execution_time', '7200' );


//Desactivar soporte y estilos de Emojis
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

$response = apply_filters( 'rest_request_after_callbacks', $response, $handler, $request );

// removes the API schemes description in the API root
function expedition_after_rest_callbacks( $response, $handler, $request ){
    global $wpdb;
    $wpdb->close();
    
    return $response;
}

add_filter( 'rest_request_after_callbacks', 'expedition_after_rest_callbacks', 99, 3 );



$suffix = 'expedition';
wp_admin_css_color(
        'expedition',
        _x( 'Expedition', 'admin color scheme' ),
        get_stylesheet_directory_uri().'/admin/expedition/colors.css?time='.time(),
        array( '#05426b', '#05426b', '#05426b', '#05426b' ),
        array(
            'base'    => 'blue',
            'focus'   => '#fff',
            'current' => '#fff',
        )
    );


add_action( 'admin_init', function() {
    // remove the color scheme picker
    remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
    // force all users to use the "Ectoplasm" color scheme
    add_filter( 'get_user_option_admin_color', function() {
        return 'expedition';
    });
});

add_action( 'admin_title' , 'change_dashboard_title' );
function change_dashboard_title( $admin_title ) {
	
	global $current_screen, $title;
        $title = '';
	if( $current_screen->id != 'dashboard' ) {
            return $admin_title;
	}
	
//	$change_title = 'Title 1';
//
//	$admin_title = str_replace( __( 'Dashboard' ) , $change_title , $admin_title );

	return $admin_title;
	
}




global $wpdb;
include(get_template_directory() . '/includes/constants.php');

function expedition_api_setup() {
    load_theme_textdomain('expedition', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'expedition_api_setup');

/**
 * Enqueue scripts and styles for front-end.
 *
 * @since 0.1.0
 */
function expedition_api_scripts_styles() {
    wp_enqueue_script ( 'llqrcode-script', get_template_directory_uri() . '/assets/js/llqrcode.js?tt='.time() );
    wp_enqueue_script ( 'webqr-script', get_template_directory_uri() . '/assets/js/webqr.js?tt='.time() );
    wp_enqueue_script ( 'base64-script', get_template_directory_uri() . '/assets/js/jquery.base64.min.js?tt='.time() );
}

add_action('admin_enqueue_scripts', 'expedition_api_scripts_styles');

/**
 * Adding thumbnails support
 */
add_theme_support('post-thumbnails');


if (function_exists('acf_add_options_page') && current_user_can('administrator') ) {
    acf_add_options_page();
    //acf_add_options_sub_page(__('Options','expedition'));
    // acf_add_options_sub_page('Misc');
    acf_add_options_sub_page('Misc');
    acf_add_options_sub_page('Reports');
    acf_add_options_sub_page('Notifications');
    // acf_add_options_sub_page('Crons');
    acf_add_options_sub_page('Email templates');
    // acf_add_options_sub_page('Catalogs');
    // acf_add_options_sub_page('Payments');
    acf_add_options_sub_page('Terms of service');
}


// hide inline css/js code for emojis
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

//if (defined(EXPEDITION_DEBUG) ){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting (E_ALL);
//}


ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
    
//remove_action('wp_json_server_before_serve', 'json_api_default_filters', 10, 1);

    
    
function remove_admin_menu_items() {
    $remove_menu_items = array(
        __('Posts'), __('Pages'),
        __('Appearance'), 
        __('Plugins'), 
//        __('Tools'), 
//        __('Settings'), 
        __('Comments'),
//        __('Custom Fields'),
        /* __('Tools'), __('Plugins'), __('Appearance')*/ 
        );
    if ( !current_user_can('administrator') ) {
        $remove_menu_items[] = __('Tools');
    }
    global $menu;
    end($menu);
    while (prev($menu)) {
        $item = explode(' ', $menu[key($menu)][0]);
        if (in_array($item[0] != NULL ? $item[0] : "", $remove_menu_items)) {
            unset($menu[key($menu)]);
        }
    }
    remove_menu_page( 'edit.php?post_type=acf-field-group' );
}

add_action('admin_menu', 'remove_admin_menu_items', 100);

add_image_size( 'medium', 360, 216, true );



get_template_part('acf-fields');
// CORE
// Third party
//include_once(get_template_directory() . '/includes/wp-async-task.php');

include(get_template_directory() . '/qr/index.php');

// // traits
include(get_template_directory() . '/includes/traits/user_tour_invitations.php');
include(get_template_directory() . '/includes/traits/user_bookings.php');
include(get_template_directory() . '/includes/traits/transactions.php');
include(get_template_directory() . '/includes/traits/what_to_bring.php');
include(get_template_directory() . '/includes/traits/sessions.php');
include(get_template_directory() . '/includes/traits/invoices.php');
include(get_template_directory() . '/includes/traits/google_places.php');
include(get_template_directory() . '/includes/traits/tour_expeditioners.php');
include(get_template_directory() . '/includes/traits/user_cards.php');
include(get_template_directory() . '/includes/traits/followers.php');
include(get_template_directory() . '/includes/traits/activities.php');
include(get_template_directory() . '/includes/traits/saved_posts.php');
include(get_template_directory() . '/includes/traits/liked_posts.php');
include(get_template_directory() . '/includes/traits/viewed_posts.php');

include(get_template_directory() . '/includes/helper.php');
include(get_template_directory() . '/includes/roles.php');
include(get_template_directory() . '/includes/register.php');
include(get_template_directory() . '/includes/firebase.php');

// notifications
get_template_part('includes/notifications/index');
get_template_part('includes/notifications/bookings');
get_template_part('includes/notifications/invitations');
get_template_part('includes/notifications/register');
get_template_part('includes/notifications/followers');
get_template_part('includes/notifications/likes');

// hooks
get_template_part('includes/hooks');
get_template_part('includes/api_hooks');
get_template_part('includes/flags');
get_template_part('includes/fields-hooks');

// change wp-json to API
add_filter( 'rest_url_prefix', function( $prefix ) { return 'api'; } );

// API ENDPOINTS
get_template_part('includes/api/base');
get_template_part('includes/api/auth');
get_template_part('includes/api/misc');
get_template_part('includes/api/user');
get_template_part('includes/api/tours');
get_template_part('includes/api/comments');
get_template_part('includes/api/flags');
get_template_part('includes/api/newsfeed');
get_template_part('includes/api/travel_notes');
get_template_part('includes/api/tour_operators');


// ADMIN VIEWS
get_template_part('includes/admin/users_column');
get_template_part('includes/admin/programs_column');
get_template_part('includes/admin/ajax');
get_template_part('includes/admin/admin-styles');
get_template_part('includes/admin/admin-scripts');
// get_template_part('includes/admin/tour_invitations');
get_template_part('includes/admin/tour_bookings');
get_template_part('includes/admin/Cancels');
//get_template_part('includes/admin/payments_user_info_failed');
get_template_part('includes/admin/transactions');
// get_template_part('includes/admin/bills');
//get_template_part('includes/admin/reports');
//get_template_part('includes/admin/invoices');
get_template_part('includes/admin/dashboard-widgets');
get_template_part('includes/admin/qr_scanner');


// removes the API schemes description in the API root
function expedition_rest_index( $response ){
    return array();
}

add_filter( 'rest_index', 'expedition_rest_index'  );


get_template_part('temp-scripts');

/*
if ( !current_user_can('administrator') ){
    global $current_user;
    $user_id = $current_user->ID;
    $res = $wpdb->get_var($wpdb->prepare("
                    SELECT COUNT(*) FROM ".$wpdb->prefix.'posts'."
                    WHERE post_author = %d AND post_type = %s AND post_status = %s ".
//                    "AND MONTH(post_date) = MONTH(CURRENT_DATE()) AND YEAR(post_date) = YEAR(CURRENT_DATE()) ".
                      "AND MONTH(post_date) = 04 AND YEAR(post_date) = 2018 ".
                    "ORDER BY post_date DESC", $user_id, 'tour', 'publish' ));
    echo "count ".($res)." <br/>";
    if ($res && count($res) > 0 ){
        foreach ($res as $p) {
            echo "<br/>$p->ID date $p->post_date and $p->post_status and $p->post_type;<br/>";
        }
        exit();
    }
}
*/


