<?php

class newsfeed_API extends base_API {

    public function register_routes() {
        
        $namespace = EXPEDITION_PRODUCTION_API_VERSION;
        register_rest_route($namespace, '/newsfeed/trending/', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_trending'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    
                    )
            ),
                )
        );
        register_rest_route($namespace, '/newsfeed/featured_expeditioners/', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_featured_expeditioners'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    
                    )
            ),
                )
        );
        
    }

    /**
     * Returns list of content for newsfeed
     * 
     * @param type $request
     * @return \WP_REST_Response
     */
    public function get_trending($request) {
        global $wpdb;
        
        $include_travel_notes = $request->get_param('include_travel_notes');
        $user_id = (int)$this->user['ID'];
        
        $users_args = array (
            'fields'        => 'ID',
            'meta_query'    => array(
                array(
                    'key'     => 'featured',
                    'value'   => 1
                )
            )
        );
        $wp_user_query = new WP_User_Query( $users_args );
        $authors = $wp_user_query->get_results();
        
        $gallery = array();
        if ( is_array($authors) && count($authors)>0 ){
            
            $search = $request->get_param('search');
            $search_by = $request->get_param('search_by');
            $basic_meta = array(
                    'relation' => 'OR',
                    array(
                        'key'       => 'private',
                        //'compare'   => '!=',
                        'value'     => 0
                    ),
                    array(
                        'key'       => 'private',
                        'compare'   => 'NOT EXISTS'
                    )
                );
            
            if ( $search && $search_by ){
                $meta_query = array(
                   'relation' => 'AND',
                    array(
                        'relation' => 'OR',
                        array(
                            'key'     => 'placeName',
                            'value'   => $search,
                            'compare' => 'LIKE'
                        ),
                        /*array(
                            'key'     => 'address',
                            'value'   => $search,
                            'compare' => 'LIKE'
                        )*/
                    ),
                    $basic_meta
                );
            }else{
                $meta_query = $basic_meta;
            }
            
            $photos_query = new WP_Query( array( 
                'post_type'     => 'attachment', 
                'post_status'   =>'inherit', 
                'author__in'    => $authors, 
                'orderby'       => 'date', 
                'order'         => 'DESC', 
                'posts_per_page'=> 50, 
                'fields'        => 'ids',
                'meta_query'    => $meta_query
                ) );
            if ( $include_travel_notes ){
                $query_travel_notes_args =  array( 
                    'post_type'     => 'travel_note', 
                    'post_status'   => 'publish', 
                    'author__in'    => $authors, 
                    'orderby'       => 'date', 
                    'order'         => 'DESC', 
                    'posts_per_page'=> 50, 
                    'fields'        => 'ids',
                    'meta_query'    => $basic_meta
                    );
                if ( $search && $search_by ){
                    $query_travel_notes_args['s'] = $search;
                }
                $query_travel_notes = new WP_Query( $query_travel_notes_args );
                $ids = array_merge($photos_query->posts, $query_travel_notes->posts);
                $query = new WP_Query(array('post_type' => 'any', 'posts_per_page'=> 100, 'post_status' => 'any', 'post__in' => $ids, 'orderby'  => 'date',  'order' => 'DESC', ));
            }else{
                $query = new WP_Query(array('post_type' => 'any', 'posts_per_page'=> 100, 'post_status' => 'any', 'post__in' => $photos_query->posts, 'orderby'  => 'date',  'order' => 'DESC', ));
            }
            
            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    if ( get_field('private', get_the_ID()) ){
                        continue;
                    }
                    if ( get_post_type() == 'attachment' ){
                        $img = Expedition_Helper::getAllImageSizes(get_the_ID(), true);
                        if ( isset($img['medium']['url']) ){
                            $img['type'] = 'gallery';
                            $gallery[] = $img;
                        }
                    }else if ( get_post_type() == 'travel_note' ){
                        $travel_note = Expedition_Helper::formatTravelNote(get_the_ID(), $user_id);
                        if ( $travel_note ){
                            $travel_note['type'] = 'travel_note';
                            $gallery[] = $travel_note;
                        }
                    }
                    
                }
                wp_reset_postdata(); // Restore original Post Data
            }
            //usort($gallery, 'galleryDateCompare');
        }
        
        return new WP_REST_Response(array('success'=> true, 'feed'=> $gallery), 200);
    }
    
    
    /**
     * Returns list of featured expeditioners
     * 
     * @param type $request
     * @return \WP_REST_Response
     */
    public function get_featured_expeditioners($request) {
        add_action( 'pre_user_query', 'expedition_random_user_query' );
        $users = get_users( array(
            'meta_key' => 'last_name',
            'orderby' => 'rand',
            'number'  => 6,
//            'meta_key'     => 'profile_picture',
//            'meta_compare' => 'EXISTS',
            'meta_key'     => 'featured',
            'meta_compare' => 1,
        ));
        $expeditioners = array();
        foreach ($users as $user) {
            $expeditioners[] = Expedition_Helper::getUser($user->ID);
        }
        return new WP_REST_Response(array('success'=> true, 'expeditioners'=> $expeditioners), 200);
    }
    
}

function expedition_random_user_query( $class ) {
    if( 'rand' == $class->query_vars['orderby'] )
        $class->query_orderby = str_replace( 'user_login', 'RAND()', $class->query_orderby );

    return $class;
}

add_action('rest_api_init', function () {
    $controller = new newsfeed_API();
    $controller->register_routes();
});
