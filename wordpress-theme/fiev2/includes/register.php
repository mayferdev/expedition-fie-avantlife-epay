<?php

/******************************************************************************/
/********************************** TOURS ***********************************/
/******************************************************************************/

/**
 * Register post type related to tour-user
 * 
 */
function tours_post_type() {

    $labels = array(
        'name' => __('Eventos', 'expedition'),
        'singular_name' => __('Evento', 'expedition'),
        'add_new' => _x('Add evento', 'expedition', 'expedition'),
        'add_new_item' => __('Add evento', 'expedition'),
        'edit_item' => __('Edit evento', 'expedition'),
        'new_item' => __('Nuevo evento', 'expedition'),
        'view_item' => __('Ver evento', 'expedition'),
        'search_items' => __('Buscar evento', 'expedition'),
        'not_found' => __('No se encontro evento ', 'expedition'),
        'not_found_in_trash' => __('No tours found in Trash', 'expedition'),
        'menu_name' => __('Eventos', 'expedition'),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'description' => 'Public and private tours',
        'taxonomies' => array('post_tag', /*'category'*/),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_admin_bar' => false,
        'menu_position' => 6,
        'menu_icon' => 'dashicons-store',
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        //'rewrite' => array('slug' => 'tours/%categoria%', 'with_front' => false),
        'capability_type' => 'tour',
        'capabilities' => array(
            'create_posts' => 'create_tours',
            ),
        'map_meta_cap'    => true,
        // 'capabilities' => array('post'=>true, 'edit_device'=> true),
        
        'supports' => array(
            'title', 
            'editor',
            'author',
            //'comments'
        )
    );

    register_post_type('tour', $args);
}

add_action('init', 'tours_post_type');



/******************************************************************************/
/********************************** TRAVEL NOTES ***********************************/
/******************************************************************************/

/**
 * Register post type related to travel_note
 * 
 */
// function travel_notes_post_type() {

//     $labels = array(
//         'name' => __('Travel notes', 'expedition'),
//         'singular_name' => __('Travel note', 'expedition'),
//         'add_new' => _x('Add travel note', 'expedition', 'expedition'),
//         'add_new_item' => __('Add travel note', 'expedition'),
//         'edit_item' => __('Edit travel note', 'expedition'),
//         'new_item' => __('New travel note', 'expedition'),
//         'view_item' => __('See travel note', 'expedition'),
//         'search_items' => __('Search travel note', 'expedition'),
//         'not_found' => __('No travel notes found', 'expedition'),
//         'not_found_in_trash' => __('No travel notes found in Trash', 'expedition'),
//         'menu_name' => __('Travel notes', 'expedition'),
//     );

//     $args = array(
//         'labels' => $labels,
//         'hierarchical' => false,
//         'description' => 'Public and private travel notes',
//         'taxonomies' => array( /*'category'*/),
//         'public' => true,
//         'show_ui' => true,
//         'show_in_menu' => true,
//         'show_in_admin_bar' => false,
//         'menu_position' => 6,
//         'menu_icon' => 'dashicons-media-text',
//         'show_in_nav_menus' => true,
//         'publicly_queryable' => true,
//         'exclude_from_search' => false,
//         'has_archive' => true,
//         'query_var' => true,
//         'can_export' => true,
//         //'rewrite' => array('slug' => 'travel_notes/%categoria%', 'with_front' => false),
//         'capability_type' => 'travel_note',
//         'capabilities' => array(
//             'create_posts' => 'create_travel_notes',
//             ),
//         'map_meta_cap'    => true,
//         // 'capabilities' => array('post'=>true, 'edit_device'=> true),
        
//         'supports' => array(
//             'title', 
//             'editor',
//             'author',
//             'excerpt',
//             //'comments'
//         )
//     );

//     register_post_type('travel_note', $args);
// }

// add_action('init', 'travel_notes_post_type');



/**
 * Replaces the title of the "author" metabox in the tour single edit view
 * 
 * @global array $wp_meta_boxes
 */
