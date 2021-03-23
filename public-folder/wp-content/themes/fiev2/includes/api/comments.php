<?php

class comments_API extends base_API {
    public function register_routes() {
        $namespace = EXPEDITION_PRODUCTION_API_VERSION;
        register_rest_route($namespace, '/comments/photo/(?P<id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_photo_comments'),
                'permission_callback' => array( $this, 'check_token' ),
            ),
                )
        );
        
        register_rest_route($namespace, '/comments/photo/(?P<id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_photo_comment'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    'comment' => [
                        'required' => true,
			'type' => 'string',
			'description' => 'The comment'
                    ],
                    )
            ),
                )
        );
    }
    
    /**
     * Returns single tour
     * 
     * @param type $request
     * @return \WP_REST_Response
     */
    public function get_photo_comments($request, $return_value = false) {
        
        $photo_id = $request->get_param('id');
        $attachment = get_attached_file($photo_id);
        // does it exist?
        if ( !$attachment ){
            $response = ['code'=>'invalid_photo', 'message'=> 'Invalid photo', 'status'=>609 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        $args = array(
            'post_id' => $photo_id,
            'status' => 'all',
            'number' => 1000,
            // 'orderby' => '',
            // 'order' => 'DESC',
        );
        $comments = get_comments( $args );
        if ( $comments && count($comments) >0 ){
            foreach ($comments as $key => $comment) {
                unset($comment->comment_author_url);
                unset($comment->comment_author_IP);
                unset($comment->comment_author_email);
                unset($comment->comment_agent);
                unset($comment->comment_type);
                $comments[$key] = $comment;
            }
        }
        
        if ( $return_value ){
            return $comments;
        }
        
        return new WP_REST_Response(array('success'=> true, 'comments'=> $comments ), 200);
    }
    
    /**
     * Returns single tour
     * 
     * @param type $request
     * @return \WP_REST_Response
     */
    public function create_photo_comment($request) {
        
        $photo_id = (int)$request->get_param('id');
        $comment = (int)$request->get_param('comment');
        $user_id = (int)$this->user['ID'];
        $full_name = (string)$this->user['ID'];
        $email = (string)$this->user['email'];
        $attachment = get_attached_file($photo_id);
        $time = current_time('mysql');
        
        // does it exist?
        if ( !$attachment ){
            $response = ['code'=>'invalid_photo', 'message'=> 'Invalid photo', 'status'=>609 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        $data = array(
            'comment_post_ID' => $photo_id,
            'comment_author' => $full_name,
            'comment_author_email' => $email,
            'comment_author_url' => '',
            'comment_content' => $comment,
            'comment_type' => '',
            'comment_parent' => 0,
            'user_id' => $user_id,
            'comment_author_IP' => Expedition_Helper::getClientIp(),
            'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
            'comment_date' => $time,
            'comment_approved' => 1,
        );
        wp_insert_comment($data);
        return new WP_REST_Response(array('success'=> true, 'comments'=> self::get_photo_comments($request, true) ), 200);
    }
    
    
}

add_action('rest_api_init', function () {
    $controller = new comments_API();
    $controller->register_routes();
});
