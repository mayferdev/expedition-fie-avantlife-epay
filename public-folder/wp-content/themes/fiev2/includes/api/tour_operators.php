<?php

class TourOperators_API extends base_API {
     public function register_routes() {
        $namespace = EXPEDITION_PRODUCTION_API_VERSION;
        register_rest_route($namespace, '/tour_operators/(?P<id>[\d]+)/', array(
                array(
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => array($this, 'get_tour_operator'),
                    // 'permission_callback' => array( $this, 'check_token' ),
                )
            )
        );
    }
    
    
    public function get_tour_operator($request) {       
        $to_id = $request->get_param('id');
        
        $user = get_user_by('ID', $to_id);
        if ( !$user || $user->roles[0] != 'business' ){
            $response = ['code'=>'bad_to', 'message'=> "The tour operator doesn't exist", 'status'=>612 ];
            return $response;
        }
        
        $to_user = Expedition_Helper::getUserExpeditioner($to_id);
        
        $rating = 0;
        $comments = get_field('comments', "user_$to_id");
		$_comments = array();
        if ( is_array($comments) && count($comments)>0 ){
            foreach ($comments as $comment) {
                $comment['main_picture'] = $comment['source'] == 'facebook' ? 'https://cdn2.auth0.com/docs/media/connections/facebook.png' : 'http://wmoda.com/wp-content/uploads/2017/10/Tripadvisor.png';
                $comment['rating'] = (int)$comment['rating'];
                $rating += $comment['rating'];
				$_comments[] = $comment;
            }
            $rating = (float)($rating / count($comments));
        }
        $to_user['comments'] = $_comments;
        
        $to_user['verifications'] = get_field('verifications', "user_$to_id");
        $to_user['about'] = get_field('about', "user_$to_id");
        $to_user['rating'] = (int)$rating;
        
        return new WP_REST_Response(array('success'=> true, 'to'=> $to_user), 200);
    }
    
}

add_action('rest_api_init', function () {
    $controller = new TourOperators_API();
    $controller->register_routes();
});