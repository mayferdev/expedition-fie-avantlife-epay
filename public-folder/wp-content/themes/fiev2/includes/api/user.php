<?php

class users_API extends base_API {

    public function register_routes() {
        
        $namespce = EXPEDITION_PRODUCTION_API_VERSION;
        
        
        register_rest_route($namespce, '/user/search_expeditioners', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'search_expeditioners'),
                'args' => array(
                    'search' => [
                        'required' => true,
                        'description' => 'The text to search',
                        'type' => 'string'
                    ],
                ),
            ),
                )
        );
        
        register_rest_route($namespce, '/user/list/expeditioner', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_expeditioners'),
                'args' => array(
                    
                ),
            ),
                )
        );
        
        register_rest_route($namespce, '/user/me', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_current'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    
                ),
            ),
                )
        );
        
        register_rest_route($namespce, '/user/me/emblems', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_emblems'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    
                ),
            ),
                )
        );
        
        register_rest_route($namespce, '/user/me', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'update_current_user'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    'firstname' => [
                        'required' => false,
                        'description' => "User's full name",
                        'type' => 'string',
                        'validate_callback' => 'rest_validate_request_arg'
                    ],
                    'lastname' => [
                        'required' => false,
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
                    'one_signal_id' => [
                        'required' => false,
                        'description' => "The ID in oneSignal, used to send single push notification",
                        'type' => 'string'
                    ],
                    'user_language' => [
                        'required' => false,
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
        
        register_rest_route($namespce, '/user/me/gallery', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_gallery'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    
                ),
            ),
                )
        );
        
        register_rest_route($namespce, '/user/places', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_places'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    
                ),
            ),
                )
        );
        
        register_rest_route($namespce, '/user/photo', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'upload_photo'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    'profile' => [
                        'required' => false,
                        'description' => 'Will be used as profile photo?',
                        'type' => 'integer'
                    ],
                    'latitude' => [
                        'required' => false,
                        'description' => 'The photo latitude',
                        //'type' => 'string'
                    ],
                    'longitude' => [
                        'required' => false,
                        'description' => 'The photo longitude',
                        //'type' => 'string'
                    ],
                    'placeID' => [
                        'required' => false,
                        'description' => 'The Google PlaceId of the photo',
                        'type' => 'string'
                    ],
                    
                ),
            ),
                )
        );
        
        register_rest_route($namespce, '/user/photo', array(
            array(
                'methods' => WP_REST_Server::DELETABLE,
                'callback' => array($this, 'delete_photo'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    'photo_id' => [
                        'required' => true,
                        'description' => 'The photo ID to delete',
                        'type' => 'integer'
                    ],
                ),
            ),
                )
        );
        
        register_rest_route($namespce, '/user/photo/make_profile', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'make_profile_photo'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    'photo_id' => [
                        'required' => true,
                        'description' => 'The photo ID to make the profile photo',
                        'type' => 'integer'
                    ],
                ),
            ),
                )
        );
        
        register_rest_route($namespce, '/user/(?P<id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_third_user'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    'id' => [
                        'required' => true,
                        'description' => 'The user id to follow',
                        'type' => 'integer'
                    ],
                ),
            ),
                )
        );
        
        register_rest_route($namespce, '/user/(?P<id>[\d]+)/follow', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'follow_user'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    'id' => [
                        'required' => true,
                        'description' => 'The user id to follow',
                        'type' => 'integer'
                    ],
                ),
            ),
                )
        );
        
        register_rest_route($namespce, '/user/activity', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_activity'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    
                ),
            ),
                )
        );
        
        register_rest_route($namespce, '/user/me/uploaded_photos', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_uploaded_photos'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                ),
            ),
                )
        );
        
        register_rest_route($namespce, '/user/password/reset', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'reset_password'),
                // 'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    'email' => [
                        'required' => true,
                        'description' => 'Email address to reset password',
                        'type' => 'email'
                    ],
                    'password' => [
                        'required' => true,
                        'description' => 'The new password',
                        'type' => 'string'
                    ],
                    'password_confirm' => [
                        'required' => true,
                        'description' => 'The new password confirmation',
                        'type' => 'string'
                    ],
                ),
            ),
                )
        );
        
        register_rest_route($namespce, '/user/password/confirm', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'reset_password_confirm'),
                // 'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    'hash' => [
                        'required' => true,
                        'description' => 'The new password hash',
                        'type' => 'string'
                    ],
                ),
            ),
                )
        );
        
        register_rest_route($namespce, '/user/activate', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'user_activate'),
                // 'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    'hash' => [
                        'required' => true,
                        'description' => 'The activation hash',
                        'type' => 'string'
                    ],
                ),
            ),
                )
        );
        
    }
    
    public static function reset_password( $request ){
        
        $email = $request->get_param('email');
        $password = $request->get_param('password');
        $password_confirm = $request->get_param('password_confirm');
        $user = get_user_by( 'email', $email );
        if ( !$user ){
            $response = ['code'=>'invalid_user', 'message'=> "Invalid email address", 'data'=> ['status'=>603] ];
            return new WP_REST_Response($response, 200 );
        }
        if (strlen($password) < 6 ){
            $response = ['code'=>'invalid_user', 'message'=> "Password needs at least 6 characters", 'data'=> ['status'=>603] ];
            return new WP_REST_Response($response, 200 );
        }
        if ( $password != $password_confirm ){
            $response = ['code'=>'invalid_user', 'message'=> "Password and password confirm don't match", 'data'=> ['status'=>603] ];
            return new WP_REST_Response($response, 200 );
        }
        
        $hash = md5(uniqid());
        update_user_meta($user->ID, '_new_password', $password);
        update_user_meta($user->ID, '_new_password_hash', $hash);
        
        /*************************************************************************/
        
        $user_language = 'en';//get_user_meta($user_id, 'user_language', true);
        $valid_languages = array('en', 'es');
        if (!in_array($user_language, $valid_languages) ){
            $user_language = $valid_languages[0];
        }

        $user_id = $user->ID;
        $field = "reset_password_content_$user_language";
        $template = get_field($field, 'options');
        
        $first_name = get_user_meta($user_id, 'first_name', true);
        $last_name = get_user_meta($user_id, 'last_name', true);
        $user_full_name = "$first_name $last_name";
        $user_email = $email && is_email($email) ? $email : $user->data->user_email;
        
        $confirmation_link = get_bloginfo('home')."/api/v1/user/password/confirm?hash=$hash";
        $message = str_replace(
                array('{user_firstname}', '{user_last_name}', '{user_full_name}', '{user_email}', '{confirmation_link}'), 
                array($first_name, $last_name, $user_full_name, $user_email, $confirmation_link), 
                $template);
        
        $subject = str_replace(
                array('{user_firstname}', '{user_last_name}', '{user_full_name}', '{user_email}', '{confirmation_link}'), 
                array($first_name, $last_name, $user_full_name, $user_email, $confirmation_link), 
                get_field("reset_password_subject_$user_language", 'options'));
        
        $sent = Notifications::send( $user_email, $subject, $message );
        Expedition_Helper::logMessage( "Reset password email ".($sent ? 'sent' : 'failed')." to $user_email", 'emails_sent.txt');
        
        /*************************************************************************/
        
        return new WP_REST_Response(
                array(
                    'success'=>true,
                    'message'=> "All ready, please check your email account to confirm the change"
                    ),
                200 );
    }
    
    public static function reset_password_confirm( $request ){
        
        $hash = $request->get_param('hash');
        $users =  get_users(
                    array(
                     'meta_key' => '_new_password_hash',
                     'meta_value' => $hash,
                     'number' => 1,
                     'count_total' => false
                    )
                   );
        $user = is_array($users) && count($users) ? $users[0] : false;
        if ( !$user ){
            $html = Expedition_Helper::get_html_notification("<h3>Invalid or expired hash</h3>");
            header( 'Content-Type: text/html; charset=utf-8' );
            echo $html;
            exit();
        }
        
        $password = get_user_meta($user->ID, '_new_password', true);
        update_user_meta($user->ID, '_new_password', '');
        update_user_meta($user->ID, '_new_password_hash', '');
        wp_set_password($password, $user->ID);
        
        $copy = '<h3>Password confirmation successfully</h3>
                    <p>
                        Open Expedition App, and continue your adventure!
                    </p>';
        
        $html = Expedition_Helper::get_html_notification($copy);
            header( 'Content-Type: text/html; charset=utf-8' );
            echo $html;
            exit();
    }
    
    public static function user_activate( $request ){
        
        $hash = $request->get_param('hash');
        $users =  get_users(
                    array(
                     'meta_key' => '_activation_hash',
                     'meta_value' => $hash,
                     'number' => 1,
                     'count_total' => false
                    )
                   );
        $user = is_array($users) && count($users) ? $users[0] : false;
        if ( !$user ){
            $html = Expedition_Helper::get_html_notification("<h3>Invalid or expired hash</h3>");
            header( 'Content-Type: text/html; charset=utf-8' );
            echo $html;
            exit();
        }
        
        update_field('status', 1, 'user_'.$user->ID);
        
        $copy = '<h3>User activated successfully</h3>
                    <p>
                        Open Expedition App, and continue your adventure!
                    </p>';
        
        $html = Expedition_Helper::get_html_notification($copy);
            header( 'Content-Type: text/html; charset=utf-8' );
            echo $html;
            exit();
    }
    
    /**
     * Returns the list of upload photos by current user
     */
    public function get_uploaded_photos($request){
        
        $user_id = (int)$this->user['ID'];
        
        $query = new WP_Query( array( 
            'post_type' => 'attachment', 
            'post_status'=>'inherit', 
            'author'    => $user_id, 
            // 'orderby'       => 'date', 'order' => 'DESC', 'posts_per_page'=> 50,
            // 'meta_query'    => $meta_query
        ) );
        $gallery = array();
        
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                if ( get_field('private', get_the_ID()) ){
                    continue;
                }
                $img = Expedition_Helper::getAllImageSizes(get_the_ID(), true);
                if ( isset($img['medium']['url']) ){
                    $gallery[] = $img;
                }
            }
            wp_reset_postdata(); // Restore original Post Data
        }
        
        return new WP_REST_Response(array('success'=>true, 'gallery'=>$gallery, 'message'=> 'Success'), 200 );
        
    }
    
    /**
     * Follow a user
     * 
     * @return \WP_REST_Response
     */
    public static function follow_user( $request ){
        
        $following_id = $request->get_param('id');
        $follower_id = $this->user['ID'];
        
        if ( !get_user_by( 'id', $following_id ) ){
            $response = ['code'=>'invalid_user', 'message'=> "Invalid user", 'data'=> ['status'=>603] ];
            return new WP_REST_Response($response, 200 );
        }
        if ( Expedition_Helper::getFollowRecordByUsers($follower_id, $following_id) ){
            $response = ['code'=>'invalid_user', 'message'=> "You are already following to this user", 'data'=> ['status'=>604] ];
            return new WP_REST_Response($response, 200 );
        }
        Expedition_Helper::insertFollowRecord($follower_id, $following_id);
        
        $user = Expedition_Helper::getUser($following_id, false, false, true);
        $user['followed'] = Expedition_Helper::getFollowRecordByUsers($follower_id, $following_id);
        Expedition_Helper::insertActivityRecord($follower_id, 'follow', $following_id);
        do_action('expedition_following_user', $follower_id, $following_id);
        
        return new WP_REST_Response(
                array(
                    'success'=>true,
                    'message'=> 'You are following to the user',
                    'user' => $user
                    ),
                200 );
        
    }
    
    /**
     * Follow a user
     * 
     * @return \WP_REST_Response
     */
    public static function get_third_user( $request ){
        
        $other_user_id = $request->get_param('id');
        $user_id = $this->user['ID'];
        
        if ( !get_user_by( 'id', $other_user_id ) ){
            $response = ['code'=>'invalid_user', 'message'=> "Invalid user", 'data'=> ['status'=>603] ];
            return new WP_REST_Response($response, 200 );
        }
        $user = Expedition_Helper::getUser($other_user_id, false, false, true);
        $user['followed'] = Expedition_Helper::getFollowRecordByUsers($user_id, $other_user_id);
        $savedPosts = Expedition_Helper::getSavedPostsByUser($user_id);
        $travel_notes = array();
        if (is_array($savedPosts) && count($savedPosts) >0 ){
            foreach ($savedPosts as $saved) {
                if ($tn = Expedition_Helper::formatTravelNote($saved->post_id, $user_id)){
                        $travel_notes[] = $tn;
                }
            }
        }
        $user['travel_notes'] = $travel_notes;
        
        return new WP_REST_Response(
                array(
                    'success'=>true,
                    'user' => $user
                    ),
                200 );
        
    }
    
    /**
     * Get the activity feed of current user
     * 
     * @return \WP_REST_Response
     */
    public static function get_activity( $request ){
        
        $user_id = $this->user['ID'];
        $activities = (array)Expedition_Helper::getActivityRecordsForUser($user_id);
        
        if (is_array($activities) && count($activities) > 0 ){
            foreach ($activities as $key => $activity) {
                
                
                // Expedition_Helper::insertActivityRecord($booking->owner_id, 'book_public_tour', $tour_id);
                // Expedition_Helper::insertActivityRecord($user_id, 'book_public_tour', $tour_id);
                // Expedition_Helper::insertActivityRecord($modified_invite->owner_id, "response_tour_".$modified_invite->status, $modified_invite->id);
                
                if ( $activity->type == 'follow' ){
                    
                    if ( $activity->ref_id == $user_id ){ // is receiving action
                        $_user = get_userdata($activity->user_id);
                        $second_line = "Now following you";
                    }else{
                        $_user = get_userdata($activity->ref_id);
                        $second_line = "Now you follow";
                    }
                    $_fullname = $_user->first_name . ' '. $_user->last_name ;
                    $activity->first_line = "$_fullname";// "Felicidades, nuevo logro";
                    $activity->second_line = $second_line;// "Second line";

                    $img = wp_get_attachment_image_src(get_field('profile_picture', 'user_'.$_user->ID), 'thumbnail');
                    $profile_picture = @$img[0];
                    $activity->image = $profile_picture ? $profile_picture : 'https://ask.libreoffice.org/m/default/media/images/nophoto.png?v=20';
                    $activity->bottom_right = "";// "+350";
                    $activity->border = 16;
                    
                }else if ( $activity->type == 'book_public_tour' ){
                    $gallery = get_field('gallery', $activity->ref_id);
                    $img = wp_get_attachment_image_src( @$gallery[0]['ID'], 'thumbnail');
                    $_picture = @$img[0];
                    $activity->first_line = get_the_title( $activity->ref_id );// "Felicidades, nuevo logro";
                    $activity->second_line = 'New booking received ';// "Second line";                    
                    
                    $activity->image = $_picture ? $_picture : '';
                    $activity->bottom_right = "+350";// "+350";
                    $activity->border = 16;
                    
                }else if ( $activity->type == 'booking_confirmed' ){
                    $booking_id = $activity->ref_id;
                    $booking = Expedition_Helper::getUserBooking($booking_id);
                    
                    $gallery = get_field('gallery', $booking->tour_id);
                    $img = wp_get_attachment_image_src( @$gallery[0]['ID'], 'thumbnail');
                    $_picture = @$img[0];
                    
                    $activity->first_line = get_the_title( $booking->tour_id );// "Felicidades, nuevo logro";
                    $activity->second_line = 'Your booking has been confirmed';// "Second line";                    
                    
                    $activity->image = $_picture ? $_picture : '';
                    $activity->bottom_right = "";// "+350";
                    $activity->border = 16;
                    
                }else if ( $activity->type == 'booking_invitation' ){
                    $gallery = get_field('gallery', $activity->ref_id );
                    $img = wp_get_attachment_image_src( @$gallery[0]['ID'], 'thumbnail');
                    $_picture = @$img[0];
                    
                    $activity->first_line = get_the_title( $activity->ref_id );// "Felicidades, nuevo logro";
                    $activity->second_line = 'You have been invited';// "Second line";                    
                    
                    $activity->image = $_picture ? $_picture : '';
                    $activity->bottom_right = "";// "+350";
                    $activity->border = 16;
                }else if ( $activity->type == 'liked_post' ){
                    $thumbnail_id = get_field( 'thumbnail', $activity->ref_id );
                    $img = wp_get_attachment_image_src( @$thumbnail_id, 'thumbnail');
                    $_picture = @$img[0];
                    
                    $_user = get_userdata($activity->ref_id2);
                    $_fullname = $_user->first_name . ' '. $_user->last_name ;
                    
                    $activity->first_line = $_fullname;// "Felicidades, nuevo logro";
                    $activity->second_line = 'Liked '.get_the_title( $activity->ref_id );// "Second line";
                    
                    $activity->image = $_picture ? $_picture : '';
                    $activity->bottom_right = "";// "+350";
                    $activity->border = 16;
                }
                $activity->date_timestamp = strtotime($activity->created_at);
                
            }
        }
        
        return new WP_REST_Response(
                array(
                    'success'=>true,
                    'activity_feed' => $activities
                    ),
                200 );
        
    }
    
    
    /**
     * Returns the list of users with role 'expeditioner'
     * 
     * @param type $request
     * @return type
     */
    public function get_expeditioners($request){
        return new WP_REST_Response(
                array(
                    'success'=>true, 
                    'expeditioners' => self::get_users($request, 'expeditioner')
                    ), 
                200 );
    }
    
    /**
     * Returns the list of users with role 'expeditioner'
     * 
     * @param type $request
     * @return type
     */
    public function search_expeditioners($request){
        $search = $request->get_param('search');
        return new WP_REST_Response(
                array(
                    'success'=>true,
                    'expeditioners' => self::get_users($request, 'expeditioner', $search, $request->get_param('object'))
                    ), 
                200 );
    }
    
    /**
     * Returns the user's list
     * 
     * @param type $request
     * @return \WP_REST_Response a token if we receive valid credentials
     */
    public function get_users($request, $role, $search = false, $object = false) {
        
        $args = array(
            'number' => -1,
            'role' => $role,
            'fields' => array('ID'),
            'meta_key' => 'last_name',
            'orderby' => 'meta_value',
            'order' => 'ASC'
        );
        if ( $search !== false ){
            $search_args = array(
                'search'         => '*'.esc_attr( $search ).'*',
                'search_columns' => array(
                    'user_login',
                    'user_nicename',
                    'display_name',
                    'user_email',
                    'user_url',
                ),
            );
            $args = array_merge($args, $search_args);
        }
        
        $query = new WP_User_Query($args);
        $results = $query->get_results();
        $users = array();
        if ( $results ){
            foreach ($results as $user) {
                //$users[] = Expedition_Helper::getUser( $user->ID, true );
                $users[] = !$object ? Expedition_Helper::getUserExpeditioner($user->ID) : Expedition_Helper::getUser($user->ID, false);
            }
        }
        
        return $users;
        
    }
    
    /**
     * Convert a photo in profile photo
     * 
     * @return \WP_REST_Response
     */
    public static function make_profile_photo( $request ){
        
        $photo_id = $request->get_param('photo_id');

        $post = get_post($photo_id);
        if ( $post->post_type != 'attachment' ){
            $response = ['code'=>'invalid_photo', 'message'=> "You can't use this attachment :D", 'data'=> ['status'=>606] ];
            return new WP_REST_Response($response, 200 );
        }
        
        if ( $post->post_author != $this->user['ID'] ){
            $response = ['code'=>'invalid_photo', 'message'=> "You can't use this attachment because you aren't the owner", 'data'=> ['status'=>604] ];
            return new WP_REST_Response($response, 200 );
        }
        
        $user_id = 'user_'.$this->user['ID'];
        update_field('main_picture', $photo_id, $user_id );
        
        return new WP_REST_Response(
                array(
                    'success'=>true, 
                    'message'=> 'Profile updated succesfully',
                    // 'user' => Expedition_Helper::getUser($this->user['ID'])
                    ), 
                200 );
        
    }
    
    /**
     * Deletes a photo, for gallery only
     * 
     * @return \WP_REST_Response
     */
    public static function delete_photo( $request ){
        
        $photo_id = $request->get_param('photo_id');

        $post = get_post($photo_id);
        if ( $post->post_type != 'attachment' ){
            $response = ['code'=>'invalid_photo', 'message'=> "You can't delete this attachment :D", 'data'=> ['status'=>606] ];
            return new WP_REST_Response($response, 200 );
        }
        
        if ( $post->post_author != $this->user['ID'] ){
            $response = ['code'=>'invalid_photo', 'message'=> "You can't delete this attachment because you aren't the owner", 'data'=> ['status'=>604] ];
            return new WP_REST_Response($response, 200 );
        }
        
        $user_id = 'user_'.$this->user['ID'];
        $main_picutre = get_field('main_picture', $user_id, false );
        
        if ( $main_picutre == $photo_id ){
            $response = ['code'=>'invalid_photo', 'message'=> "You can't delete the profile photo", 'data'=> ['status'=>603] ];
            return new WP_REST_Response($response, 200 );
        }
        $deleted = wp_delete_attachment( $photo_id );
        if ( false === $deleted ){
            $response = ['code'=>'internal_error', 'message'=> "There was an error, please try again", 'data'=> ['status'=>605] ];
            return new WP_REST_Response($response, 200 );
        }
        
        $gallery_ids = (array)get_field( 'gallery', $user_id, false );
        if ( count($gallery_ids)>0 ){
            foreach ($gallery_ids as $key => $id) {
                if ( $photo_id == $id ){
                    unset($gallery_ids[$key]);
                }
            }
            update_field('gallery', $gallery_ids, $user_id);
        }
        
        return new WP_REST_Response(
                array(
                    'success'=>true, 
                    'deleted' => $deleted,
                    'message'=> 'File deleted succesfully',
                    'user' => Expedition_Helper::getUser($this->user['ID'])
                    ), 
                200 );
        
    }
    
    /**
     * Uploads a photo, for profile or gallery
     * 
     * @return \WP_REST_Response
     */
    public static function upload_photo( $request ){
        
        //$response = ['code'=>'missing_photo', 'files'=> $_FILES, 'message'=> "Missing attachment", 'data'=> ['status'=>600] ];
        //return new WP_REST_Response($response, 200 );
            
        // handle uploaded file
        // $_FILES[ 'file'] is for old app versions
        if( empty( $_FILES ) || ( !isset($_FILES[ 'file']) && !isset($_FILES[ 'file_0']) ) ){
            $response = ['code'=>'missing_photo', 'message'=> "Missing attachment", 'data'=> ['status'=>600] ];
            return new WP_REST_Response($response, 200 );
        }
        if ( isset($_FILES[ 'file_0']) ){
            $file_id = array();
            for ($index = 0; $index < 9; $index++) {
                $file_id[] = Expedition_Helper::save_image_doc_from_url( false, 'file_'.time().'_'.$index.'.jpg', $_FILES[ "file_$index"], 0, $this->user['ID'] );
            }
        }else{
            $file_id = Expedition_Helper::save_image_doc_from_url( false, 'file_'.time().'.jpg', $_FILES[ 'file'], 0, $this->user['ID'] );
        }
        
        
        if ( is_wp_error($file_id) ){
            $response = ['code'=>'file_error', 'message'=> "Error saving the file", 'data'=> ['status'=>601, 'file'=>$file_id] ];
            return new WP_REST_Response($response, 200 );
        }else{
            $user_id = 'user_'.$this->user['ID'];
            
            if ( (int)$request->get_param('profile') == 1 || (int)$request->get_param('main_picture') == 1 ){
                if ( $this->user['main_picture'] && isset($this->user['main_picture']['id']) ){
                    // to prevent have multiple profile photos
                    // at this moment we'll keep the file in the gallery
                    // wp_delete_attachment( $this->user['main_picture']['id'], true );
                }
                
                update_field('main_picture', $file_id, $user_id );
            }
            if ( (int)$request->get_param('profile_picture') == 1 ){
                update_field('profile_picture', $file_id, $user_id );
            }
            
            if ( $request->get_param('placeID') && strlen($request->get_param('placeID')) > 5 ){
                $latitude = $request->get_param('latitude');
                $longitude = $request->get_param('longitude');
                $placeID = $request->get_param('placeID');
                
                $note = $request->get_param('note');
                $country = $request->get_param('country');
                $administrative_area_level_1 = (string)$request->get_param('administrative_area_level_1');
                $placeName = $request->get_param('placeName');
                $address = $request->get_param('address');
                $administrative_area_level_1 = str_replace(array(' Department'), array(''), $administrative_area_level_1);
                
                if ( is_array($file_id) ){
                    foreach ($file_id as $_file_id) {
                        update_post_meta($_file_id, 'latitude', $latitude);
                        update_post_meta($_file_id, 'longitude', $longitude);
                        update_post_meta($_file_id, 'placeID', $placeID);
                    }
                }else{
                    update_post_meta($file_id, 'latitude', $latitude);
                    update_post_meta($file_id, 'longitude', $longitude);
                    update_post_meta($file_id, 'placeID', $placeID);
                    
                    update_post_meta($file_id, 'note', $note);
                    update_post_meta($file_id, 'country', $country);
                    update_post_meta($file_id, 'administrative_area_level_1', $administrative_area_level_1);
                    update_post_meta($file_id, 'address', $address);
                    update_post_meta($file_id, 'placeName', $placeName);
                }
                if ( $administrative_area_level_1 || strlen($administrative_area_level_1) < 1 ){
                    Expedition_Helper::fillPlacesForAtachment($file_id);
                }
                
            }
            
            $gallery_ids = (array)get_field( 'gallery', $user_id, false );
            $gallery = array();
            if ( count($gallery_ids)>0 ){
                foreach ($gallery_ids as $id) {
                    if ( (int)$id > 0 ){
                        $gallery[] = $id;
                    }
                }
            }
            // insert before
            if ( is_array($file_id) ){
                foreach ($file_id as $_file_id) {
                    array_unshift($gallery, $_file_id);
                }
            }else{
                array_unshift($gallery, $file_id);
            }
            
            // insert after
            //$gallery[] = $file_id;
            
            update_field('gallery', $gallery, $user_id);
        }
        
        return new WP_REST_Response(
                array(
                    'success'=>true, 
                    // 'ids' => $gallery_ids, 
                    // 'gallery'=> $gallery, 
                    'message'=> 'File saved succesfully',
                    //'file' => Expedition_Helper::getAllImageSizes( $file_id ),
                    //'file_id' =>$file_id,
                    'user' => Expedition_Helper::getUser($this->user['ID'])
                    ), 
                200 );
        
    }
    
    /**
     * Returns the current user
     * 
     * @param type $request
     * @return \WP_REST_Response
     */
    public function get_current($request) {
        
        return new WP_REST_Response(array('user'=>Expedition_Helper::getUser($this->user['ID']), 'success'=> true), 200);
    }
    
    /**
     * Returns the badges for current user
     * 
     * @param type $request
     * @return \WP_REST_Response
     */
    public function get_emblems($request) {
        $user_id = $this->user['ID'];
        $bookings = Expedition_Helper::getUserBookingByUser($user_id);
        $emblems = array();
        $_emblems = array();
        if ( $bookings && is_array($bookings) && count($bookings) > 0 ){
            foreach ($bookings as $booking) {
                //if ( $booking->status != 1 ){
                //    continue;
                //}
                if ( !isset($emblems[$booking->owner_id]) ){
                    $owner_name = get_user_meta( (int)$booking->owner_id, 'first_name', true ) . ' ' .get_user_meta( (int)$booking->owner_id, 'last_name', true );
                    $main_picture_id = get_field('main_picture', "user_{$booking->owner_id}");
                    $main_picture = wp_get_attachment_image_src( $main_picture_id );
                    $emblems[$booking->owner_id] = array(
                        'count'=>0, 
                        'owner_id'=>$booking->owner_id, 
                        'owner_name'=>( strlen($owner_name)>1 ?$owner_name:'Unknown'), 
                        'image'=> ($main_picture ? $main_picture[0] : 'https://vignette.wikia.nocookie.net/marsargo/images/5/52/Unknown.jpg/revision/latest?cb=20170904102656') );
                }
                $emblems[$booking->owner_id]['count']++;
            }
            if ( count($emblems)>0 ){
                foreach ($emblems as $key => $emblem) {
                    $emblem['roman'] = Expedition_Helper::numberToRomanRepresentation( $emblem['count'] );
                    $_emblems[] = $emblem;
                }
            }
        }
        
        return new WP_REST_Response(array('emblems'=>(array)$_emblems, 'success'=> true), 200);
    }
    
    /**
     * Returns the user's gallery
     * 
     * @param type $request
     * @return \WP_REST_Response
     */
    public function get_gallery($request) {
        
        return new WP_REST_Response(array('user'=>$this->user, 'success'=> true), 200);
    }
    
    /**
     * Returns the user's places, taken from photo locations
     * 
     * @param type $request
     * @return \WP_REST_Response
     */
    public function get_places($request){
        
        $wp_user = get_user_by('id', $this->user['ID']);
        
        $gallery_ids = get_field( 'gallery', 'user_'.$wp_user->ID, false );
        $gallery = array();
        if ( is_array($gallery_ids) && count($gallery_ids)>0 ){
            $query = new WP_Query( array( 'post_type' => 'attachment', 'post_status'=>'inherit', 'post__in' => $gallery_ids, 'orderby'=> 'date', 'order' => 'DESC', 'posts_per_page'=> 1000 ) );
            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    $img = Expedition_Helper::getAllImageSizes(get_the_ID());
                    if ( isset($img['medium']['url']) ){
                        $gallery[] = $img;
                    }
                }
                wp_reset_postdata(); // Restore original Post Data
            }
            //usort($gallery, 'galleryDateCompare');
        }
        $user['gallery'] = $gallery;
        
        $places = array();
        if ( isset($user['gallery']) && $user['gallery']> 0 ){
            foreach ($user['gallery'] as $item) {
                $places[ $item['placeID'] ] = $item['placeID'];
            }
        }
        $user['places'] = count($places);
        
        return new WP_REST_Response(array('user'=>$this->user, 'success'=> true), 200);
    }
    
    
    /**
     * Updates data the current user
     * 
     * @param type $request
     * @return \WP_REST_Response
     */
    public function update_current_user($request) {
        
        $valid_fields = array('user_language', 'firstname', 'lastname', 'dob', 'gender', 'one_signal_id', 
            'nit', 'nit_name', 'nit_address', 'dpi', 'phone');
        
        foreach ($valid_fields as $field_key) {
            $field_value = $request->get_param($field_key);
            if ( $field_value ){
                $this->updateUserValue( $field_key, $field_value );
            }
        }
        
        return new WP_REST_Response(array( 'success'=> true), 200);
        //return new WP_REST_Response(array('user'=>Expedition_Helper::getUser($this->user['ID']), 'success'=> true), 200);
    }
    
    
    public function updateUserValue( $key, $value ){
        $user_id = $this->user['ID'];
        
        if ( $key == 'one_signal_id' && strlen($value) > 0 ){
            $users = get_users(array(
			'meta_key' => 'one_signal_id', 
			'meta_value' => $value, 
			'count_total' => false,
                        'fields'        => array('ID')
		)
            );
            if ( is_array($users) && count($users) >0 ){
                foreach ($users as $user) {
                    update_field( 'one_signal_id', '', 'user_'.$user->ID);
                }
            }
        }
        
        switch ($key) {
            case 'user_language':
                update_user_meta($user_id, 'user_language', $value);
                break;
            case 'firstname':
                wp_update_user( array( 'ID' => $user_id, 'first_name' => $value ) );
                break;
            case 'lastname':
                wp_update_user( array( 'ID' => $user_id, 'last_name' => $value ) );
                break;
            case 'nit':
            case 'nit_name':
            case 'nit_address':
            case 'dpi':
            case 'phone':
            case 'dob':
            case 'gender':
            case 'one_signal_id':
                update_field( $key, $value, 'user_'.$user_id);
                break;
            default:
                break;
        }
    }
    
    
}
///phpinfo();

add_action('rest_api_init', function () {
    $controller = new users_API();
    $controller->register_routes();
});
//
//if ( isset($_GET['foo']) && $_GET['foo'] == 'bar' ){
//    $wp_user_query2 = new WP_User_Query(
//            array(
//            'role' => $role,
//            'number' => -1,
//            'fields' => array('ID'),
//          )
//         );
//    $users2 = $wp_user_query2->get_results();
//    foreach ($users2 as $user) {
//        $wp_user = get_user_by('id', $user->ID);
//        if ( $wp_user->get('first_name') ){
//            wp_update_user( array ( 'ID' => $user->ID, 'display_name' => esc_attr( $wp_user->get('first_name').' '.$wp_user->get('last_name') ) ) );
//            echo "updated $user->ID<br/>";
//        }
//    }
//}