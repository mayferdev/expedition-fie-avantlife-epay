<?php

// fired when user add or update a post/page
add_action('save_post', 'expedition_update_post_timestamp', 10, 3);

// hook when is saved the push, but after acf stores data
add_action('acf/save_post', 'expedition_on_save_options_page', 20);

// fired when user add or update a terms
add_action( 'edit_terms', 'expedition_on_edit_terms', 10, 2 );
add_action( 'expedition_on_edit_terms', 'expedition_on_edit_terms', 10, 2 );
add_action( "create_term",  'expedition_on_create_term', 10, 3);
add_action( 'pre_delete_term', 'expedition_on_edit_terms', 10, 2 );
// when user add a term to a post, in this case a category to a tour
add_action('set_object_terms', 'expedition_on_set_object_terms', 10, 4);


/**
 * Just fires expedition_on_edit_terms to make just 1 function to update taxonomies 
 * timestamp for flags endpoint
 * 
 * @param type $object_id
 * @param type $terms
 * @param type $tt_ids
 * @param type $taxonomy
 */
function expedition_on_set_object_terms( $object_id, $terms, $tt_ids, $taxonomy ){
    do_action('expedition_on_edit_terms', $terms, $taxonomy);
}


/**
 * Updates taxonomies flag por flags endpoint
 * 
 * @param type $term_id
 * @param type $taxonomy
 */
function expedition_on_edit_terms( $term_id, $taxonomy ){
    
    update_option( "timestamp_$taxonomy",  time() );
    
}

/**
 * Just fires expedition_on_edit_terms to make just 1 function to update taxonomies 
 * timestamp for flags endpoint
 * 
 * @param type $term_id
 * @param type $taxonomy_id
 * @param type $taxonomy
 */
function expedition_on_create_term( $term_id, $taxonomy_id, $taxonomy ){
    do_action('expedition_on_edit_terms', $term_id, $taxonomy);
}




/**
 * Save post metadata when a post is saved.
 *
 * @param int $post_id The ID of the post.
 * @param post $post the post.
 */
function expedition_update_post_timestamp($post_id, $post, $update) {

    $post_types = expedition_post_types_flags();
    $post_type = $post->post_type;

    if ( $post_type == 'menu' ){
        return;
    }
    
    // If this isn't a 'book' post, don't update it.
    if ( in_array( $post_type, $post_types ) ) {
        update_option( "timestamp_$post_type",  time() );
    }

}

/**
 *
 * @return array with the custom post types that the app get from the api
 */
function expedition_post_types_flags(){
    return array('tour', 'settings', 'catalogs', 'specialty');
}







/**
 * When user save options page, this will be triggered, so we'll store the 
 * timestamp
 * 
 * @param type $post_id
 * @return type
 */
function expedition_on_save_options_page ( $post_id ) {
    
    // bail early if no ACF data
    if( empty($_POST['acf']) ) {
        return;
    }
    //name="acf[field_590bfb83e3b79][0][field_590bfbb4e3b7b]"
    // dashboard
    if ( isset($_POST['acf']['field_5910e1b312c90']) ){
        //$dashboard_field = $_POST['acf']['field_5747657a3ef56'];
        update_option( "timestamp_settings",  time() );
        update_option( "timestamp_catalogs",  time() );
    }
}