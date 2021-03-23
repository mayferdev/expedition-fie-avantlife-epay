<?php

class TravelNotes_API extends base_API {
     public function register_routes() {
        $namespace = EXPEDITION_PRODUCTION_API_VERSION;
        register_rest_route($namespace, '/travel_notes/', array(
                array(
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => array($this, 'get_travel_notes'),
                    'permission_callback' => array( $this, 'check_token' ),
                ),
                array(
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => array($this, 'create_travel_note'),
                    'permission_callback' => array( $this, 'check_token' ),
                    'args' => array(
                        'thumbnail' => [
                            'required' => true,
                            'type' => 'integer',
                            'description' => 'The thumbnail image'
                        ],
                        'title' => [
                            'required' => true,
                            'type' => 'string',
                            'description' => 'The travel notes title'
                        ],
                        'excerpt' => [
                            'required' => true,
                            'type' => 'string',
                            'description' => 'The travel notes excerpt'
                        ],
                        'image' => [
                            'required' => false,
                            'type' => 'integer',
                            'description' => 'The thumbnail image'
                        ],
                        'content' => [
                            'required' => true,
                            'type' => 'string',
                            'description' => 'The travel notes content'
                        ],
                    )
                )
            )
        );
        
        register_rest_route($namespace, '/travel_notes/(?P<id>[\d]+)/view/', array(
                array(
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => array($this, 'add_view_to_travel_note'),
                    'permission_callback' => array( $this, 'check_token' ),
                    'args' => array()
                )
            )
        );
        
        register_rest_route($namespace, '/travel_notes/(?P<id>[\d]+)/save/', array(
                array(
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => array($this, 'save_travel_note'),
                    'permission_callback' => array( $this, 'check_token' ),
                    'args' => array()
                )
            )
        );
        
        register_rest_route($namespace, '/travel_notes/(?P<id>[\d]+)/delete/', array(
                array(
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => array($this, 'delete_from_saved_travel_note'),
                    'permission_callback' => array( $this, 'check_token' ),
                    'args' => array()
                )
            )
        );
        
        register_rest_route($namespace, '/travel_notes/(?P<id>[\d]+)/trash/', array(
                array(
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => array($this, 'trash_travel_note'),
                    'permission_callback' => array( $this, 'check_token' ),
                    'args' => array()
                )
            )
        );
        
        register_rest_route($namespace, '/travel_notes/(?P<id>[\d]+)/like/', array(
                array(
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => array($this, 'like_travel_note'),
                    'permission_callback' => array( $this, 'check_token' ),
                    'args' => array()
                )
            )
        );
        
        register_rest_route($namespace, '/travel_notes/(?P<id>[\d]+)/unlike/', array(
                array(
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => array($this, 'unlike_travel_note'),
                    'permission_callback' => array( $this, 'check_token' ),
                    'args' => array()
                )
            )
        );
        
    }
    
    
    public function get_travel_notes($request) {       
        $travel_notes = array();
        $my_travel_notes = $request->get_param('my_travel_notes');
        $search = $request->get_param('search');
        // $search_by = $request->get_param('search_by');
        $user_id = (int)$this->user['ID'];
        $args = array();
        
        $basic_args = array(
            'post_type'         =>'travel_note', 
            // 'meta_key'          => 'departure_date',
            // 'orderby'           => 'meta_value_num',
            // 'order'             => 'DESC',
            'fields'            => 'ids',
            'author'            => $user_id,
            'posts_per_page'    =>  150
        );
        
        if ( $search ){
            // if ($search_by == 'title'){
                $basic_args['s'] = $search;
            // }
        }
        
        $savedPosts = Expedition_Helper::getSavedPostsByUser($user_id);
        $travel_notes_ids = array();
        if (is_array($savedPosts) && count($savedPosts) >0 ){
            foreach ($savedPosts as $saved) {
                $travel_notes_ids[] = $saved->post_id;
            }
        }
        
        $basic_query = new WP_Query( array_merge($basic_args, $args) );
        $ids = array_merge($basic_query->posts, $travel_notes_ids);
        $query = new WP_Query(array('post_type' => 'travel_note', 'posts_per_page'=> 100, 'post_status' => 'any', 'post__in' => $ids, 'orderby'  => 'date',  'order' => 'DESC', ));
        
        if ( $query->found_posts && count($ids) > 0 ){
            foreach ($query->posts as $p) {
                $travel_notes[] = Expedition_Helper::formatTravelNote($p->ID, $user_id);
            }
        }
        
        return new WP_REST_Response(array('success'=> true, 'travel_notes'=> $travel_notes), 200);
    }
    
    public function create_travel_note($request) {
        
        $image_id = (int)$request->get_param('image');
        $image_ids = $request->get_param('images');
        $thumbnail_id = (int)$request->get_param('thumbnail');
        
        $content = $request->get_param('content');
        $excerpt = $request->get_param('excerpt');
        $title = $request->get_param('title');
        $user_id = (int)$this->user['ID'];
        
        
        $_post = array(
            'post_title'	=> $title,
            'post_type'     	=> 'travel_note',
            'post_status'	=> 'publish',
            'post_author'	=> $user_id,
            'post_content'	=> wpautop($content),
            'post_excerpt'	=> wpautop($excerpt),
        );
        // insert the post into the database
        $post_id = wp_insert_post( $_post, true );
        if (is_wp_error($post_id) ){
            
            $response = ['code'=>'file_error', 'message'=> "Error creating the travel note, please try again", 'wp_error'=>$post_id->get_error_message(), 'data'=> ['status'=>601] ];
            return new WP_REST_Response($response, 200 );
        }
        
        // save meta values
        update_field( 'image', $image_id, $post_id );
        update_field( 'images', $image_ids, $post_id );
        update_field( 'thumbnail', $thumbnail_id, $post_id );
        update_post_meta($post_id, 'views', 0);
        
        return new WP_REST_Response(array('success'=> true, 'travel_note'=> Expedition_Helper::formatTravelNote( $post_id, $user_id ) ), 200);
    }
    
