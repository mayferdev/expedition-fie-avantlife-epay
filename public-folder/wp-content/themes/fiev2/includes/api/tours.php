<?php

class tours_API extends base_API {

    public function register_routes() {
        
        $namespace = EXPEDITION_PRODUCTION_API_VERSION;
        register_rest_route($namespace, '/tours', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_tours'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    'tour_ids' => [
                        'required' => false,
                        'description' => "List of tour ids splitted by '|', example 3|36|354|346...",
                        'type' => 'string'
                    ],
                    )
            ),
                )
        );
        
        register_rest_route($namespace, '/tours/(?P<id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_tour'),
                'permission_callback' => array( $this, 'check_token' ),
            ),
                )
        );
        
        register_rest_route($namespace, '/tours/(?P<id>[\d]+)/pay_with_credit_card', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'pay_with_credit_card'),
                'permission_callback' => array( $this, 'check_token' ),
            ),
                )
        );
        
        register_rest_route($namespace, '/optin/tour/(?P<id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_optin_tour'),
            ),
                )
        );
        
        register_rest_route('forms', '/optin/(?P<id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'print_optin_form'),
            ),
                )
        );
        
        register_rest_route($namespace, '/tours/past', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_past_tours'),
                'permission_callback' => array( $this, 'check_token' ),
            ),
                )
        );
        
        register_rest_route($namespace, '/tours/(?P<id>[\d]+)/booking_expeditioners_info', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_tour_booking_expeditioners_info'),
                'permission_callback' => array( $this, 'check_token' ),
            ),
                )
        );
        
        register_rest_route($namespace, '/tours/(?P<id>[\d]+)/booking_expeditioners_info/web', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_tour_booking_expeditioners_info_from_web'),
                
                // 'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    'first_name' => [
                        'required' => false,
                        'description' => "The expeditioner's first_name",
                        'type' => 'string'
                    ],
                    'last_name' => [
                        'required' => false,
                        'description' => "The expeditioner's last_name",
                        'type' => 'string'
                    ],
                    'age' => [
                        'required' => false,
                        'description' => "The expeditioner's age",
                        'type' => 'string'
                    ],
                    'dpi_passport' => [
                        'required' => false,
                        'description' => "The expeditioner's dpi_passport",
                        'type' => 'string'
                    ],
                    'phone' => [
                        'required' => false,
                        'description' => "The expeditioner's phone",
                        'type' => 'string'
                    ],
                    )
            ),
                )
        );
        
        register_rest_route($namespace, '/tours/(?P<tour_id>[\d]+)/book_and_pay/web', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'bookWebWithPaymentOption'),
                
                // 'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    'first_name' => [
                        'required' => false,
                        'description' => "The expeditioner's first_name",
                        'type' => 'string'
                    ],
                    'last_name' => [
                        'required' => false,
                        'description' => "The expeditioner's last_name",
                        'type' => 'string'
                    ],
                    'age' => [
                        'required' => false,
                        'description' => "The expeditioner's age",
                        'type' => 'string'
                    ],
                    'dpi_passport' => [
                        'required' => false,
                        'description' => "The expeditioner's dpi_passport",
                        'type' => 'string'
                    ],
                    'phone' => [
                        'required' => false,
                        'description' => "The expeditioner's phone",
                        'type' => 'string'
                    ],
                    )
            ),
                )
        );
        
        register_rest_route($namespace, '/tours/(?P<id>[\d]+)/booking_expeditioners_info', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_tour_booking_expeditioners_info'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    'first_name' => [
                        'required' => false,
                        'description' => "The expeditioner's first_name",
                        'type' => 'string'
                    ],
                    'last_name' => [
                        'required' => false,
                        'description' => "The expeditioner's last_name",
                        'type' => 'string'
                    ],
                    'age' => [
                        'required' => false,
                        'description' => "The expeditioner's age",
                        'type' => 'string'
                    ],
                    'dpi_passport' => [
                        'required' => false,
                        'description' => "The expeditioner's dpi_passport",
                        'type' => 'string'
                    ],
                    'phone' => [
                        'required' => false,
                        'description' => "The expeditioner's phone",
                        'type' => 'string'
                    ],
                    )
            ),
                )
        );
        
        
        register_rest_route($namespace, '/tours/(?P<id>[\d]+)/expeditioners', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_tour_expeditioners'),
                'permission_callback' => array( $this, 'check_token' ),
            ),
                )
        );
        
        
        
        register_rest_route($namespace, '/tours/private/response', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'private_response'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    'tour_id' => [
                        'required' => true,
                        'description' => "The tour ID whis is responsed",
                        'type' => 'integer'
                    ],
                    'invite_response' => [
                        'required' => true,
			'type' => 'string',
			'description' => 'The response to the invite en|es',
			'enum' => array( 
                            'going',
                            'next_time'
                        ),
                    ],
                    )
            ),
                )
        );
        
        register_rest_route($namespace, '/tours/book', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'book'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    'tour_id' => [
                        'required' => true,
                        'description' => "The tour ID whis is responsed",
                        'type' => 'integer'
                    ],
                    )
            ),
                )
        );
        
        register_rest_route($namespace, '/tours/book_and_pay', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'bookWithPaymentOption'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    'tour_id' => [
                        'required' => true,
                        'description' => "The tour ID whis is responsed",
                        'type' => 'integer'
                    ],
                    )
            ),
                )
        );
        
        register_rest_route($namespace, '/tours/book/deposit_confirmation', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'deposit_confirmation'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    'tour_id' => [
                        'required' => true,
                        'description' => "The tour ID of related booking",
                        'type' => 'integer'
                    ],
                    'bank' => [
                        'required' => true,
                        'description' => "The bank of the deposit",
                        'type' => 'string'
                    ],
                    'receipt' => [
                        'required' => true,
                        'description' => "The receipt number",
                        'type' => 'string'
                    ],
                    )
            ),
                )
        );
        
        register_rest_route($namespace, '/tours/book/transactions', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'tour_booking_transactions'),
                'permission_callback' => array( $this, 'check_token_business' ),
                'args' => array(
                    'tour_id' => [
                        'required' => true,
                        'description' => "The tour ID of related booking",
                        'type' => 'integer'
                    ],
                    'booking_id' => [
                        'required' => true,
                        'description' => "The tour booking ID",
                        'type' => 'integer'
                    ],
                    )
            ),
                )
        );
        
        register_rest_route($namespace, '/tours/book/confirm', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'deposit_confirmation_by_owner'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    'tour_id' => [
                        'required' => true,
                        'description' => "The tour ID of related booking",
                        'type' => 'integer'
                    ],
                    'booking_id' => [
                        'required' => true,
                        'description' => "The booking ID",
                        'type' => 'integer'
                    ]
                    )
            ),
                )
        );
        
        register_rest_route($namespace, '/tours/book/reject', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'deposit_rejection_by_owner'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    'tour_id' => [
                        'required' => true,
                        'description' => "The tour ID of related booking",
                        'type' => 'integer'
                    ],
                    'booking_id' => [
                        'required' => true,
                        'description' => "The booking ID",
                        'type' => 'integer'
                    ]
                    )
            ),
                )
        );
        
        
        
        
        register_rest_route($namespace, '/tours/book/qr_checkin', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'qr_checkin'),
                'permission_callback' => array( $this, 'check_token_business' ),
                'args' => array(
                    'tour_id' => [
                        'required' => true,
                        'description' => "The tour ID of related booking",
                        'type' => 'integer'
                    ],
                    'booking_id' => [
                        'required' => true,
                        'description' => "The tour booking ID",
                        'type' => 'integer'
                    ],
                    'expeditioner_id' => [
                        'required' => true,
                        'description' => "The expeditioner to make check in",
                        'type' => 'integer'
                    ],
                    )
            ),
                )
        );
        
        register_rest_route($namespace, '/tours/booking/(?P<id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_booking'),
                'permission_callback' => array( $this, 'check_token' ),
            ),
                )
        );
        
        register_rest_route($namespace, '/tours/update/what_to_bring', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'update_what_to_bring'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    'user_id' => [
                        'required' => true,
                        'description' => "The user ID of related booking",
                        'type' => 'integer'
                    ],
                    'tour_id' => [
                        'required' => true,
                        'description' => "The tour ID of related booking",
                        'type' => 'integer'
                    ],
                    'acf_id' => [
                        'required' => true,
                        'description' => "The ACF id to change state",
                        'type' => 'string'
                    ],
                    'status' => [
                        'required' => true,
                        'description' => "The new status",
                        'type' => 'integer'
                    ],
                    )
            ),
                )
        );
        
        register_rest_route($namespace, '/tours/new', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_tour'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                        'type' => [
                            'required' => true,
                            'description' => "The tour's type",
                            'type' => 'string',
                            'enum' => array( 
                                'public',
                                'private'
                            ),
                        ],
                        'title' => [
                            'required' => true,
                            'description' => "The tour's main color",
                            'type' => 'string',
                        ],
                        'main_color' => [
                            'required' => true,
                            'description' => "The tour's main color",
                            'type' => 'string',
                        ],
                        'desc' => [
                            'required' => true,
                            'description' => "The tour's title",
                            'type' => 'string'
                        ],
                        'max_capacity' => [
                            'required' => false,
                            'description' => "The tour's seats number",
                            'type' => 'integer'
                        ],
                        'itinerary' => [
                            'required' => true,
                            'description' => "The tour's itinerary",
                        ],
                        'expeditioners_going' => [
                            'required' => true,
                            'description' => "The tour's expeditioners going",
                        ],
                        'what_to_bring' => [
                            'required' => true,
                            'description' => "The tour's what to bring",
                        ],
                    
                    )
            ),
                )
        );
        
        register_rest_route($namespace, '/tours/tour/append_photo/', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'append_photo_to_tour'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    'tour_id' => [
                        'required' => true,
                        'description' => "The tour ID whis is responsed",
                        'type' => 'integer'
                    ],
                    )
            ),
                )
        );
        
        register_rest_route($namespace, '/tours/(?P<tour_id>[\d]+)/start_chat/', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'start_chat_of_public_tour'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    
                    )
            ),
                )
        );
        
    }
    
    /**
     * Prints the Opt-in form
     * 
     * @param type $request
     * @return \WP_REST_Response
     */
    public function print_optin_form($request) {
        global $post;
        $id = $request->get_param('id');
        $post = get_post($id);
        header('Content-Type: text/html; charset=utf-8');
        get_template_part('parts/optin');
        exit();
    }
    
    /**
     * Returns the tour info
     * 
     * @param type $request
     * @return \WP_REST_Response
     */
    public function get_optin_tour($request) {
        $id = $request->get_param('id');
        $p = get_post($id);
        if ( !$p || $p->post_type != 'tour' ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid tour', 'status'=>609 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        $tour = (object) self::get_tour_info($id);
        unset($tour->tags);
        // unset($tour->gallery);
        unset($tour->qr_code);
        unset($tour->departure_date_timestamp);
        unset($tour->return_date_timestamp);
        unset($tour->departure_place);
        unset($tour->departure_place_name);
        unset($tour->return_place);
        unset($tour->return_place_name);
        unset($tour->hide_seats_information);
        unset($tour->itinerary);
        unset($tour->what_to_bring);
        // unset($tour->max_capacity);
        unset($tour->going_status);
        // unset($tour->booking_seats);
        // unset($tour->valid_bookings);
        unset($tour->departure_date);
        unset($tour->return_date);
        // unset($tour->category);
        unset($tour->expeditioners);
        // $tour->non_profit;
        
        return new WP_REST_Response(array('success'=> true, 'tour'=> $tour ), 200);
    }
    
    
    /**
     * Make a booking for a public tour and pay/choose the payment gateway
     * 
     * @param type $request
     * @return \WP_REST_Response
     */
    public function bookWebWithPaymentOption($request) {
        
        
        $booking_data = $this->create_tour_booking_expeditioners_info_from_web($request, true);
       
       
        if ( !$booking_data || !isset($booking_data['booking']) || !isset($booking_data['booking']->user_id) ){
            return new WP_REST_Response($booking_data, 200 );
        }
        
        // I don't like explode because i could forget some var or include some not passed
        $booking = $booking_data['booking'];
        

        $tour_expeditioner_record = $booking_data['tour_expeditioner_record'];
        $payment_type = (string)$request->get_param('payment_type');
        $tour_id = (string)$request->get_param('tour_id');
        $tour = get_post($tour_id);
        $user_id            = (int)$this->user['ID'];
        if ( !$tour ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid tour', 'status'=>609 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        // is a privated tour
        if ( get_field('type', $tour_id) != 'public' || !get_field('category', $tour_id, false) ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid type of tour', 'status'=>609 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        // if payment is required
        if ( !get_field('hide_payment', $tour_id, false) ){
            // payment required
            if ( $payment_type == 'deposit' ){
                // deposit
                
                $receipt = (int)$request->get_param('receipt');
                $bank = $request->get_param('bank');

                // handle uploaded file
                if( empty( $_FILES ) || !isset($_FILES[ 'file']) ){
                    $response = ['code'=>'missing_photo', 'message'=> "Missing attachment", 'data'=> ['status'=>600] ];
                    return new WP_REST_Response($response, 200 );
                }

                $file_id = Expedition_Helper::save_image_doc_from_url( false, 'file_'.time().'.jpg', $_FILES[ 'file'], 0, $this->user['ID'] );

                if ( is_wp_error($file_id) ){
                    $response = ['code'=>'file_error', 'message'=> "Error saving the file", 'data'=> ['status'=>601, 'file'=>$file_id] ];
                    return new WP_REST_Response($response, 200 );
                }

                $total = Expedition_Helper::getTourBookingTotalAmount($booking);

                $meta = new stdClass();
                $meta->receipt = $receipt;
                $meta->bank = $bank;
                $meta->attachment_id = $file_id;
                update_field('private', 1, $file_id);
                $img = wp_get_attachment_image_src($file_id, 'large');
                $meta->attachment_url = $img[0];
                $owner_id = (int)get_field('owner', $tour_id);

                $args = array(
                    'booking_id' => $booking->id,
                    'user_id' => $user_id,
                    'owner_id' => $owner_id,
                    'amount' => $total,
                    'currency' => 'GTQ',
                    'gateway' => GATEWAY_DEPOSIT,
                    'meta' => json_encode($meta),

                    'ws_sent' => '',
                    'ws_response' => '',
                    'booking' => '',
                    'success' => TRANSACTION_PENDING
                );

                Expedition_Helper::insertTransactionRecord($args);
                Expedition_Helper::modifyUserBooking(array('status'=> BOOKING_PENDING_CONFIRM, 'id'=> $booking->id ));

                 $firstName = $this->user['first_name'];
                 $lastName = $this->user['last_name'];
                
                $first_name = (int)$request->get_param('first_name');
                $last_name = (int)$request->get_param('last_name');
                $full_name = $first_name . ' ' . $last_name;
                $owner_user = get_userdata($owner_id);

                $one_signal_id = get_field( 'one_signal_id', 'user_'.$owner_id);
                if ( $one_signal_id ){
                    Notifications::sendPushToUser($one_signal_id, "$fullname has uploaded the deposit confirmation, please check your email.", "Deposit confirmation");
                }

                $attach_path = array();
                if ( $file_id ){
                    $attach_path[] = get_attached_file( $file_id );
                }

                $content = '<h2>Nuevo Depósito</h2>'
                        . "<p>$firstName $lastName has uploaded the deposit confirmation, the details below: </p>"
                        . "<p>Bank : $bank<br/>"
                        . "Receipt number : $receipt<br/>"
                        . "Amount : GTQ".number_format($total, 2)."</p>"
                        . "<p>Also check the attached file.</p>";

                $sent = Notifications::send($owner_user->user_email, '¡Nuevo registro!', $content, $attach_path);
                Expedition_Helper::logMessage( "Deposit confirmation email ".($sent ? 'sent' : 'failed')." to $owner_user->user_email", 'emails_sent.txt');

                return new WP_REST_Response(
                        array(
                            'success'=>true, 
                            // 'ids' => $gallery_ids, 
                            // 'gallery'=> $gallery, 
                            'message'=> 'Confirm added succesfully',
                            'file_id' =>$file_id,
                            'tour'=> self::get_tour_info($tour_id)
                            ), 
                        200 );

            }
            else if ($payment_type == 'epay'){
                $pay_response = $this->pay_with_credit_card_epay($request, $booking->id , false);
            } else { 
                $pay_response = $this->pay_with_credit_card_cybersource($request, false);
            }
            
        }else{
            // Expedition_Helper::modifyUserBooking(array('status'=> BOOKING_PENDING_CONFIRM, 'id'=> $booking->id ));
            $pay_response['success'] = true;
            
             $total = Expedition_Helper::getTourBookingTotalAmount($booking);

                $meta = new stdClass();
                $meta->receipt = $receipt;
                $meta->bank = $bank;
                $meta->attachment_id = $file_id;
                update_field('private', 1, $file_id);
                $img = wp_get_attachment_image_src($file_id, 'large');
                $meta->attachment_url = $img[0];
                $owner_id = (int)get_field('owner', $tour_id);

                $args = array(
                    'booking_id' => $booking->id,
                    'user_id' => $user_id,
                    'owner_id' => $owner_id,
                    'amount' => $total,
                    'currency' => 'GTQ',
                    'gateway' => GATEWAY_DEPOSIT,
                    'meta' => json_encode($meta),

                    'ws_sent' => '',
                    'ws_response' => '',
                    'booking' => '',
                    'success' => TRANSACTION_PENDING
                );

                Expedition_Helper::insertTransactionRecord($args);
                Expedition_Helper::modifyUserBooking(array('status'=> BOOKING_PENDING_CONFIRM, 'id'=> $booking->id ));
                
                $firstName = $this->user['first_name'];
                $lastName = $this->user['last_name']; 
                
                $first_name = (int)$request->get_param('first_name');
                $last_name = (int)$request->get_param('last_name');
                $full_name = $first_name . ' ' . $last_name;
                $owner_user = get_userdata($owner_id);

                $one_signal_id = get_field( 'one_signal_id', 'user_'.$owner_id);
                if ( $one_signal_id ){
                    Notifications::sendPushToUser($one_signal_id, "$fullname has uploaded the deposit confirmation, please check your email.", "Deposit confirmation");
                }

                // $attach_path = array();
                // if ( $file_id ){
                //     $attach_path[] = get_attached_file( $file_id );
                // }

                $content = '<h2>Nuevo depósito enviado</h2>'
                        . "<p>$firstName $lastName has uploaded the deposit confirmation, the details below: </p>"
                        . "<p>Bank : <br/>"
                        . "Receipt number : <br/>"
                        . "Amount : GTQ".number_format($total, 2)."</p>"
                        . "<p>Also check the attached file.</p>";

                $sent = Notifications::send($owner_user->user_email, '¡Nuevo registro!', $content);
                Expedition_Helper::logMessage( "Deposit confirmation email ".($sent ? 'sent' : 'failed')." to $owner_user->user_email", 'emails_sent.txt');

                return new WP_REST_Response(
                        array(
                            'success'=>true, 
                            // 'ids' => $gallery_ids, 
                            // 'gallery'=> $gallery, 
                            'message'=> 'Confirm added succesfully',
                            // 'file_id' =>$file_id,
                            'tour'=> self::get_tour_info($tour_id)
                            ), 
                        200 );

            }
            
            
        // if ( !isset($pay_response['success']) || !$pay_response['success'] ){
        //     Expedition_Helper::deleteUserBookingRecord( $booking->id );
        //     Expedition_Helper::deleteTourExpeditionerRecord( $tour_expeditioner_record->id );
        //     return new WP_REST_Response($pay_response, 200 );
        // }
        
        if(isset($pay_response['success']) && $pay_response['success'] ){
            if( isset($pay_response['code']) && $pay_response['code'] == 'epay_success' ){
                Expedition_Helper::modifyUserBooking( array( 'status' => BOOKING_CONFIRMED_CARD, 'id'=>$booking->id ) );    
            }
            Expedition_Helper::insertActivityRecord($user_id, 'book_public_tour', $tour_id);
            return new WP_REST_Response($pay_response, 200 );
        }
        
        Expedition_Helper::deleteUserBookingRecord( $booking->id );
        Expedition_Helper::deleteTourExpeditionerRecord( $tour_expeditioner_record->id );
        return new WP_REST_Response($pay_response, 500 );
        
        // return new WP_REST_Response(array('success'=> true, 'booking'=> $booking, 'tour'=> self::get_tour_info($tour_id) ), 200);
    }
    
    /**
     * Returns single tour personal info from expeditioners
     * 
     * @param type $request
     * @return \WP_REST_Response
     */
    public function create_tour_booking_expeditioners_info_from_web($request, $return_raw = false) {
        
        
        $tour_id = $request->get_param('tour_id');
        $p = get_post($tour_id);
                
        if ( !$p || $p->post_type != 'tour' ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid tour', 'status'=>609 ];
            if ( $return_raw )
                return $response;
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        // nuevos fields
        $first_name = $request->get_param('first_name');
        $last_name = $request->get_param('last_name');
        $email = $request->get_param('email');
        $sex = $request->get_param('sex');
        $nationality = $request->get_param('nationality');
        $dpi_passport = $request->get_param('dpi_passport');
        $phone = $request->get_param('phone');
        $occupation = $request->get_param('occupation');
        
        $receipt_name = $request->get_param('nit_name');
        $receipt_num = $request->get_param('nit');
        $receipt_address = $request->get_param('receipt_address');
        
        // Extras
        $ex_field_01 = $request->get_param('ex_field_01');
        $ex_field_02 = $request->get_param('ex_field_02');
        $ex_field_03 = $request->get_param('ex_field_03');
        $ex_field_04 = $request->get_param('ex_field_04');
        $ex_field_05 = $request->get_param('ex_field_05');
        $ex_field_06 = $request->get_param('ex_field_06');
        $ex_field_07 = $request->get_param('ex_field_07');
        $ex_field_08 = $request->get_param('ex_field_08');
        $ex_field_09 = $request->get_param('ex_field_09');
        $ex_field_10 = $request->get_param('ex_field_10');
        
        $utm_medium = $request->get_param('utm_medium');
        $utm_source = $request->get_param('utm_source');
        
        // Discount code & amount
        $discount_code = $request->get_param('discount_code');
        $total_discounted = $request->get_param('total_discounted');
        $total_amount = $request->get_param('total_amount');

        
        // $ = $request->get_param('');
        
        $expeditioner_id = 0;
        $age = $request->get_param('age');
        
        
        // $collegiate = $request->get_param('collegiate');
        $emergency_contact = $request->get_param('emergency_contact');
        $emergency_contact_number = $request->get_param('emergency_contact_number');
        
        
        
        
        $valid_sources = array('whatsapp', 'facebook', 'instagram', 'web');
        if ( !in_array($utm_medium, $valid_sources) ){
            $utm_medium = 'web';
        }
        $source = $utm_medium;
        
        $user = get_user_by('email', $email);
        if ( $user && $user->ID ){
            $user_id = $user->ID;
            $this->user = Expedition_Helper::getUser($user_id, true);
        }else{
            $dob_raw = '';//(string)$request->get_param('dob');
            $dob = @str_replace('-', '', $dob_raw);
            $gender = '';//(string)$request->get_param('gender');
            // $email = $request->get_param('email');
            $password = base_convert(rand(50000, 100000), 10, 36);
            $user_language = 'es';//$request->get_param('user_language');
            $userdata = [
                            'user_login' => $email,
                            'display_name' => "$first_name $last_name",
                            'first_name' => $first_name,
                            'last_name' => $last_name,
                            'user_image' => '',
                            'role' => 'expeditioner',
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

            $user = Expedition_Helper::getUser($user_id, true);
            update_user_meta($user_id, 'status', USER_ACTIVE);
            update_user_meta($user_id, 'user_language', $user_language);
            update_user_meta($user_id, 'activation_key', uniqid() );
            
            on_expedition_created_user( $user_id, false, $password );
            $this->user = $user;
        }
        
        $categories = get_field('category', $tour_id);
        $total = 0;
        $meta = array();
        $seats = 0;
        if ( count($categories) > 0 ){
            foreach ($categories as $key => $category) {
                if ( count($category['prices']) > 0 ){
                    foreach ($category['prices'] as $key => $price) {
                        if ( $request->get_param( $price['id'] ) ){
                            $qty = (int)$request->get_param( $price['id'] );
                            if ( $qty > 0 ){
                                $seats += $qty;
                                $price['qty'] = $qty;
                                $price['total'] = $qty*$price['price'];
                                $price['category'] = $category['id'];
                                $meta[] = $price;
                                $total += $price['price'] * $qty;
                            }
                        }
                    }
                }
            }
        }
        
        $max_capacity = (int) get_field('max_capacity', $tour_id);
        $valid_bookings = Expedition_Helper::getNumberOfValidUserBookingsByTour( $tour_id);
        
        if ( $valid_bookings >= $max_capacity ){
            $response = ['code'=>'tour_full', 'message'=> "The program has no available space.", 'status'=>811 ];
            if ( $return_raw )
                return $response;
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        if ( $valid_bookings + $seats > $max_capacity ){
            $seats_available = $max_capacity - $valid_bookings;
            $response = ['code'=>'tour_full', 'message'=> "We have only $seats_available seats available, please try once again", 'status'=>811 ];
            if ( $return_raw )
                return $response;
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        // if no items in the book
        if ( $total == 0 && $seats < 1){
            $response = ['code'=>'empty_book', 'message'=> "Your booking is empty", 'status'=>612 ];
            if ( $return_raw )
                return $response;
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        
        $args = array(
            'user_id' => $user_id,
            'owner_id' => (int)get_field('owner', $tour_id),
            'tour_id' => $tour_id,
            'tour_meta' => json_encode($meta),
            'seats' => $seats,
            'source' => $source,
            'amount' => $total,
            'total_discount' => $total_discounted,
            'discount_code' => $discount_code,
            'total_charged' => $total_amount,
            'status' => BOOKING_PENDING
        );
        $booking = Expedition_Helper::insertUserBooking( $args );
        $booking_id = $booking->id;
        
        $data_to_store = compact(
            'booking_id', 'tour_id', 'user_id', 'expeditioner_id',
            'first_name', 'last_name', 'email', 'age',
            'sex','nationality','dpi_passport','phone','occupation',
            'receipt_name' , 'receipt_num', 'receipt_address',
            'source',
            'ex_field_01','ex_field_02','ex_field_03','ex_field_04',
            'ex_field_05','ex_field_06','ex_field_07','ex_field_08',
            'ex_field_09','ex_field_10'
            
            
        );
        $tour_expeditioner_record = Expedition_Helper::insertTourExpeditionerRecord($data_to_store);
        // $data = (array)Expedition_Helper::getTourExpeditioners($tour_id, $user_id);
        if ( $return_raw ){
            return compact('booking', 'tour_expeditioner_record');
        }
        
        return new WP_REST_Response(array('success'=> true, 'data'=> array() ), 200);
    }

    /**
     * Returns list of tours
     * 
     * @param type $request
     * @return \WP_REST_Response
     */
    public function get_tours($request) {
        global $wpdb;
        $tours = array();
        
        $tour_ids = $request->get_param('tour_ids');
        $type = $request->get_param('type');
        
        $tag = $request->get_param('tag');
        $non_profit = $request->get_param('non_profit');
        $my_trips = $request->get_param('my_trips');
        // $tour_code = $request->get_param('tour_code');
        $search = $request->get_param('search');
        $search_by = $request->get_param('search_by');
        
        
        $basic_args = array(
            'post_type'=>'tour', 
            'meta_key'          => 'departure_date',
            'orderby'           => 'meta_value_num',
            'order'             => 'DESC',
            'posts_per_page'    =>  1000,
        );
        
        $args = array(
                    'meta_query' => array(
                        'relation' => 'OR', // Optional, defaults to "AND"
                        array(
                            'relation' => 'AND',
                            array(
                                'key'     => 'type',
                                'value'   => 'public',
                                'compare' => '='
                            ),
//                            array(
//                                'key'     => 'departure_date',
//                                'value'   =>  date("Y-m-d H:i:s"),
//                                'compare' => '>=', // Return the ones greater than today's date
//                                'type' => 'DATE'
//                            )
                        ),
                        array(
                            'relation' => 'AND',
                            array(
                                'key'     => 'type',
                                'value'   => 'private',
                                'compare' => '='
                            ),
                            array(
                                'key'     => 'expeditioners',
                                'value'   => 'i:'.$this->user['ID'].';',
                                'compare' => 'LIKE'
                            )
                        )
                    )
                );
        
        
        if ( $search_by ){
            if ($search_by == 'tour_code'){
                $args = array(
                    'meta_query' => array(
                        'relation' => 'AND', // Optional, defaults to "AND"
                        array(
                            'key'     => 'type',
                            'value'   => 'public',
                            'compare' => '='
                        ),
                        array(
                            'key'     => 'tour_code',
                            'value' => strtoupper($search),
                            'compare' => 'LIKE'
                        ),
                    )
                );
                
            }else if ($search_by == 'title'){
                // var_dump($args['meta_query']);
                // exit();
                $basic_args['s'] = $search;
            }
        }
        
        if ( $type == 'public' ){
            $args = array(
                    'meta_query' => array(
                        'relation' => 'AND', // Optional, defaults to "AND"
                        array(
                            'key'     => 'type',
                            'value'   => 'public',
                            'compare' => '='
                        ),
                        array(
                            'key'     => 'return_date',
                            'value' => date("Y-m-d"),
                            'type'=> 'DATETIME',
                            'compare' => '<'
                        ),
                    )
                );
        }
        
        if ( $tag ){
            $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'post_tag',
                            'terms' => $tag,
                            'field' => 'term_id',
                            'include_children' => true,
                            'operator' => 'IN'
                        )
                    );
        }
        
        if ( $non_profit ){
            $user_query = new WP_User_Query( 
                array(
                    'fields' => array('ID'),
                    'role'          => 'business',
                    'meta_query'    => array(
                        'relation'  => 'AND',
                        array( 
                            'key'     => 'non_profit_organization',
                            'value'   => 1,
                        ),
                    )
                ) 
            );
            
            $non_profit_users = $user_query->get_results();
            $non_profit_users_array = array();
            if ( ! empty( $non_profit_users ) ) {
                foreach ( $non_profit_users as $non_profit_user ) {
                    $non_profit_users_array[] = (int)$non_profit_user->ID;
                }
            }
            $args['author__in'] = $non_profit_users_array;
        }
        
        if ( $my_trips ){
            
            //$query = new WP_Query( $args );
            
            
            
            //"SELECT SQL_CALC_FOUND_ROWS ea_posts.ID FROM ea_posts  INNER JOIN ea_postmeta ON ( ea_posts.ID = ea_postmeta.post_id )  INNER JOIN ea_postmeta AS mt1 ON ( ea_posts.ID = mt1.post_id )  INNER JOIN ea_postmeta AS mt2 ON ( ea_posts.ID = mt2.post_id ) WHERE 1=1  AND ( \n  ( \n    ( ea_postmeta.meta_key = 'type' AND ea_postmeta.meta_value = 'public' )\n  ) \n  OR \n  ( \n    ( mt1.meta_key = 'type' AND mt1.meta_value = 'private' ) \n    AND \n    ( mt2.meta_key = 'expeditioners' AND mt2.meta_value LIKE '{eff81e31120a874f22856178474614a5d6a5bf2eeeadbd2c8b7b333db6f62019}i:8;{eff81e31120a874f22856178474614a5d6a5bf2eeeadbd2c8b7b333db6f62019}' )\n  )\n) AND ea_posts.post_type = 'post' AND (ea_posts.post_status = 'publish' OR ea_posts.post_status = 'acf-disabled') GROUP BY ea_posts.ID ORDER BY ea_posts.post_date DESC LIMIT 0, 10";
            
//            $table_posts = $wpdb->prefix . 'posts';
//            $table_meta = $wpdb->prefix . 'postmeta';
//            $q = "SELECT SQL_CALC_FOUND_ROWS $table_posts.ID FROM $table_posts INNER JOIN $table_meta ON ( $table_posts.ID = $table_meta.post_id )  INNER JOIN $table_meta AS mt1 ON ( $table_posts.ID = mt1.post_id )  INNER JOIN $table_meta AS mt2 ON ( $table_posts.ID = mt2.post_id ) WHERE 1=1  AND ( \n  ( \n    ( $table_meta.meta_key = 'type' AND $table_meta.meta_value = 'public' )\n  ) \n  OR \n  ( \n    ( mt1.meta_key = 'type' AND mt1.meta_value = 'private' ) \n    AND \n    ( mt2.meta_key = 'expeditioners' AND mt2.meta_value LIKE 'i:8;' )\n  )\n) AND $table_posts.post_type = 'post' AND ($table_posts.post_status = 'publish' OR $table_posts.post_status = 'acf-disabled') GROUP BY $table_posts.ID ORDER BY $table_posts.post_date DESC LIMIT 0, 10";
//        echo $table_posts . $table_meta;
//            $res = $wpdb->get_results($wpdb->prepare($q ));
//        
//            
//            return $res;
//            
//            $non_profit_users = $user_query->get_results();
//            $non_profit_users_array = array();
//            if ( ! empty( $non_profit_users ) ) {
//                foreach ( $non_profit_users as $non_profit_user ) {
//                    $non_profit_users_array[] = (int)$non_profit_user->ID;
//                }
//            }
//            $args['author__in'] = $non_profit_users_array;
        }
        
        
        $args = array_merge($basic_args, $args);
        
        
        if ( $tour_ids ){
            $tour_array = explode('|', $tour_ids);
            if ( $tour_array ){
                $args['post__in'] = $tour_array;
            }
        }
        
        $query = new WP_Query( $args );
        
//        return $query->query;
            
        if ( $query->found_posts ){
            foreach ($query->posts as $p) {
                $tours[] = self::get_tour_info($p->ID);
            }
        }
        
        return new WP_REST_Response(array('success'=> true, 'tours'=> $tours), 200);
    }
    
    /**
     * Returns list of past tours 
     * 
     * @param type $request
     * @return \WP_REST_Response
     */
    public function get_past_tours($request) {
        $tours = array();
        $user_id = $this->user['ID'];
        $bookings = Expedition_Helper::getUserBookingByUser($user_id);
        $tour_ids = array();
        if ( $bookings && is_array($bookings) && count($bookings) > 0 ){
            foreach ($bookings as $booking) {
                $tour_ids[] = $booking->tour_id;
            }
        }
        
        if ( count($tour_ids) > 0 ){
            $args['post__in'] = $tour_ids;
            $args['post_type'] = 'tour';
        }
        
        $query = new WP_Query( $args );
        
        if ( $query->found_posts ){
            foreach ($query->posts as $p) {
                $tours[] = self::get_tour_info($p->ID);
            }
        }
        
        return new WP_REST_Response(array('success'=> true, 'tours'=> $tours), 200);
    }
    
    /**
     * Returns single tour
     * 
     * @param type $request
     * @return \WP_REST_Response
     */
    public function get_tour($request) {
        
        $id = $request->get_param('id');
        $p = get_post($id);
        if ( !$p || $p->post_type != 'tour' ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid tour', 'status'=>609 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        return new WP_REST_Response(array('success'=> true, 'tour'=> self::get_tour_info($id) ), 200);
    }
    
    /**
     * Returns single tour personal info from expeditioners
     * 
     * @param type $request
     * @return \WP_REST_Response
     */
    public function get_tour_booking_expeditioners_info($request) {
        
        $tour_id = $request->get_param('id');
        $booking_id = $request->get_param('booking_id');
        $user_id = (int)$this->user['ID'];
        $p = get_post($tour_id);
        if ($booking_id){
            $booking = Expedition_Helper::getUserBooking($booking_id);
        }else{
            $booking = Expedition_Helper::getUserBookingByUserAndTour($tour_id, $user_id);
        }
        
        if ( !$p || $p->post_type != 'tour' ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid tour', 'status'=>609 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }else if ( !$booking ){
            $response = ['code'=>'invalid_booking', 'message'=> 'Invalid booking', 'status'=>608 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }else if ( $booking && $booking->user_id != $user_id && $booking->owner_id != $user_id ){
            $response = ['code'=>'invalid_booking', 'message'=> 'Invalid booking', 'status'=>607 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        $data = (array)Expedition_Helper::getTourExpeditioners($tour_id, $booking_id ? $booking->user_id : $user_id);
        
        
        return new WP_REST_Response(array('success'=> true, 'data'=> $data ), 200);
    }
    
    /**
     * Returns single tour personal info from expeditioners
     * 
     * @param type $request
     * @return \WP_REST_Response
     */
    public function create_tour_booking_expeditioners_info($request) {
        
        $tour_id = $request->get_param('id');
        
        $first_name = $request->get_param('first_name');
        $last_name = $request->get_param('last_name');
        $expeditioner_id = 0;
        $age = $request->get_param('age');
        $dpi_passport = $request->get_param('dpi_passport');
        $phone = $request->get_param('phone');
        $collegiate = $request->get_param('collegiate');
        $emergency_contact = $request->get_param('emergency_contact');
        $emergency_contact_number = $request->get_param('emergency_contact_number');
        
        $user_id = (int)$this->user['ID'];
        $p = get_post($tour_id);
        $booking = Expedition_Helper::getUserBookingByUserAndTour($tour_id, $user_id);
        
        if ( !$p || $p->post_type != 'tour' ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid tour', 'status'=>609 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }else if ( !$booking ){
            $response = ['code'=>'invalid_booking', 'message'=> 'Invalid booking', 'status'=>608 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        $booking_id = $booking->id;
        $source = 'app';
        $current_user = get_userdata($user_id);
        $email = $current_user->user_email;
        
        $data_to_store = compact('booking_id', 'tour_id', 'user_id', 'expeditioner_id', 'first_name', 'last_name', 'age', 'dpi_passport', 'phone', 'email', 'source',
                'collegiate', 'emergency_contact', 'emergency_contact_number');
        $result = Expedition_Helper::insertTourExpeditionerRecord($data_to_store);
        $data = (array)Expedition_Helper::getTourExpeditioners($tour_id, $user_id);
        
        return new WP_REST_Response(array('success'=> true, 'data'=> $data, 'insert'=> json_decode($result)  ), 200);
    }
    
    /**
     * Behavior for response of user to a tour invite
     * 
     * @param type $request
     * @return \WP_REST_Response
     */
    public function private_response($request) {
        
        $tour_id = (int)$request->get_param('tour_id');
        $invite_response = $request->get_param('invite_response');
        $user_id = (int)$this->user['ID'];
        $tour = get_post($tour_id);
        // does it exist?
        if ( !$tour ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid tour', 'status'=>609 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        // is a privated tour
        if ( get_field('type', $tour_id) != 'private' ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid type of tour', 'status'=>609 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        // use is invited?
        $expeditioners = get_field('expeditioners', $tour_id);
        if ( !in_array( $user_id, $expeditioners) ){
            $response = ['code'=>'invalid_tour', 'message'=> "User isn't invited", 'status'=>609 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        $invite = Expedition_Helper::getTourInvitationByTourAndInvited( $tour_id, $user_id );
        // if for some reason exist the invite in the ACF fields but not in the relationships table, 
        // then will be needed create the invite relationship between user and tour
        if ( !$invite ){ 
            $args = array(
                'user_id' => $user_id,
                'owner_id' => (int)get_field('owner', $tour_id),
                'tour_id' => $tour_id,
                'status' => TOUR_PENDING,
            );
            $invite = Expedition_Helper::insertUserTourInvitation( $args );
        }
        
        //going
        //next_time
        $mapped_values = array('going'=>1, 'next_time'=> 2);
        $status = $mapped_values[$invite_response];
        $modified_invite = Expedition_Helper::modifyTourInvitation( array( 'id'=>$invite->id, 'status'=> $status) );
        Expedition_Helper::insertActivityRecord($modified_invite->owner_id, "response_tour_".$modified_invite->status, $modified_invite->id);
        
        do_action('expedition_user_confirmed_invite_to_tour', $invite->id, $modified_invite );
        
        return new WP_REST_Response(array('success'=> true, 'status'=>$status, 'invite'=> $modified_invite, 'tour'=> self::get_tour_info($tour_id) ), 200);
    }
    
    /**
     * Make a booking for a public tour
     * 
     * @param type $request
     * @return \WP_REST_Response
     */
    public function book($request, $return_raw = false) {
        global $wpdb;
        
        $tour_id = (int)$request->get_param('tour_id');
        $nit = (string)$request->get_param('nit');
        $nit_name = (string)$request->get_param('nit_name');
        $nit_address = (string)$request->get_param('nit_address');
        $user_id = (int)$this->user['ID'];
        $user_status = get_field('status', "user_{$user_id}");
        
        if ( $user_status != 1 ){
            $response = ['code'=>'invalid_user', 'message'=> 'your_account_needs_to_be_verified_before_purchase', 'status'=>637 ];
            if ( $return_raw )
                return $response;
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        
        /*********** MAX TRANSACTIONS VALIDATION ************/
        $transactions_sql = "SELECT * FROM ".TRANSACTIONS_TABLE." WHERE DATE(created_at) = CURDATE() AND success = 1 AND user_id = ".$user_id.";";
        $today_transactions = $wpdb->get_results($transactions_sql);
        if (is_array($today_transactions) && count($today_transactions) > 2  ){
            $response = ['code'=>'max_today_bookings', 'message'=> 'You can only perform 3 daily transactions, please try tomorrow', 'status'=>639 ];
            if ( $return_raw )
                return $response;
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        /*********** MAX TRANSACTIONS VALIDATION ************/
        
        $tour = get_post($tour_id);
        $source = 'app';
        // does it exist?
        if ( !$tour ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid tour', 'status'=>609 ];
            if ( $return_raw )
                return $response;
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        // is a privated tour
        if ( get_field('type', $tour_id) != 'public' || !get_field('category', $tour_id, false) ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid type of tour', 'status'=>609 ];
            if ( $return_raw )
                return $response;
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        $book = Expedition_Helper::getUserBookingByUserAndTour( $tour_id, $user_id );
        
        // is previously booked same tour
        if ( $book ){
            $response = ['code'=>'tour_previously_reserved', 'message'=> "You have already booked this tour", 'status'=>611 ];
            if ( $return_raw )
                return $response;
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        $max_capacity = (int) get_field('max_capacity', $tour_id);
        $valid_bookings = Expedition_Helper::getNumberOfValidUserBookingsByTour( $tour_id);
        
        if ( $valid_bookings >= $max_capacity ){
            $response = ['code'=>'tour_full', 'message'=> "The tour has no available seats.", 'status'=>811 ];
            if ( $return_raw )
                return $response;
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        $categories = get_field('category', $tour_id);
        $total = 0;
        $meta = array();
        $seats = 0;
        if ( count($categories) > 0 ){
            foreach ($categories as $key => $category) {
                if ( count($category['prices']) > 0 ){
                    foreach ($category['prices'] as $key => $price) {
                        if ( $request->get_param( $price['id'] ) ){
                            $qty = (int)$request->get_param( $price['id'] );
                            if ( $qty > 0 ){
                                $seats += $qty;
                                $price['qty'] = $qty;
                                $price['total'] = $qty*$price['price'];
                                $price['category'] = $category['id'];
                                $meta[] = $price;
                                $total += $price['price'] * $qty;
                            }
                        }
                    }
                }
            }
        }
        
        if ( $valid_bookings + $seats > $max_capacity ){
            $seats_available = $max_capacity - $valid_bookings;
            $response = ['code'=>'tour_full', 'message'=> "We have only $seats_available seats available, please try once again", 'status'=>811 ];
            if ( $return_raw )
                return $response;
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        // if no items in the book
        if ( $total == 0 && $seats < 1){
            $response = ['code'=>'empty_book', 'message'=> "Your booking is empty", 'status'=>612 ];
            if ( $return_raw )
                return $response;
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        // return array( 'success'=>true, 'total'=>$total, 'meta'=>$meta, 'params'=>$request->get_params() );
        
        $args = array(
            'user_id' => $user_id,
            'owner_id' => (int)get_field('owner', $tour_id),
            'tour_id' => $tour_id,
            'tour_meta' => json_encode($meta),
            'seats' => $seats,
            'source' => $source,
            'amount' => $total,
            'status' => BOOKING_PENDING
        );
        $booking = Expedition_Helper::insertUserBooking( $args );
        
        if ( $nit && $nit_name && $nit_address ){
            update_field( 'nit', $nit, 'user_'.$user_id);
            update_field( 'nit_name', $nit_name, 'user_'.$user_id);
            update_field( 'nit_address', $nit_address, 'user_'.$user_id);
        }
        
        if ( $return_raw ){
            return $booking;
        }
        
        Expedition_Helper::insertActivityRecord($booking->owner_id, 'book_public_tour', $tour_id);
        
        return new WP_REST_Response(array('success'=> true, 'booking'=> $booking, 'tour'=> self::get_tour_info($tour_id) ), 200);
    }
    
    /**
     * Make a booking for a public tour and pay/choose the payment gateway
     * 
     * @param type $request
     * @return \WP_REST_Response
     */
    public function bookWithPaymentOption($request) {
        
        $payment_type = (string)$request->get_param('payment_type');
        if ( $payment_type == 'deposit' ){
            return $this->book($request);
        }else{
            $booking = $this->book($request, true);
        }
        
        if ( !$booking || !isset($booking->user_id) ){
            return new WP_REST_Response($booking, 200 );
        }
        
        $pay_response = $this->pay_with_credit_card_cybersource($request);
        if ( !isset($pay_response['success']) || !$pay_response['success'] ){
            Expedition_Helper::deleteUserBookingRecord( $booking->id );
            return new WP_REST_Response($pay_response, 200 );
        }
        $tour_id = (int)$request->get_param('tour_id');
        $user_id = (int)$this->user['ID'];
        Expedition_Helper::insertActivityRecord($user_id, 'book_public_tour', $tour_id);
        
        return new WP_REST_Response(array('success'=> true, 'booking'=> $booking, 'tour'=> self::get_tour_info($tour_id) ), 200);
    }
    
    /**
     * Pay a tour using credit card
     * 
     * @param type $request
     * @return \WP_REST_Response
     */
    public function pay_with_credit_card($request) {
        
        $api_url            = PAGALO_URL;
        $token              = PAGALO_TOKEN;
        $public_key         = PAGALO_PUBLIC_KEY;
        $secret_key         = PAGALO_SECRET_KEY;
        $id_en_empresa      = PAGALO_ID_EN_EMPRESA;
        $user_id            = (int)$this->user['ID'];
        $current_user       = get_userdata($user_id);
        $tour_id            = $request->get_param('tour_id');
        //$tokenize           = $request->get_param('tokenize');
        $booking            = Expedition_Helper::getUserBookingByUserAndTour($tour_id, $user_id);
        $user_tokens        = Expedition_Helper::getActiveUserCardsByUser($user_id);
        $user_token         = false;
        if ( $user_tokens ){
            $user_token = $user_tokens[0];
        }
        
        $alias              = $request->get_param('alias');
        $credit_card_name   = $request->get_param('credit_card_name');
        $credit_card_number = str_replace(array(' ', '-'), array('',''), $request->get_param('credit_card_number'));
        $credit_card_expiry = $request->get_param('credit_card_expiry');
        $credit_card_cvv    = $request->get_param('credit_card_cvv');
        $credit_card_expiry_parts   = explode( '/', $credit_card_expiry );
        $credit_card_expiry_month   = $credit_card_expiry_parts[0];
        $credit_card_expiry_year    = $credit_card_expiry_parts[1];
        
        $credit_card_brand  = $request->get_param('credit_card_brand');
        $fingerprint        = $request->get_param('fingerprint');
        $gateway            = 'pagalocard';
        
        
        $p = get_post($tour_id);
        
        if ( !$p || $p->post_type != 'tour' ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid tour', 'status'=>609 ];
            return $response;
            //return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }else if ( !$booking ){
            $response = ['code'=>'invalid_booking', 'message'=> 'Invalid booking', 'status'=>608 ];
            return $response;
            //return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }else if ( $booking && $booking->user_id != $user_id && $booking->owner_id != $user_id ){
            $response = ['code'=>'invalid_booking', 'message'=> 'Invalid booking', 'status'=>607 ];
            return $response;
            //return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        $empresa = array(
            'key_secret'        => $secret_key,
            'key_public'        => $public_key,
            'idenEmpresa'       => $id_en_empresa
        );
        
        $cliente = array(
           'codigo'             => 'c001',
           'firstName'          => $current_user->first_name,
           'lastName'           => $current_user->last_name,
           'phone'              => '42122755',
           'street1'            => '17 avenida y 32 calle final zona 11 1-24',
           'country'            => 'Guatemala',
           'city'               => 'Guatemala',
           'state'              => 'Guatemala',
           'postalCode'         => '01011',
           'email'              => $current_user->user_email,
           'ipAddress'          => Expedition_Helper::getClientIp(),
           'Total'              => 2.00,
           'currency'           => 'GTQ',
           'fecha_transaccion'  => current_time('mysql', 1), //'2019-02-19 10:01:26',
           'deviceFingerprintID'=> $fingerprint
        );
        
        if ( !$user_token ){
            
            $tarjetaPagalo = array(
                'nameCard'           => $credit_card_name,
                'accountNumber'      => $credit_card_number,
                'expirationMonth'    => $credit_card_expiry_month,
                'expirationYear'     => "20$credit_card_expiry_year",
                'CVVCard'            => $credit_card_cvv
            );
            $detalle = array(
                'id_producto'        => 'validate_card',
                'cantidad'           => 1,
                'tipo'               => 'Servicio',
                'nombre'             => 'Validar Tarjeta',
                'precio'             => 2,
                'Subtotal'           => 2
             );

            $url = "{$api_url}/boveda/nuevo/{$token}";
            $params = array(
                        'empresa'           => json_encode($empresa),
                        'cliente'           => json_encode($cliente),
                        'tarjetaPagalo'     => json_encode($tarjetaPagalo),
                        'detalle'           => json_encode($detalle)
                        );
            $response_wp = wp_remote_post($url, array(
                    'method'                => 'POST',
                    'body'                  => $params,
                    'timeout'               => 45,
                )
            );
            $response = json_decode($response_wp['body']);
            if ( $response && $response->token ){
                $token = $response->token;
                $status = 1;
                $credit_card_last4 = substr($credit_card_number, -4 );
                Expedition_Helper::insertUserCardRecord( compact('user_id', 'alias', 'credit_card_name', 
                        'credit_card_expiry', 'credit_card_brand', 'credit_card_last4', 'gateway', 'token', 'status') );
                
                return $this->pay_with_credit_card($request);
                
            }else if ( $response && $response->descripcion ){
                $response = ['code'=>'pagalo_card_error', 'message'=> $response->descripcion, 'status'=>607 ];
                return $response;
                //return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
            }else{
                $response = ['code'=>'pagalo_card_error', 'message'=> 'Error with payment gateway','response'=>$response_wp['body'], 'status'=>608 ];
                return $response;
                //return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
            }
        }
        
        $user_token = $user_token->token;
        $tarjetaPagalo = array('tokenTarjeta'=>$user_token);
        $url = "{$api_url}/boveda/transaccionC/{$token}";
        
        /*$total = 0;
        if ( $booking->tour_meta ){
            $meta = json_decode($booking->tour_meta);
            foreach ($meta as $key => $metafield) {
                $total += $metafield->total;
            }
        }*/
        $total = Expedition_Helper::getTourBookingTotalAmount($booking);
        $fee_percent = get_field('credit_card_percents_fee', 'options');
        $fee = 0;
        if ( $fee_percent > 0 ){
            $fee = $fee_percent * 0.01;
        }
        $multiplier = 1+$fee;
        
        $detalle = array(
           'id_producto'        => "booking_$booking->id",
           'cantidad'           => 1,
           'tipo'               => 'Servicio',
           'nombre'             => "Booking $booking->id",
           'precio'             => $total*$multiplier,
           'Subtotal'           => $total*$multiplier
        );
        $params = array(
                    'empresa'       => json_encode($empresa),
                    'cliente'       => json_encode($cliente),
                    'tarjetaPagalo' => json_encode($tarjetaPagalo),
                    'detalle'       => json_encode( array($detalle) ),
                    );
        $response_wp = wp_remote_post($url, array(
                'method'            => 'POST',
                'body'              => $params,
                'timeout'           => 45,
            )
        );
        
        $response = json_decode($response_wp['body']);
        
        if ( $response && $response->decision && $response->decision == 'ACCEPT' ){
                $token = $response->token;
                
                $booking_id     = $booking->id;
                $owner_id       = $booking->owner_id;
                $amount         = $total*$multiplier;
                $currency       = 'GTQ';
                $meta           = '{}';
                $ws_sent        = json_encode($params);
                $ws_response    = $response_wp['body'];
                $success        = 1;
                
                Expedition_Helper::modifyUserBooking( array( 'status'=>BOOKING_CONFIRMED, 'id'=>$booking->id ) );
                Expedition_Helper::insertTransactionRecord( compact('booking_id', 'booking', 'user_id', 'owner_id', 
                        'amount', 'currency', 'gateway', 'meta', 'ws_sent', 'ws_response', 'success') );
                
                //return new WP_REST_Response(
                return    array(
                        'success'=>true, 
                        'message'=> 'Payment processed succesfully!',
                        'tour'=> self::get_tour_info($tour_id),
                        'user'=> Expedition_Helper::getUser( $user_id )
                        );//, 
                    //200 );
                
        }else{
            $booking_id     = $booking->id;
            $owner_id       = $booking->owner_id;
            $amount         = $total*$multiplier;
            $currency       = 'GTQ';
            $meta           = '{}';
            $ws_sent        = json_encode($params);
            $ws_response    = $response_wp['body'];
            $success        = 0;

            Expedition_Helper::insertTransactionRecord( compact('booking_id', 'booking', 'user_id', 'owner_id', 
                    'amount', 'currency', 'gateway', 'meta', 'ws_sent', 'ws_response', 'success') );

            if ( $response && $response->decision && $response->decision == 'REJECT' ){
                $response = ['code'=>'pagalo_card_error', 'message'=> $response->descripcion, 'status'=>607 ];
                return $response;
                //return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
            }else{
                $response = ['code'=>'pagalo_card_error', 'message'=> 'Error with gateway processing payment', 'status'=>608 ];
                return $response;
                //return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
            }
        }
        
    }
    
     /**
     * Pay a event using credit card with epay provider
     * 
     * @param type $request
     * @return \WP_REST_Response
     */
    public function pay_with_credit_card_epay($request, $bookingID, $use_token = true) {
        
        //$sent = Notifications::send('youremail@mail.com', '¡Nuevo booking !', $bookingID);
     
        error_log( print_r( ">> pay with credit card epay: ", true ) );
        
        // user id
        $user_id            = (int)$this->user['ID'];
        // user
        $current_user       = get_userdata($user_id);
        // tour (event) id
        $tour_id            = $request->get_param('tour_id');
        
        // owner id - user for notifications
        $owner_id = (int)get_field('owner', $tour_id);
        $owner_user = get_userdata($owner_id);
        
        // user booking
        $booking            = Expedition_Helper::getUserBookingByUserAndTour($tour_id, $user_id);
        
        
        $user_tokens        = Expedition_Helper::getActiveUserCardsByUser($user_id);
        $user_token         = false;
        if ( $user_tokens ){
            $user_token = $user_tokens[0];
        }
        
        // credit card name 
        $credit_card_name   = $request->get_param('credit_card_name');
        
        // credit card number
        $credit_card_number = str_replace(array(' ', '-'), array('',''), $request->get_param('credit_card_number'));
        
        // expiry date
        // card expiry date must be in the following format: (YYMM)
        $credit_card_expiry = $request->get_param('credit_card_expiry');
        
        // card CVV
        $credit_card_cvv    = $request->get_param('credit_card_cvv');
        
        
        // gateway
        $gateway = 'epay';
        
        $p = get_post($tour_id);
        
        if ( !$p || $p->post_type != 'tour' ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid tour', 'status'=>609 ];
            return $response;
            //return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }else if ( !$booking ){
            $response = ['code'=>'invalid_booking', 'message'=> 'Invalid booking', 'status'=>608 ];
            return $response;
            //return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }else if ( $booking && $booking->user_id != $user_id && $booking->owner_id != $user_id ){
            $response = ['code'=>'invalid_booking', 'message'=> 'Invalid booking', 'status'=>607 ];
            return $response;
            //return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        
        $booking_id = $booking->id;
           
        if($bookingID){
            $booking_id = $bookingID;
        }
        
        $owner_id  = $booking->owner_id;
        
        $firstName = $this->user['first_name'];
        $lastName = $this->user['last_name'];
        $email = $current_user->user_email;
        
        $nit = $request->get_param('nit');
        $nit_name = $request->get_param('nit_name');
        
        $shopper_ip = $request->get_param('shopper_ip');
        
        // $total = Expedition_Helper::getTourBookingTotalAmount($booking);
        $total = $request->get_param('total_amount');
        $total_discounted = $request->get_param('total_discounted');
        
     

        $fee_percent = get_field('credit_card_percents_fee', 'options');
        $fee = 0;
        if ( $fee_percent > 0 ){
            $fee = $fee_percent * 0.01;
        }
        $multiplier = 1+$fee;
        //$amount = $total*$multiplier;
        
        $amount = $total;
        if ($amount <= 0){
            $response = array(
		        'code'=>'epay_error',
		        'message'=> 'monto invalido',
		        'status'=>400,
		        'success'=>'false',
		    );
		     $content2Admin = implode(', ', array_map(
                function ($v, $k) { return sprintf("%s='%s'", $k, $v); },
                $response,
                array_keys($response)
            ));
		    $sent = Notifications::send('backend@mayfer.dev', '¡Nuevo pago con EPAY!', $content2Admin);
            $sent = Notifications::send('admin@mayfer.dev', '¡Nuevo pago con EPAY!', $content2Admin);
            
            return $response;
        }
        
        if($request->get_param('total_discount')){
            $discount = $request->get_param('total_discount');
        } else {
            $discount = 0; 
        }
        
        
        $epay_amount = (int) round(($amount * 100), 0); 

        // ----------------------------------------
        $filename='epay_audit.txt';
        if(!file_exists($filename)){
            $counter = 0 ;
        }
        else{
            $counter = file_get_contents ($filename);
            if($counter > 999999){
                $counter = 0;
            }
        }
        
        $counter++;
        file_put_contents($filename, $counter); //save the new counter ( +1 )
        $auditlength = 6;
        $audit_num = str_pad((string)$counter, $auditlength,"0", STR_PAD_LEFT);
        // ----------------------------------------
        
        
        // building params for request
        
        // epay variables
        $epay_url = "";
        
        // true -> pruebas | false -> produccion 
        $test_epay = true;
        
        if($test_epay){
            $epay_url = "https://epaytestvisanet.com.gt/paymentcommerce.asmx?WSDL";
            $afiliation_epay = "00575123";
            $param=array(
            	'posEntryMode' => "012"	                //Metodo de entrada
            	,'pan' => $credit_card_number		    //Numero de Tarjeta
            	,'expdate' => $credit_card_expiry 	    //Fecha de expiracion (YYMM)
            	,'amount' => $epay_amount 				    //Monto de la transaccion sin delimitadores
            	,'track2Data' => "" 
            	,'cvv2' => $credit_card_cvv			    //Codigo de secguridad
            	,'paymentgwIP' => "190.149.69.135"	    //IP WebService Visanet
            	,'shopperIP' => $shopper_ip				//IP Cliente que realiza la compra
            	,'merchantServerIP' => "67.205.167.98"	//IP Comercio integrado a VisaNet
            	,'merchantUser' => "76B925EF7BEC821780B4B21479CE6482EA415896CF43006050B1DAD101669921" //Usuario
            	,'merchantPasswd' => "DD1791DB5B28DDE6FBC2B9951DFED4D97B82EFD622B411F1FC16B88B052232C7" //Password
            	,'terminalId' => "77788881"			    //Terminal
            	,'merchant' => $afiliation_epay			    //Afiliacion
            	,'messageType' => "0200"			    //Mensaje de Venta
            	,'auditNumber' => $audit_num		        //Correlativo ciclico de transaccion de 000001 hasta 999999
            	,'additionalData' => ""		            //Datos adicionales cuotas o puntos
            );
            
        } else {
            $epay_url = "https://epayvisanet.com.gt/paymentcommerce.asmx?WSDL";
            $afiliation_epay = "035250001";
            $param=array(
            	'posEntryMode' => "012"	                //Metodo de entrada
            	,'pan' => $credit_card_number		    //Numero de Tarjeta
            	,'expdate' => $credit_card_expiry 	    //Fecha de expiracion (YYMM)
            	,'amount' => $epay_amount 				    //Monto de la transaccion sin delimitadores
            	,'track2Data' => "" 
            	,'cvv2' => $credit_card_cvv			    //Codigo de secguridad
            	,'paymentgwIP' => "190.111.1.198"	    //IP WebService Visanet
            	,'shopperIP' => $shopper_ip				//IP Cliente que realiza la compra
            	,'merchantServerIP' => "162.0.237.247"	//IP Comercio integrado a VisaNet
            	,'merchantUser' => "44BC7ABEA713FDE5D02A755161168156260B9125A4852E6345C29A6799A29CCF" //Usuario
            	,'merchantPasswd' => "6D4448F5233D7FF5ED12503892803EF040AFA16FA47C356A8B51E1BCFAB60BB7" //Password
            	,'terminalId' => "99543999"			    //Terminal
            	,'merchant' => $afiliation_epay			    //Afiliacion
            	,'messageType' => "0200"			    //Mensaje de Venta
            	,'auditNumber' => $audit_num		        //Correlativo ciclico de transaccion de 000001 hasta 999999
            	,'additionalData' => ""		            //Datos adicionales cuotas o puntos
            );
        }

        
        $params = array(array('AuthorizationRequest' => $param));
        
        error_log( print_r( "New Request: ", true ) );
        error_log( print_r( $params, true ) );

        ini_set("default_socket_timeout", 10);//Tiempo en segundos para disparar reversa automatica
        $client = new SoapClient($epay_url, array('connection_timeout' => 10));//Tiempo en segundos para disparar reversa automatica
        
        try 
        	{	
        		$result  = $client->__soapCall('AuthorizationRequest',$params);
        		// responseCode: 00 (success)
        		// messageType: 0200 venta (0400 reversion)
        		if($result->response->responseCode === '00'){
        		     // modifiying booking status

        		    error_log( print_r( ">> Response: ", true ) );
                    error_log( print_r( $result, true ) );
                    error_log( print_r( $result->response->responseCode, true ) );
                    
                    // ------------------------------------------------------------------------
                    // ------------------------------------------------------------------------
                    
                   
                    
                    $credit_card_last4 = substr($credit_card_number, -4 );
                    $status = 1;
                    $reference_num = $result->response->referenceNumber;
                    $auth_num = $result->response->authorizationNumber;
                    // -------------- saving card information as "backup" ---------------------
                    Expedition_Helper::insertUserCardRecord( compact('user_id', 'credit_card_name', 
                        'credit_card_expiry', 'credit_card_last4', 'gateway', 'status', 'afiliation_epay', 'audit_num', 'reference_num', 'auth_num', 'amount', 'booking_id') );
                    
                    
                    // inserting transacion record
                    $success = 1;
                    $meta = '{}';
                    $currency = 'GTQ';
                    $ws_sent = json_encode($params);
                    $ws_response = json_encode($result->response);
                    
                    Expedition_Helper::insertTransactionRecord( compact('booking_id', 'booking', 'user_id', 'owner_id', 
                        'amount', 'currency', 'gateway', 'meta', 'ws_sent', 'ws_response', 'success') );
                   
                    //  making up response
                    $response = array(
                        'success'=>true,
        		        'code'=>'epay_success',
        		        'message'=> 'Transaccion exitosa',
        		        'status'=>200,
        		        'epay_response_code'=>$result->response->responseCode,
        		        'audit_number'=>$result->response->auditNumber,
        		        'reference_number'=>$result->response->referenceNumber,
        		        'authorization_number'=>$result->response->authorizationNumber,
        		        'message_type'=>$result->response->messageType,
        		        'amount'=>$epay_amount
        		    );
        		    
        		    
        		   
        		    // Notification when a user pay with card 
                        $content = '<h2>Nuevo pago con tarjeta</h2>'
                                . "<p>$firstName $lastName acaba de realizar su registro con tarjeta.  </p>"
                                . "Correo electrónico: $email<br/>"
                                . "Descuento aplicado: $total_discounted ($discount %)<br/>"
                                . "Precio final : GTQ".number_format($amount, 2)."</p>";
                
                    $sent = Notifications::send($owner_user->user_email, '¡Nuevo registro!', $content);
                    Expedition_Helper::logMessage( "Deposit confirmation email ".($sent ? 'sent' : 'failed')." to $owner_user->user_email", 'emails_sent.txt');
                        
                    $content2Admin = implode(', ', array_map(
                        function ($v, $k) { return sprintf("%s='%s'", $k, $v); },
                        $response,
                        array_keys($response)
                    ));
                    
        		    $sent = Notifications::send('backend@mayfer.dev', '¡Nuevo pago con EPAY!', $content2Admin);
        		    $sent = Notifications::send('admin@mayfer.dev', '¡Nuevo pago con EPAY!', $content2Admin);
        		    
                    return $response;
        		} else {
        		    
        		    // inserting transacion record
                    $success = 0;
                    $meta = '{}';
                    $currency = 'GTQ';
                    $ws_sent = json_encode($params);
                    $ws_response = json_encode($result->response);
                    Expedition_Helper::insertTransactionRecord( compact('bookingID', 'booking', 'user_id', 'owner_id', 
                        'amount', 'currency', 'gateway', 'meta', 'ws_sent', 'ws_response', 'success') );
        		    
        		    $response = array(
        		        'code'=>'epay_error',
        		        'message'=> 'responseCode no identificado',
        		        'status'=>400,
        		        'epay_response_code'=>$result->response->responseCode,
        		        'audit_number'=>$result->response->auditNumber,
        		        'reference_number'=>$result->response->referenceNumber,
        		        'authorization_number'=>$result->response->authorizationNumber,
        		        'message_type'=>$result->response->messageType,
        		        'amount'=>$epay_amount,
        		        'success'=>false,
        		    );
        		        
        		     // Notification when a user pay with card and failed
                        $content = '<h2>Error en el pago</h2>'
                                . "<p>$firstName $lastName acaba de realizar su registro con tarjeta pero hubo un error  </p>"
                                . "Correo electrónico: $email<br/>"
                                . "Error: ".$result->response->responseCode;
                
                    $sent = Notifications::send($owner_user->user_email, 'Error en pago', $content);
                    Expedition_Helper::logMessage( "Deposit confirmation email ".($sent ? 'sent' : 'failed')." to $owner_user->user_email", 'emails_sent.txt');
                     
                    $content2Admin = implode(', ', array_map(
                        function ($v, $k) { return sprintf("%s='%s'", $k, $v); },
                        $response,
                        array_keys($response)
                    ));
                    
        		    $sent = Notifications::send('backend@mayfer.dev', '¡Nuevo pago con EPAY!', $content2Admin);
                    $sent = Notifications::send('admin@mayfer.dev', '¡Nuevo pago con EPAY!', $content2Admin);
        		    
                    return $response;
        		}
        		
                
                
        		
        	}
        catch(SoapFault $e) 
        	{
        		error_log( print_r( "Timeout! ", true ) );
        		if($test_epay){
        		    $afiliation_epay = "00575123";
            		$param=array(
            			'posEntryMode' => "012"	            //Metodo de entrada
            			,'pan' => $credit_card_number	    //Numero de Tarjeta
            			,'expdate' => $credit_card_expiry  	//Fecha de expiracion (YYMM)
            			,'amount' => $epay_amount 				//Monto de la transaccion sin delimitadores
            			,'track2Data' => "" 
            			,'cvv2' => $credit_card_cvv			//Codigo de secguridad
            			,'paymentgwIP' => "190.149.69.135"			//IP WebService Visanet
            			,'shopperIP' => $shopper_ip			//IP Cliente que realiza la compra
            			,'merchantServerIP' => "67.205.167.98"		//IP Comercio integrado a VisaNet
            			,'merchantUser' => "76B925EF7BEC821780B4B21479CE6482EA415896CF43006050B1DAD101669921" //Usuario
            			,'merchantPasswd' => "DD1791DB5B28DDE6FBC2B9951DFED4D97B82EFD622B411F1FC16B88B052232C7" //Password
            			,'terminalId' => "77788881"			//Terminal
            			,'merchant' => $afiliation_epay			//Afiliacion
            			,'messageType' => "0400"			//Mensaje de Reversion
            			,'auditNumber' => $audit_num		//Numero de Auditoria enviado en la solicitud de venta a reversar
            			,'additionalData' => ""		//Datos adicionales cuotas o puntos
            		);
        		} else {
        		    $afiliation_epay = "035250001";
        		    $param=array(
            			'posEntryMode' => "012"	            //Metodo de entrada
            			,'pan' => $credit_card_number	    //Numero de Tarjeta
            			,'expdate' => $credit_card_expiry  	//Fecha de expiracion (YYMM)
            			,'amount' => $epay_amount 				//Monto de la transaccion sin delimitadores
            			,'track2Data' => "" 
            			,'cvv2' => $credit_card_cvv			//Codigo de secguridad
            			,'paymentgwIP' => "190.111.1.198"	    //IP WebService Visanet
            			,'shopperIP' => $shopper_ip			//IP Cliente que realiza la compra
                    	,'merchantServerIP' => "162.0.237.247"	//IP Comercio integrado a VisaNet
                    	,'merchantUser' => "44BC7ABEA713FDE5D02A755161168156260B9125A4852E6345C29A6799A29CCF" //Usuario
                    	,'merchantPasswd' => "6D4448F5233D7FF5ED12503892803EF040AFA16FA47C356A8B51E1BCFAB60BB7" //Password
                    	,'terminalId' => "99543999"				//Terminal
            			,'merchant' => $afiliation_epay			//Afiliacion
            			,'messageType' => "0400"			//Mensaje de Reversion
            			,'auditNumber' => $audit_num		//Numero de Auditoria enviado en la solicitud de venta a reversar
            			,'additionalData' => ""		//Datos adicionales cuotas o puntos
            		);
        		}
        		
        
        		$params = array(array('AuthorizationRequest' => $param));	
        
        		error_log( print_r( "Reverse request:  ", true ) );
        		error_log( print_r( $params, true ) );

        		$result  = $client->__soapCall('AuthorizationRequest',$params);
        		
        		error_log( print_r( "Reverse response:  ", true ) );
        		error_log( print_r( $result, true ) );
                
                // Notification when a user pay with card and failed
                        $content = '<h2>Error en el pago</h2>'
                                . "<p>$firstName $lastName acaba de realizar su registro con tarjeta pero hubo un error  </p>"
                                . "Correo electrónico: $email<br/>";
                
                $sent = Notifications::send($owner_user->user_email, 'Error en pago', $content);
                Expedition_Helper::logMessage( "Deposit confirmation email ".($sent ? 'sent' : 'failed')." to $owner_user->user_email", 'emails_sent.txt');
                      
                $content2Admin = 'TIMEOUT en request a epay; REVERSE REQUEST DONE';
        		$sent = Notifications::send('backend@mayfer.dev', '¡Nuevo pago con EPAY!', $content2Admin);
                $sent = Notifications::send('admin@mayfer.dev', '¡Nuevo pago con EPAY!', $content2Admin);
                
        
        	}
        
    }
    
    /**
     * Pay a tour using credit card
     * 
     * @param type $request
     * @return \WP_REST_Response
     */
    public function pay_with_credit_card_cybersource($request, $use_token = true) {
        
        get_template_part('includes/cybersource-sdk/lib/CybsSoapClient');
        
        
        $user_id            = (int)$this->user['ID'];
        $current_user       = get_userdata($user_id);
        $tour_id            = $request->get_param('tour_id');
        $booking            = Expedition_Helper::getUserBookingByUserAndTour($tour_id, $user_id);
        $user_tokens        = Expedition_Helper::getActiveUserCardsByUser($user_id);
        $user_token         = false;
        if ( $user_tokens ){
            $user_token = $user_tokens[0];
        }
        
        $alias              = $request->get_param('alias');
        $credit_card_name   = $request->get_param('credit_card_name');
        $credit_card_number = str_replace(array(' ', '-'), array('',''), $request->get_param('credit_card_number'));
        $credit_card_expiry = $request->get_param('credit_card_expiry');
        $credit_card_cvv    = $request->get_param('credit_card_cvv');
        $credit_card_expiry_parts   = explode( '/', $credit_card_expiry );
        $credit_card_expiry_month   = $credit_card_expiry_parts[0];
        $credit_card_expiry_year    = $credit_card_expiry_parts[1];
        $credit_card_brand  = $request->get_param('credit_card_brand');
        $fingerprint        = $request->get_param('fingerprint');
        $gateway            = 'cybersource';
        
        $p = get_post($tour_id);
        
        if ( !$p || $p->post_type != 'tour' ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid tour', 'status'=>609 ];
            return $response;
            //return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }else if ( !$booking ){
            $response = ['code'=>'invalid_booking', 'message'=> 'Invalid booking', 'status'=>608 ];
            return $response;
            //return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }else if ( $booking && $booking->user_id != $user_id && $booking->owner_id != $user_id ){
            $response = ['code'=>'invalid_booking', 'message'=> 'Invalid booking', 'status'=>607 ];
            return $response;
            //return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        $referenceCode = $fingerprint;
        $firstName = $this->user['first_name'];
        $lastName = $this->user['last_name'];
        $address = '17 avenida y 32 calle final zona 11 1-24';
        $postal_code = '010011';
        $email = $current_user->user_email;// 'estuardoeg@gmail.com';
        
        $nit = 'C/F';
        $total = Expedition_Helper::getTourBookingTotalAmount($booking);
        $fee_percent = get_field('credit_card_percents_fee', 'options');
        $fee = 0;
        if ( $fee_percent > 0 ){
            $fee = $fee_percent * 0.01;
        }
        $multiplier = 1+$fee;
        $amount = $total*$multiplier;
        
        
        $client = new CybsSoapClient();
        $cybsrequest = $client->createRequest($referenceCode);
        
        $ccAuthService = new stdClass();
        $ccAuthService->run = 'true';
        $cybsrequest->ccAuthService = $ccAuthService;
        $ccCaptureService = new stdClass();
        $ccCaptureService->run = 'true';
        $cybsrequest->ccCaptureService = $ccCaptureService;
        
        $billTo = new stdClass();
        $billTo->firstName = $firstName;
        $billTo->lastName = $lastName;
        $billTo->street1 = $address;
        $billTo->city = 'GT';
        $billTo->country = 'GT';
        $billTo->state = 'GT';
        $billTo->postalCode = $postal_code;
        $billTo->email = $email;
        $billTo->ipAddress = $_SERVER['REMOTE_ADDR'];
        $billTo->company = $nit;
        $cybsrequest->billTo = $billTo;

        // AMOUNT
        $purchaseTotals = new stdClass();
        $purchaseTotals->currency = 'GTQ';
        $purchaseTotals->grandTotalAmount= strval(number_format((float)$amount, 2, '.', ''));
        $cybsrequest->purchaseTotals = $purchaseTotals;
        $cybsrequest->deviceFingerprintID = $fingerprint;
        $cybsrequest->deviceFingerprintRaw = true;
        
        $send_card = false;
        if ( $use_token ){
            // USE TOKEN LOGIC, TO CREATE TOKEN OR USE EXISTING
            
            if ( !$user_token ){
                // IF NOT EXISTS TOKEN, CREATE ONE
                $send_card = true;
                $recurringSubscriptionInfo = new stdClass();
                $recurringSubscriptionInfo->frequency = 'on-demand';
                $cybsrequest->recurringSubscriptionInfo = $recurringSubscriptionInfo;
                
                $paySubscriptionCreateService = new stdClass();
                $paySubscriptionCreateService->run = 'true';
                $cybsrequest->paySubscriptionCreateService = $paySubscriptionCreateService;

            }else{
                // IF EXISTS TOKEN, JUST USE IT
                $recurringSubscriptionInfo = new stdClass();
                $recurringSubscriptionInfo->subscriptionID = $user_token->token;
                $cybsrequest->recurringSubscriptionInfo = $recurringSubscriptionInfo;
            }
            
        }else{
            // FOR SINGLE TRANSACTION
            $send_card = true;
        }
        
        if ( $send_card ){
            $card = new stdClass();
            $card->accountNumber = $credit_card_number;
            $card->expirationMonth = $credit_card_expiry_month;
            $card->expirationYear = $credit_card_expiry_year;
            $card->cvNumber = $credit_card_cvv;
            $card->cardType = Expedition_Helper::getCCType($credit_card_number);
            $cybsrequest->card = $card;
        }
        
        $response = $client->runTransaction($cybsrequest);
        
        // if ( !$user_token ){
            
            $booking_id     = $booking->id;
            $owner_id       = $booking->owner_id;
            $amount         = $total*$multiplier;
            $currency       = 'GTQ';
            $meta           = '{}';
            $ws_sent        = json_encode($cybsrequest);
            $ws_response    = json_encode($response);
            
            if ( $response && $response->decision && $response->decision == 'ACCEPT' ){
                $token = $response->requestID;
                $status = 1;
                $credit_card_last4 = substr($credit_card_number, -4 );
                // IF WE HAVE TO SEND CARD, WE STORE IN DB TO USE LATER
                if ( $use_token && $send_card ){
                    Expedition_Helper::insertUserCardRecord( compact('user_id', 'alias', 'credit_card_name', 
                        'credit_card_expiry', 'credit_card_brand', 'credit_card_last4', 'gateway', 'token', 'status') );
                }
                
                $success        = 1;
                Expedition_Helper::modifyUserBooking( array( 'status' => BOOKING_CONFIRMED, 'id'=>$booking->id ) );
                Expedition_Helper::insertTransactionRecord( compact('booking_id', 'booking', 'user_id', 'owner_id', 
                        'amount', 'currency', 'gateway', 'meta', 'ws_sent', 'ws_response', 'success') );
                
                return array(
                        'success'=>true, 
                        'message'=> 'Payment processed succesfully!',
                        'tour'=> self::get_tour_info($tour_id),
                        'user'=> Expedition_Helper::getUser( $user_id )
                        );//, 
                
                
                //return $this->pay_with_credit_card($cybsrequest);
                
            }else{
                
                $success        = 0;
                Expedition_Helper::insertTransactionRecord( compact('booking_id', 'booking', 'user_id', 'owner_id', 
                        'amount', 'currency', 'gateway', 'meta', 'ws_sent', 'ws_response', 'success') );
                
                if ( $response && $response->decision && $response->decision == 'REJECT' ){
                    $__response = ['code'=>'cybersource_card_error', 'message'=> Expedition_Helper::reasonCode( isset($response->reasonCode) ? $response->reasonCode : false ), 'status'=>607 ];
                    return $__response;
                    //return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
                }else{
                    $__response = ['code'=>'cybersource_card_error', 'message'=> 'Error with payment gateway','response'=>null, 'status'=>608 ];
                    return $__response;
                    //return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
                }
            }
        // }
        
    }
    
    /**
     * Logic when user submits the photo with confirmation of deposit for tour booking
     * 
     * @return \WP_REST_Response
     */
    public static function deposit_confirmation( $request ){
        
        $tour_id = (int)$request->get_param('tour_id');
        $receipt = (int)$request->get_param('receipt');
        $bank = $request->get_param('bank');
        $user_id = (int)$this->user['ID'];
        $tour = get_post($tour_id);
        $firstName = $this->user['first_name'];
        $lastName = $this->user['last_name'];
        
        if ( !$tour ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid tour', 'status'=>609 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        // is a privated tour
        if ( get_field('type', $tour_id) != 'public' || !get_field('category', $tour_id, false) ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid type of tour', 'status'=>609 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        // handle uploaded file
        if( empty( $_FILES ) || !isset($_FILES[ 'file']) ){
            $response = ['code'=>'missing_photo', 'message'=> "Missing attachment", 'data'=> ['status'=>600] ];
            return new WP_REST_Response($response, 200 );
        }
        
        $booking = Expedition_Helper::getUserBookingByUserAndTour( $tour_id, $user_id );
        
        // is previously booked same tour
        if ( !$booking ){
            $response = ['code'=>'tour_previously_no_reserved', 'message'=> "You didn't book this tour", 'status'=>611 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        $file_id = Expedition_Helper::save_image_doc_from_url( false, 'file_'.time().'.jpg', $_FILES[ 'file'], 0, $this->user['ID'] );
        
        if ( is_wp_error($file_id) ){
            $response = ['code'=>'file_error', 'message'=> "Error saving the file", 'data'=> ['status'=>601, 'file'=>$file_id] ];
            return new WP_REST_Response($response, 200 );
        }
        
        /*$total = 0;
        if ( $booking->tour_meta ){
            $meta = json_decode($booking->tour_meta);
            foreach ($meta as $key => $metafield) {
                $total += $metafield->total;
            }
        }*/
        $total = Expedition_Helper::getTourBookingTotalAmount($booking);
        
        
        $meta = new stdClass();
        $meta->receipt = $receipt;
        $meta->bank = $bank;
        $meta->attachment_id = $file_id;
        update_field('private', 1, $file_id);
        $img = wp_get_attachment_image_src($file_id, 'large');
        $meta->attachment_url = $img[0];
        $owner_id = (int)get_field('owner', $tour_id);
        
        $args = array(
            'booking_id' => $booking->id,
            'user_id' => $user_id,
            'owner_id' => $owner_id,
            'amount' => $total,
            'currency' => 'GTQ',
            'gateway' => GATEWAY_DEPOSIT,
            'meta' => json_encode($meta),
            
            'ws_sent' => '',
            'ws_response' => '',
            'booking' => '',
            'success' => TRANSACTION_PENDING
        );
        
        Expedition_Helper::insertTransactionRecord($args);
        Expedition_Helper::modifyUserBooking(array('status'=> BOOKING_PENDING_CONFIRM, 'id'=> $booking->id ));
        
        $current_user = get_userdata($user_id);
        $owner_user = get_userdata($owner_id);
        
        $fullname = $current_user->first_name . ' '. $current_user->last_name ;
        
        $one_signal_id = get_field( 'one_signal_id', 'user_'.$owner_id);
        if ( $one_signal_id ){
            Notifications::sendPushToUser($one_signal_id, "$fullname has uploaded the deposit confirmation, please check your email.", "Deposit confirmation");
        }
        
        $attach_path = array();
        if ( $file_id ){
            $attach_path[] = get_attached_file( $file_id );
        }
        
        $content = '<h2>Nuevo depósito enviado</h2>'
                . "<p>$firstName $lastName has uploaded the deposit confirmation, the details below: </p>"
                . "Correo electrónico: $email<br/>"
                . "<p>Bank : $bank<br/>"
                . "Receipt number : $receipt<br/>"
                . "Amount : GTQ".number_format($total, 2)."</p>"
                . "<p>Also check the attached file.</p>";
        
        $sent = Notifications::send($owner_user->user_email, '¡Nuevo registro!', $content, $attach_path);
        Expedition_Helper::logMessage( "Deposit confirmation email ".($sent ? 'sent' : 'failed')." to $owner_user->user_email", 'emails_sent.txt');
    
        return new WP_REST_Response(
                array(
                    'success'=>true, 
                    // 'ids' => $gallery_ids, 
                    // 'gallery'=> $gallery, 
                    'message'=> 'Confirm added succesfully',
                    'file_id' =>$file_id,
                    'tour'=> self::get_tour_info($tour_id)
                    ), 
                200 );
        
    }
    
    /**
     * Returns list of transactions related to a tour booking
     * 
     * @return \WP_REST_Response
     */
    public static function tour_booking_transactions( $request ){
        
        $tour_id = (int)$request->get_param('tour_id');
        $booking_id = (int)$request->get_param('booking_id');
        $user_id = (int)$this->user['ID'];
        $tour = get_post($tour_id);
        
        if ( !$tour ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid tour', 'status'=>609 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        $booking = Expedition_Helper::getUserBooking( $booking_id );
        
        // is previously booked same tour
        if ( !$booking ){
            $response = ['code'=>'tour_previously_no_reserved', 'message'=> "Invalid booking", 'status'=>611 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        if ( $user_id != $booking->owner_id && $user_id != $booking->user_id ){
            $response = ['code'=>'invalid_booking', 'message'=> "You have no permissions to handle the expeditioner's booking", 'status'=>649 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        $transactions = (array) Expedition_Helper::getTransactionsByBooking($booking_id);
        
        return new WP_REST_Response(
                array(
                    'success'=>true, 
                    'message'=> 'Query succesful',
                    'transactions' => $transactions,
                    ), 
                200 );
        
    }
    
    /**
     * Logic when tour's owner confirm the deposit by expeditioner
     * 
     * @return \WP_REST_Response
     */
    public static function deposit_confirmation_by_owner( $request ){
        
        $tour_id = (int)$request->get_param('tour_id');
        $booking_id = (int)$request->get_param('booking_id');
        $user_id = (int)$this->user['ID'];
        $tour = get_post($tour_id);
        
        if ( !$tour ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid tour', 'status'=>609 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        // is a privated tour
        if ( get_field('type', $tour_id) != 'public' ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid type of tour', 'status'=>609 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        $booking = Expedition_Helper::getUserBooking( $booking_id );
        
        // is previously booked same tour
        if ( !$booking ){
            $response = ['code'=>'tour_previously_no_reserved', 'message'=> "Invalid booking", 'status'=>611 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        if ( $user_id != $booking->owner_id ){
            $response = ['code'=>'invalid_booking', 'message'=> "You have no permissions to handle the expeditioner's booking", 'status'=>649 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        Expedition_Helper::modifyUserBooking(array('status'=> BOOKING_CONFIRMED, 'id'=> $booking->id ));
        Expedition_Helper::insertActivityRecord($booking->user_id, 'booking_confirmed', $booking->id);
        
        return new WP_REST_Response(
                array(
                    'success'=>true, 
                    // 'ids' => $gallery_ids, 
                    // 'gallery'=> $gallery,
                    'booking'=> self::format_booking( Expedition_Helper::getUserBooking( $booking->id  ) ),
                    'message'=> 'Confirm added succesfully',
                    // 'tour'=> self::get_tour_info($tour_id)
                    ), 
                200 );
        
    }
    
    /**
     * Logic when tour's owner rejects the deposit by expeditioner
     * 
     * @return \WP_REST_Response
     */
    public static function deposit_rejection_by_owner( $request ){
        
        $tour_id = (int)$request->get_param('tour_id');
        $booking_id = (int)$request->get_param('booking_id');
        $user_id = (int)$this->user['ID'];
        $tour = get_post($tour_id);
        
        if ( !$tour ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid tour', 'status'=>609 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        // is a privated tour
        if ( get_field('type', $tour_id) != 'public' ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid type of tour', 'status'=>609 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        $booking = Expedition_Helper::getUserBooking( $booking_id );
        
        // is previously booked same tour
        if ( !$booking ){
            $response = ['code'=>'tour_previously_no_reserved', 'message'=> "Invalid booking", 'status'=>611 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        if ( $user_id != $booking->owner_id ){
            $response = ['code'=>'invalid_booking', 'message'=> "You have no permissions to handle the expeditioner's booking", 'status'=>649 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        Expedition_Helper::modifyUserBooking(array('status'=> BOOKING_REJECTED, 'id'=> $booking->id ));
        
        return new WP_REST_Response(
                array(
                    'success'=>true, 
                    // 'ids' => $gallery_ids, 
                    // 'gallery'=> $gallery,
                    'booking'=> self::format_booking( Expedition_Helper::getUserBooking( $booking->id  ) ),
                    'message'=> 'Reject added succesfully',
                    // 'tour'=> self::get_tour_info($tour_id)
                    ), 
                200 );
        
    }
    
    /**
     * Tour owner make tour check in using the QR of Expeditioner
     * 
     * @return \WP_REST_Response
     */
    public static function qr_checkin( $request ){
        
        $tour_id = (int)$request->get_param('tour_id');
        $booking_id = (int)$request->get_param('booking_id');
        $expeditioner_id = (int)$request->get_param('expeditioner_id');
        $user_id = (int)$this->user['ID'];
        $tour = get_post($tour_id);
        
        if ( !$tour ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid tour', 'status'=>609 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        // is a privated tour
        if ( get_field('type', $tour_id) != 'public' || !get_field('category', $tour_id, false) ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid type of tour', 'status'=>609 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        $booking = Expedition_Helper::getUserBooking( $booking_id );
        
        // is previously booked same tour
        if ( !$booking ){
            $response = ['code'=>'tour_previously_no_reserved', 'message'=> "You didn't book this tour", 'status'=>611 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        if ( $booking->id != $booking_id ){
            $response = ['code'=>'tour_previously_no_reserved', 'message'=> "You didn't book this tour", 'status'=>611 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        
        
        $owner_id = (int)get_field('owner', $tour_id);
        // is a privated tour
        if ( $owner_id != $user_id ){
            $response = ['code'=>'invalid_tour', 'message'=> "You have no permissions to handle the checkin", 'status'=>649 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        if ( $booking->status == BOOKING_CHECKED_IN ){
            $response = ['code'=>'invalid_tour', 'message'=> "The booking has been checked in previously", 'status'=>651 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        if ( $booking->status != BOOKING_CONFIRMED ){
            $response = ['code'=>'invalid_tour', 'message'=> "The booking's status, is not valid to make checkin", 'status'=>651 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        Expedition_Helper::modifyUserBooking(array('status'=> BOOKING_CHECKED_IN, 'id'=> $booking->id ));
        $one_signal_id = get_field( 'one_signal_id', 'user_'.$expeditioner_id);
        if ( $one_signal_id ){
            Notifications::sendPushToUser($one_signal_id, "Successful Check in!", "Booking", array('notification'=>'checked_in', 'tour_id', $tour_id ) );
        }
        
        return new WP_REST_Response(
                array(
                    'success'=>true, 
                    // 'ids' => $gallery_ids, 
                    'booking'=> self::format_booking( Expedition_Helper::getUserBooking( $booking->id  ) ),
                    'message'=> 'Booking checked in',
                    // 'file_id' =>$file_id,
                    //'tour'=> self::get_tour_info($tour_id)
                    ), 
                200 );
        
    }
    
    /**
     * Return public tour Expeditioners list
     * 
     * @param type $tour_id
     * @return type
     */
    public function get_tour_expeditioners($request){
        
        $tour_id = (int)$request->get_param('id');
        $user_id = (int)$this->user['ID'];
        $tour = get_post($tour_id);
        
        if ( !$tour ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid tour', 'status'=>609 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        // is a privated tour
        if ( get_field('type', $tour_id) != 'public' || !get_field('category', $tour_id, false) ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid type of tour', 'status'=>609 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        $owner_id = (int)get_field('owner', $tour_id);
        // is a privated tour
        if ( $owner_id != $user_id ){
            $response = ['code'=>'invalid_tour', 'message'=> "You have no permissions to handle the expeditioner's bookings", 'status'=>649 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        return new WP_REST_Response(
                array(
                    'success'=>true,
                    'expeditioners'=> self::get_public_tour_expeditioners( $tour_id )  
                ), 200 );
        
    }
    
    /**
     * Return booking
     * 
     * @return type
     */
    public function get_booking($request){
        
        $booking_id = (int)$request->get_param('id');
        $user_id = (int)$this->user['ID'];
        
        $booking = Expedition_Helper::getUserBooking( $booking_id  );
        
        if ( !$booking ){
            $response = ['code'=>'invalid_booking', 'message'=> 'Invalid booking', 'status'=>629 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        // is a privated tour
        if ( $user_id != $booking->owner_id && $booking->user_id != $user_id ){
            $response = ['code'=>'invalid_booking', 'message'=> "You have no permissions to handle the expeditioner's booking", 'status'=>649 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        return new WP_REST_Response(
                array(
                    'success'=>true,
                    'booking'=> $booking
                ), 200 );
        
    }
    
    /**
     * Creates a tour from the front
     * 
     * @return \WP_REST_Response
     */
    public static function create_tour( $request ){
        
        $type = (string)$request->get_param('type');
        $title = (string)$request->get_param('title');
        $main_color = (string)$request->get_param('main_color');
        $desc = (string)$request->get_param('desc');
        $max_capacity = (string)$request->get_param('max_capacity');
        // departure
        $departure_place_name = (string)$request->get_param('departure_place_name');
        $departure_date = (string)$request->get_param('departure_date');
        $departure_place = $request->get_param('departure_place');
        // return
        $return_place_name = (string)$request->get_param('return_place_name');
        $return_date = (string)$request->get_param('return_date');
        $return_place = $request->get_param('return_place');
        
        
        $itinerary = (array)$request->get_param('itinerary');
        $expeditioners_going = (array)$request->get_param('expeditioners_going');
        $what_to_bring = (array)$request->get_param('what_to_bring');
        $categories = (array)$request->get_param('categories');
        $monetary_deposit_copy = (string)$request->get_param('monetary_deposit_copy');
        $cancellation_policy = (string)$request->get_param('cancellation_policy');
        $hide_seats_information = (bool)$request->get_param('hide_seats_information');
        
        
        
        $user_id = (int)$this->user['ID'];
        
        // is a privated tour
        /*if ( get_field('type', $tour_id) != 'public' || !get_field('category', $tour_id, false) ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid type of tour', 'status'=>609 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }*/
        
        /*var_dump(
                array( 'type'=>$type, 'main_color'=> $main_color, 'max_capacity'=> $max_capacity, 'desc'=> $desc,
            'itinerary'=> $itinerary, 'expeditioners_going'=> $expeditioners_going, 'what_to_bring'=> $what_to_bring)
                );
        exit();*/
        
        /**************************************************************************/
        
        $_post = array(
            'post_title'	=> $title,
            'post_type'     	=> 'tour',
            'post_status'	=> 'publish',
            'post_author'	=> $user_id,
            'post_content'      => $desc
        );

        // insert the post into the database
        $post_id = wp_insert_post( $_post );
        
        // save meta values
        update_field( 'type', $type, $post_id );
        update_field( 'main_color', $main_color, $post_id );
        update_field( 'desc', $desc, $post_id );
        update_field( 'max_capacity', (int)$max_capacity, $post_id );
        update_field( 'monetary_deposit_copy', $monetary_deposit_copy, $post_id );
        update_field( 'cancellation_policy', $cancellation_policy, $post_id );
        update_field( 'hide_seats_information', $hide_seats_information ? 1 : 0, $post_id );
        
        
        
        update_field( 'departure_place_name', $departure_place_name, $post_id );
        update_field( 'departure_date', date('Y-m-d H:i:s', strtotime($departure_date)), $post_id ); // 2018-03-28 17:00:00
        $departure_place_obj = array(
            'address'   => $departure_place['address'],
            'lat'       => $departure_place['latitude'],
            'lng'       => $departure_place['longitude']
        );
        update_field( 'departure_place', $departure_place_obj, $post_id ); 
        
        update_field( 'return_place_name', $return_place_name, $post_id );
        update_field( 'return_date', date('Y-m-d H:i:s', strtotime($return_date)), $post_id ); // 2018-03-28 17:00:00
        $return_place_obj = array(
            'address'   => (string)$return_place['address'],
            'lat'       => (string)$return_place['latitude'],
            'lng'       => (string)$return_place['longitude']
        );
        update_field( 'return_place', $return_place_obj, $post_id ); 
        
        if ( $itinerary && count($itinerary)>0 ){
            $itineraryObj = array();
            foreach ($itinerary as $key => $itineraryItem) {
                $itineraryObj[] = array(
                    'date' => date('Y-m-d H:i:s', strtotime($itineraryItem['date'])),
                    'desc' => $itineraryItem['place_name']
                );
            }
            update_field( 'itinerary', $itineraryObj, $post_id );
        }
        
        update_field( 'owner', $user_id, $post_id );
        
        if ( $type == 'private'):
            $expeditioners_goingObj = array();
            $exists = false;
            if ( $expeditioners_going && count($expeditioners_going)>0 ){
                foreach ($expeditioners_going as $key => $expeditioner) {
                    if ( (int)$expeditioner == $user_id ){
                        $exists = true;
                    }
                    $expeditioners_goingObj[] = (int)$expeditioner;
                }
            }
            if ( !$exists ){
                $expeditioners_goingObj[] = $user_id;
            }
            update_field( 'expeditioners', $expeditioners_goingObj, $post_id );
        endif;
        
        $categoriesObj = array();
        if ( $categories && count($categories)>0 ){
            
            foreach ($categories as $key => $category ) {
                $tempObj = array();
                $tempObj['id'] = uniqid();
                $tempObj['category'] = $category['name'];
                $tempObj['desc'] = $category['desc'];
                $tempObj['prices'] = array();
                
                if ( $category['prices'] ){
                    foreach ($category['prices'] as $key2 => $price) {
                        $tempObj['prices'][] = array(
                            'id' => uniqid(),
                            'title' => $price['title'],
                            'price' => $price['price']
                        );
                        
                        update_post_meta($post_id, "category_{$key}_prices_{$key2}_id", uniqid());
                        update_post_meta($post_id, "category_{$key}_prices_{$key2}_title", $price['title']);
                        update_post_meta($post_id, "category_{$key}_prices_{$key2}_price", $price['price']);
                        
                        update_post_meta($post_id, "_category_{$key}_prices_{$key2}_id", 'field_5aad7fc8d5306');
                        update_post_meta($post_id, "_category_{$key}_prices_{$key2}_title", 'field_5a96db7a3a472');
                        update_post_meta($post_id, "_category_{$key}_prices_{$key2}_price", 'field_5a96647c3a471');
                        
                    }
                }
                
                update_post_meta($post_id, "category_{$key}_id", uniqid());
                update_post_meta($post_id, "category_{$key}_category", $category['name']);
                update_post_meta($post_id, "category_{$key}_desc", $category['desc']);
                
                update_post_meta($post_id, "_category_{$key}_id", '5a9ea8024e12f');
                update_post_meta($post_id, "_category_{$key}_category", 'field_5a96637d7985b');
                update_post_meta($post_id, "_category_{$key}_desc", 'field_5aada8e05270a');
                
                update_post_meta($post_id, "category_{$key}_prices", count($tempObj['prices']));
                update_post_meta($post_id, "_category_{$key}_prices", 'field_5a9664513a470');
                $categoriesObj[] = $tempObj;
            }
             // update_field( 'category', $categoriesObj, $post_id );
             update_post_meta($post_id, 'category', count($categoriesObj));
             update_post_meta($post_id, "_category", 'field_5a96631a7985a');
        }
        
        
        $what_to_bringObj = array();
        if ( $what_to_bring && count($what_to_bring)>0 ){
            
            foreach ($what_to_bring as $key => $item) {
                $what_to_bringObj[] = array(
                    'name' => $item['name'],
                    'id' => uniqid()
                );
            }
            update_field( 'what_to_bring', $what_to_bringObj, $post_id );
        }
        
        // create invitations to users
        if ( $type == 'private'){
            Expedition_Helper::createTourExpeditionersInvitations($expeditioners_goingObj, $post_id, $user_id);
        }
        
        $tour_code_prefix = get_field('tour_code_prefix', 'user_'.$user_id);
        $tour_code = $tour_code_prefix . base_convert($post_id, 10, 36);
        update_post_meta($post_id, 'tour_code', strtoupper($tour_code) );
        
        ob_start();
        var_dump_pre(
            $post_id,
            $categoriesObj,
            $request->get_params()
        );
        $content = ob_get_contents();
        ob_clean();
        
        wp_mail('estuardoeg@gmail.com', 'create tour', $content);
        
        
        //return ( array( 'type'=>$type, 'title'=> $title, 'main_color'=> $main_color, 'max_capacity'=> $max_capacity, 'desc'=> $desc,
        //    'itinerary'=> $itinerary, 'expeditioners_going'=> $expeditioners_going, 'what_to_bring'=> $what_to_bring) );
        
        return new WP_REST_Response(
                array(
                    'success'=>true, 
                    // 'ids' => $gallery_ids, 
                    'tour_id'=> $post_id,
                    //'message'=> 'Booking checked in',
                    // 'file_id' =>$file_id,
                    'tour'=> self::get_tour_info($post_id)
                    ), 
                200 );
        
    }
    
    /**
     * Appends a photo to a tour
     * 
     * @return \WP_REST_Response
     */
    public static function append_photo_to_tour( $request ){
        
        $tour_id = (int)$request->get_param('tour_id');
        $files_number = (int)$request->get_param('files_number');
        if ( !$files_number ){
            $files_number = 10;
        }
        
        $user_id = (int)$this->user['ID'];
        $tour = get_post($tour_id);
        
        if ( !$tour ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid tour', 'status'=>609 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        $owner_id = (int)get_field('owner', $tour_id);
        // is a privated tour
        if ( $owner_id != $user_id ){
            $response = ['code'=>'invalid_tour', 'message'=> "You have no permissions to handle the tour", 'status'=>649 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        // handle uploaded file
        if( empty( $_FILES ) || !isset($_FILES[ 'file_0']) ){
            $response = ['code'=>'missing_photo', 'message'=> "Missing attachment", 'data'=> ['status'=>600] ];
            return new WP_REST_Response($response, 200 );
        }
        
        ob_start();
        var_dump_pre(
            '$files_number', $files_number, $_FILES
        );
        $content = ob_get_contents();
        ob_clean();
        
        wp_mail('estuardoeg@gmail.com', 'create images', $content);
        
        
        $file_ids = array();
        for($i = 0; $i < $files_number; ++$i) {
            $file_id = Expedition_Helper::save_image_doc_from_url( false, 'file_'.time().$i.'.jpg', $_FILES[ "file_$i"], $tour_id, $this->user['ID'] );
            if ( is_wp_error($file_id) ){
                $response = ['code'=>'file_error', 'message'=> "Error saving the file", 'data'=> ['status'=>601, 'file'=>$file_id] ];
                return new WP_REST_Response($response, 200 );
            }else{
                $file_ids[] = (int)$file_id;
            }
        }
        
        update_field('gallery', $file_ids, $tour_id);
        
        return new WP_REST_Response(
                array(
                    'success'=>true, 
                    // 'ids' => $gallery_ids, 
                    // 'gallery'=> $gallery, 
                    'message'=> 'Images added',
                    'file_ids' => $file_ids,
                    'tour'=> self::get_tour_info($tour_id)
                    ), 
                200 );
        
    }
    
    
    /**
     * Return public tour Expeditioners list
     * 
     * @param type $tour_id
     * @return type
     */
    public function get_public_tour_expeditioners($tour_id){
        
        $bookings_list = (array) Expedition_Helper::getUserBookingByTour( $tour_id );
        if (is_array($bookings_list) && count($bookings_list) > 0){
            foreach ($bookings_list as $key => $booking) {
                //$booking->user = Expedition_Helper::getUser($booking->user_id, true);
                $bookings_list[$key] = self::format_booking($booking);
            }
        }
        
        return $bookings_list;
    }
    
    public function format_booking($booking){
        if ( $booking->user_id < 0 ){
            $expeditioners = (array)Expedition_Helper::getTourExpeditioners($booking->tour_id, $booking->user_id);
            if ( count( $expeditioners > 0 ) ){
                $expeditioner = $expeditioners[0];
                $booking->user = [
                    'full_name' => $expeditioner->first_name . ' ' . $expeditioner->last_name,
                    'first_name' => $expeditioner->first_name,
                    'last_name' => $expeditioner->last_name,
                    'role' => 'expeditioner',
                    // 'email' => $wp_user->get('user_email'),
                    // 'status' => (int)$status,
                    'ID' => $expeditioner->user_id,
                    // 'dob' => get_field( 'dob', 'user_'.$wp_user->ID ),
                    // 'gender' => get_field( 'gender', 'user_'.$wp_user->ID ),
                    'main_picture' => '',
                    'one_signal_id' => ''
                ];
            }
        }else{
            $booking->user = Expedition_Helper::getUserExpeditioner($booking->user_id);
        }
        
        return $booking;
    }
    
    
    /**
     * Return formatted tour data
     * 
     * @param type $pid
     * @return type
     */
    public function get_tour_info($pid){
        global $wpdb;
        $tour = get_fields( $pid );
        $tour['id'] = $pid;
        $tour['tour_support_link'] = (string)get_field('tour_support_link', 'options');
        $tour['title'] = (string) get_post_field('post_title', $pid);
        
        if ( !isset( $tour['request_first_name'] ) ){
            $tour['request_first_name'] = true;
        }
        if ( !isset( $tour['request_last_name'] ) ){
            $tour['request_last_name'] = true;
        }
        if ( !isset( $tour['request_age'] ) ){
            $tour['request_age'] = true;
        }
        if ( !isset( $tour['request_dpi_passport'] ) ){
            $tour['request_dpi_passport'] = true;
        }
        if ( !isset( $tour['request_phone'] ) ){
            $tour['request_phone'] = true;
        }
        if ( !isset( $tour['request_collegiate'] ) ){
            $tour['request_collegiate'] = true;
        }
        if ( !isset( $tour['request_emergency_contact'] ) ){
            $tour['request_emergency_contact'] = true;
        }
        if ( !isset( $tour['request_emergency_contact_number'] ) ){
            $tour['request_emergency_contact_number'] = true;
        }
        if ( !isset( $tour['hide_payment'] ) ){
            $tour['hide_payment'] = false;
        }
        
        
        $tour['departure_date_timestamp'] = strtotime($tour['departure_date']);
        $tour['return_date_timestamp'] = strtotime($tour['return_date']);
        $tour['itinerary'] = (array)$tour['itinerary'];
        $tour['what_to_bring'] = (array)$tour['what_to_bring'];
        $tour['cancellation_policy'] = (string)@$tour['cancellation_policy'];
        $tour['hide_seats_information'] = (bool)$tour['hide_seats_information'];
        $tour['tour_code'] = (string) get_post_meta($pid, 'tour_code', true);
        $tour['currency_symbol'] = (string) get_post_meta($pid, 'currency_symbol', true);
        $tour['credit_card_percents_fee'] = (float)get_field('credit_card_percents_fee', 'options');
        
        if ( !$tour['currency_symbol'] ){
            $tour['currency_symbol'] = 'Q';
        }
        
        
        if (is_array($tour['what_to_bring']) && count($tour['what_to_bring']) > 0 ){
            $records = Expedition_Helper::getArrayWhatToBringByTourAndUser( $pid, $this->user['ID'] );
            foreach ($tour['what_to_bring'] as $key => $item) {
                $tour['what_to_bring'][$key]['status'] = (bool)0;
                if ( $records ){
                    foreach ($records as $record) {
                        if ( $record->acf_id == $item['id'] ){
                            $tour['what_to_bring'][$key]['status'] = (bool)( (int)$record->status );
                        }
                    }
                }
            }
        }
        
        $going_status = Expedition_Helper::getTourInvitationByTourAndInvited( $pid, $this->user['ID']);
        $booking_status = Expedition_Helper::getUserBookingByUserAndTour( $pid, $this->user['ID']);
        $tour['going_status'] = $tour['type'] == 'private' ? ( !$going_status ? -1 : (int)$going_status->status ) : ( !$booking_status ? -1 : $booking_status->status );
        
        if ( $tour['type'] == 'public' && $booking_status && $booking_status->status == BOOKING_CONFIRMED ){
            $booking = array(
                'id' => $booking_status->id,
                'user_id' => $booking_status->user_id,
                'owner_id' => $booking_status->owner_id,
                'tour_id' => $booking_status->tour_id,
            );
            $image_base_64 = getQRImage( base64_encode(json_encode($booking)) , 400, true);
            $tour['qr_code'] = $image_base_64;
        }else{
            $tour['qr_code'] = null;
        }
        $tour['booking_seats'] = 0;
        if ( $booking_status ){
            $seats = 0;
            if ( isset($booking_status->tour_meta) ){
                $meta = json_decode($booking_status->tour_meta);
                foreach ($meta as $key => $book) {
                    $seats +=$book->qty;
                }
            }

            $tour['booking_seats'] = $seats;
            $tour['booking_amount'] = $booking_status->amount;
        }
        
        $tour['tags'] = wp_get_post_tags($pid);
        if (is_array($tour['tags']) && count($tour['tags'])>0){
            foreach ($tour['tags'] as $key => $tag) {
                unset($tag->taxonomy);
                unset($tag->filter);
                unset($tag->term_group);
                unset($tag->term_taxonomy_id);
                unset($tag->description);
                unset($tag->parent);
                unset($tag->count);
            }
        }
        
        if ( isset($tour['owner']) && $tour['owner'] ){
            $tour['non_profit'] = (bool)get_field('non_profit_organization', 'user_'.$tour['owner']);
            $tour['owner'] = Expedition_Helper::getUserExpeditioner($tour['owner']);
        }
        
        if ( $tour['gallery'] && is_array($tour['gallery']) && count($tour['gallery'])>0 ){
            foreach ($tour['gallery'] as $key => $image) {
                $tour['gallery'][$key] = Expedition_Helper::getAllImageSizes( $image['ID'] );
            }
        }else{
            $tour['gallery'] = array();
        }
        
        if ( isset($tour['expeditioners']) && is_array($tour['expeditioners']) && count($tour['expeditioners'])>0 ){
            foreach ($tour['expeditioners'] as $key => $uid) {
                $tour['expeditioners'][$key] = Expedition_Helper::getUserExpeditioner($uid);
                $attend = Expedition_Helper::getTourInvitationByTourAndInvited($pid, $uid);
                $attend_status = 0;
                if ( $attend ){
                    $attend_status = (int)$attend->status;
                }
                $tour['expeditioners'][$key]['attend'] = $attend_status;
            }
        }else{
            $tour['expeditioners'] = array();
            if ( $this->user['ID'] == $tour['owner']['ID'] ){
            //    $tour['expeditioners'] = self::get_public_tour_expeditioners( $pid );
            }
        }
        $max_capacity = 0;
        $bookings = $wpdb->get_results($wpdb->prepare("
                                        SELECT * FROM ".USER_BOOKINGS_TABLE."
                                        WHERE status != %d AND tour_id = %d;", BOOKING_REJECTED, $pid ));
        $max_capacities = array();
        if ( $bookings && count($bookings) >0 ){
            foreach ($bookings as $_booking) {
                $_meta = json_decode($_booking->tour_meta);
                if ( $_meta && count($_meta) > 0 ){
                    foreach ($_meta as $__meta) {
                        if ( !isset( $max_capacities[$__meta->id] ) ){
                            $max_capacities[$__meta->id] = 0;
                        }
                        $max_capacities[$__meta->id] += $__meta->qty;
                    }
                }
            }
        }
        
        if ( isset($tour['category']) && is_array($tour['category']) && count($tour['category'])>0 ){
            foreach ($tour['category'] as $key => $category) {
                if ( $category['prices'] && count($category['prices'])>0 ){
                    foreach ($category['prices'] as $key2 => $price) {
                        $tour['category'][$key]['prices'][$key2]['price'] = (double)$price['price'];//number_format($price['price'], 2);
                        $max_capacity += (int)$price['max_capacity'];
                        $_booked = isset($max_capacities[$price['id']]) ? $max_capacities[$price['id']] : 0;
                        $tour['category'][$key]['prices'][$key2]['available'] = (int)$price['max_capacity'] - $_booked;
                    }
                }
            }
        }
        $tour['max_capacity'] = (int)$max_capacity; //$tour['max_capacity'];
        if ( $tour['type'] == 'public' ){
            // max_capacity
            $valid_bookings = Expedition_Helper::getNumberOfValidUserBookingsByTour( $pid);
            $tour['valid_bookings'] = $valid_bookings;
        }
        
        return $tour;
    }
    
    /**
     * Updates what to bring item status
     * 
     * @param type $request
     * @return \WP_REST_Response
     */
    public function update_what_to_bring($request){
        
        $args = array(
            'user_id' => $request->get_param('user_id'),
            'tour_id' => $request->get_param('tour_id'),
            'acf_id' => $request->get_param('acf_id'),
            'status' => $request->get_param('status')
        );
        
        $success = Expedition_Helper::insertOrUpdateWhatToBring($args);
        if ( $success ){
            $response = ['success'=> true, 'object' => $success, 'tour' => self::get_tour_info($request->get_param('tour_id')) ];
        }else{
            $response = ['code'=>'record_error', 'objects'=>$args, 'message'=> 'Error updating record', 'status'=>671 ];
        }
        
        return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
    }
    
    /**
     * Updates what to bring item status
     * 
     * @param type $request
     * @return \WP_REST_Response
     */
    public function start_chat_of_public_tour($request){
        global $firebase;
        
        $tour_id = (int)$request->get_param('tour_id');
        $_user_id = (int)$request->get_param('user_id');
        $tour = get_post($tour_id);
        $owner_id = (int)get_field('owner', $tour_id );
        
        
        
        
        // does it exist?
        if ( !$tour ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid tour', 'status'=>609 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        // is a privated tour
        if ( get_field('type', $tour_id) != 'public' ){
            $response = ['code'=>'invalid_tour', 'message'=> 'Invalid type of tour', 'status'=>609 ];
            return new WP_REST_Response($response, 200 ); // best practice is code 401, but 200 will help to apps developer to parse response
        }
        
        $user_id = (int)$this->user['ID'];
        $recent_id = "tour_{$tour_id}_{$user_id}_{$owner_id}";
        $recent_for_owner = false;
        if ( $_user_id && $_user_id > 0 && $owner_id == $user_id && get_user_by('id', $_user_id) ){
            $user_id = $_user_id;
            $recent_id = "tour_{$tour_id}_{$owner_id}_{$user_id}";
            $recent_for_owner = true;
        }
        
        //$recent = FirebaseHelper::getSinglePathWithQuery("recents", "objectId", @$patient_recent['userId']);
        
        $recent = FirebaseHelper::getPath("recents/$recent_id");
        if ( $recent && isset($recent['id']) && $recent['id'] == $recent_id && isset($recent['users_names']) ){
            return $recent;
        }
        
        // var_dump($recent, $user_id, $owner_id, $recent_id, $recent_for_owner, $_user_id);
        // return array( 'boo'=>compact($recent, $user_id, $owner_id, $recent_id, $recent_for_owner, $_user_id) );
        
        
        $recent = Expedition_Helper::createPublicChatForTour( $tour_id, $user_id, $recent_for_owner );
        
        return $recent;
    }
    
    
    
}

add_action('rest_api_init', function () {
    $controller = new tours_API();
    $controller->register_routes();
});
