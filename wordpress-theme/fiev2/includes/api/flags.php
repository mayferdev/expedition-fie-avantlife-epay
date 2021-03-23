<?php

//get_template_part('includes/firebase');

class flags_API extends WP_REST_Controller {

    public function register_routes() {
        $version = 'v1';
        //$namespce = 'wp/v' . $version;
        $namespce = $version;
        register_rest_route($namespce, '/flags', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_flags'),
            ),
                )
        );
    }

    /**
     * Return array with the list of timestamps of last updated settings, cpt, etc.
     * 
     * @param type $request
     * @return \WP_REST_Response a token if we receive valid credentials
     */
    public function get_flags($request) {
        
        $post_types = expedition_post_types_flags();
        
        $response = array();
        foreach ($post_types as $post_type) {            
            $response[$post_type] = (int) get_option("timestamp_$post_type", 0);
        }
        
        return $response;
    }
}

add_action('rest_api_init', function () {
    $controller = new flags_API();
    $controller->register_routes();
});