    public function save_travel_note($request) {
        
        $travel_note_id = (int)$request->get_param('id');
        $user_id = (int)$this->user['ID'];
        
        $record = Expedition_Helper::getSavedPostByUserAndPost($user_id, $travel_note_id);
        $saved = false;
        if ( !$record ){
            $saved = true;
            Expedition_Helper::insertSavedPost($user_id, $travel_note_id);
            $count = Expedition_Helper::getSavedPostCount($travel_note_id);
            update_post_meta($travel_note_id, 'saved', $count);
        }
        
        return new WP_REST_Response(array('success'=> true, 'record'=> $record, 'saved'=> $saved, 'travel_note'=> Expedition_Helper::formatTravelNote( $travel_note_id, $user_id ) ), 200);
        
    }
    
    public function delete_from_saved_travel_note($request) {
        
        $travel_note_id = (int)$request->get_param('id');
        $user_id = (int)$this->user['ID'];
        
        $record = Expedition_Helper::getSavedPostByUserAndPost($user_id, $travel_note_id);
        $deleted = false;
        if ( $record ){
            $deleted = true;
            Expedition_Helper::deleteSavedPostRecord($record->id);
        }
        $count = Expedition_Helper::getSavedPostCount($travel_note_id);
        update_post_meta($travel_note_id, 'saved', $count);
        
        return new WP_REST_Response(array('success'=> true, 'deleted'=> $deleted, 'travel_note'=> Expedition_Helper::formatTravelNote( $travel_note_id, $user_id ) ), 200);
        
    }
    
    public function trash_travel_note($request) {
        
        $travel_note_id = (int)$request->get_param('id');
        $user_id = (int)$this->user['ID'];
        
        $post = get_post( $travel_note_id );
        
        if ( !$post || $post->post_type != 'travel_note' || $post->post_author != $user_id ){
            $response = ['code'=>'travel_note_invalid', 'message'=> "Invalid travel note", 'status'=>613 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        $records = Expedition_Helper::getSavedPostsByPost($travel_note_id);
        if ( $records ){
            foreach ($records as $record) {
                Expedition_Helper::deleteSavedPostRecord($record->id);
            }
        }
        $records_liked = Expedition_Helper::getLikedPostsByPost($travel_note_id);
        if ( $records_liked ){
            foreach ($records_liked as $record) {
                Expedition_Helper::deleteLikedPostRecord($record->id);
            }
        }
        wp_delete_post($travel_note_id);
        
        return new WP_REST_Response(array('success'=> true,), 200);
        
    }
    
    public function like_travel_note($request) {
        
        $travel_note_id = (int)$request->get_param('id');
        $user_id = (int)$this->user['ID'];
        
        $record = Expedition_Helper::getLikedPostByUserAndPost($user_id, $travel_note_id);
        $saved = false;
        if ( !$record ){
            $saved = true;
            Expedition_Helper::insertLikedPost($user_id, $travel_note_id);
            $count = Expedition_Helper::getLikedPostCount($travel_note_id);
            update_post_meta($travel_note_id, 'liked', $count);
            
            $post = get_post($travel_note_id);
            Expedition_Helper::insertActivityRecord((int)$post->post_author, 'liked_post', $travel_note_id, $user_id);
            do_action('expedition_liked_post', $travel_note_id, $user_id);
        }
        
        return new WP_REST_Response(array('success'=> true, 'record'=> $record, 'liked'=> $saved, 'travel_note'=> Expedition_Helper::formatTravelNote( $travel_note_id, $user_id ) ), 200);
        
    }
    
    public function unlike_travel_note($request) {
        
        $travel_note_id = (int)$request->get_param('id');
        $user_id = (int)$this->user['ID'];
        
        $record = Expedition_Helper::getLikedPostByUserAndPost($user_id, $travel_note_id);
        $deleted = false;
        if ( $record ){
            $deleted = true;
            Expedition_Helper::deleteLikedPostRecord($record->id);
        }
        $count = Expedition_Helper::getLikedPostCount($travel_note_id);
        update_post_meta($travel_note_id, 'liked', $count);
        
        return new WP_REST_Response(array('success'=> true, 'deleted'=> $deleted, 'travel_note'=> Expedition_Helper::formatTravelNote( $travel_note_id, $user_id ) ), 200);
        
    }
    
    public function add_view_to_travel_note($request) {
        
        $travel_note_id = (int)$request->get_param('id');
        $user_id = (int)$this->user['ID'];
        
        $record = Expedition_Helper::getViewedPostByUserAndPost($user_id, $travel_note_id);
        $created = false;
        if ( !$record ){
            $created = true;
            Expedition_Helper::insertViewedPost($user_id, $travel_note_id);
            $count = Expedition_Helper::getViewedPostCount($travel_note_id);
            update_post_meta($travel_note_id, 'views', $count);
        }
        
        return new WP_REST_Response(array('success'=> true, 'record'=> $record, 'created'=> $created, 'travel_note'=> Expedition_Helper::formatTravelNote( $travel_note_id, $user_id ) ), 200);
    }
    
}

add_action('rest_api_init', function () {
    $controller = new TravelNotes_API();
    $controller->register_routes();
});