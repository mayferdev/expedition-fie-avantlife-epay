<?php

class auth_API extends base_API {

    public function register_routes() {
        
        $namespce = EXPEDITION_PRODUCTION_API_VERSION;
        
        register_rest_route($namespce, '/auth/login', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'login'),
                'args' => array(
                    'email' => [
                        'required' => true,
                        'description' => "User's email",
                        'type' => 'string',
                        'validate_callback' => 'is_email'
                    ],
                    'password' => [
                        'required' => true,
                        'description' => "User's password",
                        'type' => 'string',
                        'validate_callback' => 'rest_validate_request_arg'
                    ],
                    'user_language' => [
                        'required' => true,
			'type' => 'string',
			'description' => 'The language of the user to be registerd, only supported en|es',
			'enum' => array( 
                            'en',
                            'es'
                        ),
                    ],
                ),
            ),
                )
        );
        
        register_rest_route($namespce, '/auth/fblogin', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'fblogin'),
                'args' => array(
                    'token' => [
                        'required' => true,
                        'description' => "Facebook's token",
                        'type' => 'string',
                        'validate_callback' => 'rest_validate_request_arg'
                    ],
                    'user_language' => [
                        'required' => true,
			'type' => 'string',
			'description' => 'The language of the user to be registerd, only supported en|es',
			'enum' => array( 
                            'en',
                            'es'
                        ),
                    ],
                ),
            ),
                )
        );
        
        register_rest_route($namespce, '/auth/register', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'register'),
                'args' => array(
                    'firstname' => [
                        'required' => true,
                        'description' => "User's full name",
                        'type' => 'string',
                        'validate_callback' => 'rest_validate_request_arg'
                    ],
                    'lastname' => [
                        'required' => true,
                        'description' => "User's full name",
                        'type' => 'string',
                        'validate_callback' => 'rest_validate_request_arg'
                    ],
                    'dob' => [
                        'required' => false,
                        'description' => "User's date of birth",
                        'type' => 'string',
                        'validate_callback' => 'rest_validate_request_arg'
                    ],
                    'gender' => [
                        'required' => false,
                        'description' => "User's date of birth",
                        'type' => 'string',
                        'enum' => array( 
                            'male',
                            'female'
                        ),
                    ],
                    'email' => [
                        'required' => true,
                        'description' => "User's email",
                        'type' => 'string',
                        'validate_callback' => 'is_email'
                    ],
                    'password' => [
                        'required' => true,
                        'description' => "User's password",
                        'type' => 'string',
                        'validate_callback' => 'rest_validate_request_arg'
                    ],
                    'confirm_password' => [
                        'required' => true,
                        'description' => "Confirmation of user's password",
                        'type' => 'string',
                        'validate_callback' => 'rest_validate_request_arg'
                    ],
                    'invitation' => [
                        'required' => false,
                        'description' => "Invitation sent to user by a business or other expeditioner",
                        'type' => 'string'
                    ],
                    'role' => [
                        'required' => true,
                        'description' => "The role of user to be registered",
                        'type' => 'string',
                        'enum' => array( 
                            'expeditioner',
                            'business'
                        ),
                    ],
                    'user_language' => [
                        'required' => true,
			'type' => 'string',
			'description' => 'The language of the user to be registerd, only supported en|es',
			'enum' => array( 
                            'en',
                            'es'
                        ),
                    ],
                ),
            ),
                )
        );
        
    }
    
    
    /**
     * Login a user,
     * 
     * @param type $request
     * @return \WP_REST_Response a token if we receive valid credentials
     */
    public function fblogin($request) {
        
        require_once (__DIR__.'/../Facebook/autoload.php'); // change path as needed
        $fb = new Facebook\Facebook( FB_SDK_CONFIG );
        
        try {
            // Get the \Facebook\GraphNodes\GraphUser object for the current user.
            // If you provided a 'default_access_token', the '{access-token}' is optional.
            //$fbresponse = $fb->get('/me?fields=name,email,first_name,last_name,link,gender,birthday', $request->get_param('token'));
            //$fbresponse = $fb->get('/me?fields=name,email,first_name,last_name,link,gender,birthday,picture.width(600).height(600)', $request->get_param('token'));
            $fbresponse = $fb->get('/me?fields=name,email,first_name,last_name,picture.width(600).height(600)', $request->get_param('token'));

        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            //echo 'Graph returned an error: ' . $e->getMessage();
            //exit;
            $response = ['code'=>'auth_error', 'message'=> 'FB login error', 'status'=>401 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            //echo 'Facebook SDK returned an error: ' . $e->getMessage();
            //exit;
            $response = ['code'=>'auth_error', 'message'=> 'FB login error', 'status'=>401 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        $fbuser = $fbresponse->getGraphUser();
        //$fbuser_edge = $fbresponse->getGraphEdge();
        
        $user_json = json_decode($fbuser->asJson());
        $picture = '';
        if ( $user_json && isset($user_json->picture) ){
            $picture = $user_json->picture->url;
        }
        
//        $data = array(
//            $fbuser->getId(),
//            $fbuser->getName(),
//            $fbuser->getFirstName(),
//            $fbuser->getLastName(),
//            $fbuser->getField('email'),
//            $fbuser->getGender(),
//            $fbuser->getBirthday(),
//            $picture
//        );
//        var_dump($fb);
//        return array( 'fbuser'=>$data);
        
        $wp_user = get_user_by('id', $fbuser->getId());
        $wp_user_email = get_user_by('email', $fbuser->getField('email'));
        // if no exists we create it
        if ( !$wp_user && !$wp_user_email){
            
            $user_language = $request->get_param('user_language');
            $email = $fbuser->getField('email');
            /*$birth_date_raw = $fbuser->getBirthday();
            $dob = '';
            if ( $birth_date_raw && isset($birth_date_raw->date) ){
                $dob = str_replace(substr($birth_date_raw->date, 0, 10), '-', '' );
            }
            if ( $fbuser->getGender() ){
                $gender = $fbuser->getGender();
                if ( $gender != 'male' ){
                    $gender = 'female';
                }
            }*/
            $dob = date('Ymd', strtotime('-20 years') );
            $gender = 'male';
            
            $userdata = [
                            'user_login' => $email,
                            'display_name' => $fbuser->getFirstName(). ' '.$fbuser->getLastName(),
                            'first_name' => $fbuser->getFirstName(),
                            'last_name' => $fbuser->getLastName(),
                            'user_image' => '',
                            'role' => 'expeditioner',
                            'user_pass' => md5($fbuser->getId()),
                            'user_email' => $email,
                            'its_new' => true
                    ];

            $user_id = wp_insert_user( $userdata );

            if (is_wp_error( $user_id )) {
                $response = ['code'=>'auth_error', 'message'=> $user_id->get_error_message(), 'data'=> ['status'=>20] ];
                return new WP_REST_Response($response, 200 );
            }

            update_field( 'dob', $dob, 'user_'.$user_id);
            update_field( 'gender', $gender, 'user_'.$user_id);
            update_field( 'invitation', '', 'user_'.$user_id);
            update_field( 'status', 0, 'user_'.$user_id);

            update_user_meta($user_id, 'user_language', $user_language);
            update_user_meta($user_id, 'activation_key', uniqid() );
            
            
            do_action('expedition_created_user', $user_id);
            $session = array(
                'token' => $response['token'],
                'sl_token' => $response['sl_token'],
                'user_id' =>  $user_id,//$user['ID'],
                'status' => SESSION_ACTIVE,
                'role' => 'expeditioner',
            );
            Expedition_Helper::insertSessionRecord($session);
        }else{
            $user_id = $wp_user ? $wp_user->ID : $wp_user_email->ID;
        }
        
        $_user_id = 'user_'.$user_id;
        if ( $picture && !get_field('profile_picture', $_user_id ) ){
            $photo_id = Expedition_Helper::save_image_doc_from_url( $picture, 'file_'.$user_id.time().'.jpg', false, 0, $user_id );
            update_field('profile_picture', $photo_id, $_user_id );
        }
        update_field('facebook_id', $fbuser->getId(), $_user_id );
        //return array( $picture, get_field('profile_picture', $_user_id ), $_user_id, $photo_id );
        
        $user = Expedition_Helper::getUser($user_id);
        $response = array();
        $response['user'] = $user;
        $response['sl_token'] = FirebaseHelper::create_token( array('ID'=>$user['ID'], 'role'=> $user['role'], 'email'=> $user['email']) );
        $response['token'] = FirebaseHelper::create_token( array('ID'=>$user['ID'], 'role'=> $user['role'], 'email'=> $user['email']), true );
        
        return new WP_REST_Response($response, 200);
    }
    
    /**
     * Login a user,
     * 
     * @param type $request
     * @return \WP_REST_Response a token if we receive valid credentials
     */
    public function login($request) {
        
        $wp_user = wp_authenticate($request->get_param('email'), $request->get_param('password'));
        
        if (is_wp_error($wp_user)) {
            $message = apply_filters('expedition_api_error_message', $wp_user->get_error_message());
            $response = ['code'=>'auth_error', 'message'=> $message, 'status'=>401 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
//        $status = get_field( 'status', 'user_'.$wp_user->ID);
//        if ($status == 0 || $status == 2) {
////            your account needs to be verified before purchasing a Tour
//            $response = ['code'=>'auth_error', 'message'=> 'your account needs to be verified before purchasing a Tour', 'status'=>401 ];
//            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
//        }
        
        $user = Expedition_Helper::getUser($wp_user->ID);
        $user_language = $request->get_param('user_language');
        update_user_meta($wp_user->ID, 'user_language', $user_language);
        $response = array();
        $response['user'] = $user;
        $response['sl_token'] = FirebaseHelper::create_token( array('ID'=>$user['ID'], 'role'=> $user['role'], 'email'=> $user['email']) );
        $response['token'] = FirebaseHelper::create_token( array('ID'=>$user['ID'], 'role'=> $user['role'], 'email'=> $user['email']), true );
        
        $session = array(
            'token' => $response['token'],
            'sl_token' => $response['sl_token'],
            'user_id' => $user['ID'],
            'status' => SESSION_ACTIVE,
            'role' => $user['role'],
        );
        Expedition_Helper::insertSessionRecord($session);
        
        return new WP_REST_Response($response, 200);
    }
    
    /**
     * Register a new user,
     * 
     * @param type $request
     * @return \WP_REST_Response a token if we receive valid credentials
     */
    public function register($request) {
        global $firebase;
        
        
        
        
        $firstname = $request->get_param('firstname');
        $lastname = $request->get_param('lastname');
        $dob_raw = (string)$request->get_param('dob');
        $dob = @str_replace('-', '', $dob_raw);
        $gender = (string)$request->get_param('gender');
        $email = $request->get_param('email');
        $password = $request->get_param('password');
        $confirm_password = $request->get_param('confirm_password');
        $invitation = $request->get_param('invitation');
        
        $role = $request->get_param('role');
        $user_language = $request->get_param('user_language');
        $valid_roles = array('expeditioner', 'business');
        
        if ( $password != $confirm_password ){
            $response = ['code'=>'password_doesnt_match', 'message'=> "Password and password confirmation doesn't match", 'data'=> ['status'=>10] ];
            return new WP_REST_Response($response, 200 );
        }
        
        if ( strlen($password) < 6 ){
            $response = ['code'=>'password_doesnt_match', 'message'=> "Password needs at least 6 characters", 'data'=> ['status'=>70] ];
            return new WP_REST_Response($response, 200 );
        }
        
        if ( !in_array($role, $valid_roles) ){
            $response = ['code'=>'invalid_role', 'message'=> "The passed role is invalid", 'data'=> ['status'=>20] ];
            return new WP_REST_Response($response, 200 );
        }
        
        $userdata = [
			'user_login' => $email,
			'display_name' => "$firstname $lastname",
			'first_name' => $firstname,
                        'last_name' => $lastname,
			'user_image' => '',
			'role' => $role,
			'user_pass' => $password,
			'user_email' => $email,
			'its_new' => true
		];
        
        $user_id = wp_insert_user( $userdata );
        
        if (is_wp_error( $user_id )) {
            $response = ['code'=>'auth_error', 'message'=> $user_id->get_error_message(), 'data'=> ['status'=>20] ];
            return new WP_REST_Response($response, 200 );
        }
        
        update_field( 'dob', $dob, 'user_'.$user_id);
        update_field( 'gender', $gender, 'user_'.$user_id);
        update_field( 'invitation', $invitation, 'user_'.$user_id);
        update_field( 'status', 0, 'user_'.$user_id);
        
        $user = Expedition_Helper::getUser($user_id);
        $response = array();
        $response['user'] = $user;
        $response['sl_token'] = FirebaseHelper::create_token( array('ID'=>$user['ID'], 'role'=> $user['role'], 'email'=> $user['email']) );
        $response['token'] = FirebaseHelper::create_token( array('ID'=>$user['ID'], 'role'=> $user['role'], 'email'=> $user['email']), true );
        
        update_user_meta($user_id, 'status', USER_ACTIVE);
        update_user_meta($user_id, 'user_language', $user_language);
        update_user_meta($user_id, 'activation_key', uniqid() );
        
        //$firebase_user = array('objectId'=> (string)$user_id, 'phone'=> (string)$phone, 'email'=> $email );
        //$firebase->set($firebase_user, "User/{$firebase_user['objectId']}");
        
        do_action('expedition_created_user', $user_id);
        
        
        $session = array(
            'token' => $response['token'],
            'sl_token' => $response['sl_token'],
            'user_id' => $user['ID'],
            'status' => SESSION_ACTIVE,
            'role' => $user['role'],
        );
        Expedition_Helper::insertSessionRecord($session);
        
        return new WP_REST_Response($response, 200);
    }
    
}

add_action('rest_api_init', function () {
    $controller = new auth_API();
    $controller->register_routes();
});