function change_meta_box_titles() {
    global $wp_meta_boxes, $post; // array of defined meta boxes
    // cycle through the array, change the titles you want
    if ( $post->post_type == 'tour' ){
        
    }
    $current_user_role = expedition_get_user_role();
    if ( $current_user_role ){
        if ( $current_user_role == 'administrator' ){
            $wp_meta_boxes['tour']['normal']['core']['authordiv']['title']= __('Program owner', 'expedition');
        }
    }
    
}
//hook to the 'add_meta_boxes' action
add_action('add_meta_boxes', 'change_meta_box_titles');



add_theme_support('post-formats', array('status', 'gallery', 'video', 'audio', 'quote'));
//add_post_type_support( 'post', 'post-formats' );


/******************************************************************************/
/********************************** BOOKINGS ***********************************/
/******************************************************************************/


/**
 * Register post type related to tour-user
 * 
 */
function tour_booking_post_type() {

    $labels = array(
        'name' => __('Bookings', 'expedition'),
        'singular_name' => __('Booking', 'expedition'),
        'add_new' => _x('Add booking', 'expedition', 'expedition'),
        'add_new_item' => __('Add booking', 'expedition'),
        'edit_item' => __('Edit booking', 'expedition'),
        'new_item' => __('New booking', 'expedition'),
        'view_item' => __('See booking', 'expedition'),
        'search_items' => __('Search bookings', 'expedition'),
        'not_found' => __('No bookings found', 'expedition'),
        'not_found_in_trash' => __('No bookings found in Trash', 'expedition'),
        'menu_name' => __('Bookings', 'expedition'),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'description' => 'The bookings',
        //'taxonomies' => array('post_tag'),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 6,
        'menu_icon' => 'dashicons-clipboard',
        'show_in_nav_menus' => true,
        'publicly_queryable' => false,
        'exclude_from_search' => true,
        'has_archive' => false,
        'query_var' => false,
        'can_export' => false,
        'capability_type' => 'post',
        // 'capabilities' => array('edit_tours'),
        'supports' => array('author')
    );

    register_post_type('booking', $args);
}

add_action( 'admin_enqueue_scripts', 'expedition_prevent_bookings_drafts' );
function expedition_prevent_bookings_drafts() {
    if ( 'tour_booking' == get_post_type() ){
        wp_dequeue_script( 'autosave' );
    }   
}

add_action('admin_head', 'leave_only_publish_button');

function leave_only_publish_button() {
    if ( 'tour_booking' == get_post_type() ){
        echo '<style>
                    #misc-publishing-actions, #minor-publishing-actions {
                        display:none;
                    }
                </style>';
    }
}

add_filter( 'gettext', 'change_publish_button', 10, 2 );
function change_publish_button( $translation, $text ) {
    if ( $text == 'Publish' )
        return 'Save';
    return $translation;
}

//if ( Expedition_Helper::getClientIp() == '181.174.72.157' ){
//    add_action('init', 'tour_booking_post_type');
//}




/* * ************** ADD POST TAG SUPPORT FOR PAGES **************** */

// add tag support to pages
function tags_support_all() {
    register_taxonomy_for_object_type('post_tag', 'page');
}

// ensure all tags are included in queries
function tags_support_query($wp_query) {
    if ($wp_query->get('tag'))
        $wp_query->set('post_type', 'any');
}

// tag hooks
add_action('init', 'tags_support_all');
add_action('pre_get_posts', 'tags_support_query');
/************************************************************************/



function expedition_filter_field_5a79df9d3ad41_value( $field ){
    $field['choices'] = array();
    $countries = get_field('countries', 'options');
    foreach( $countries as $country ) {
        $field['choices'][ $country['id'] ] = "{$country['name']} - {$country['currency_code']}";
    }
    
    return $field;
}
add_filter('acf/load_field/key=field_5a79df9d3ad41', 'expedition_filter_field_5a79df9d3ad41_value');





function expedition_acf_post_object_query($args, $field, $post_id) {

    if ($field["key"] == "field_55b00ed74b409") {
        
    } else {
        // only show children of the current post being edited

        $args['order'] = "DESC";
        $args['post_status'] = "publish";
        $args['date_query'] = array(
            array(
                'after' => '1 month ago'
            )
        );
    }

    // return
    return $args;
}

// filter for every field
//add_filter('acf/fields/post_object/query', 'expedition_acf_post_object_query', 10, 3);
