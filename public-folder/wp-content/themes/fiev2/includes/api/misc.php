<?php

//get_template_part('includes/firebase');

class misc_API extends base_API {

    public function register_routes() {
        $version = 'v1';
        //$namespce = 'wp/v' . $version;
        $namespce = $version;
        register_rest_route($namespce, '/catalogs', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_catalogs'),
            ),
                )
        );
        register_rest_route($namespce, '/tags', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_tags'),
            ),
                )
        );
        register_rest_route($namespce, '/billing/status/', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'enable_billing_fields'),
            ),
                )
        );
        register_rest_route($namespce, '/scheme', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'go_to_scheme'),
            ),
                )
        );
        
        register_rest_route($namespce, '/tos', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'tos'),
                'args' => array(
                    'user_language' => [
                        'required' => false,
			'type' => 'string',
			'description' => 'The language of the user',
			'enum' => array( 
                            'en',
                            'es'
                        ),
                    ],
                ),
            ),
                )
        );
        
        register_rest_route($namespce, '/test', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'test'),
                'args' => array(
                    
                ),
            ),
                )
        );
        
        
        register_rest_route($namespce, '/recommend', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'recommend'),
                'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                    'email' => [
                        'required' => true,
			'type' => 'string',
			'description' => "The email to the user to send the recommendation's email.",
                        'validate_callback' => 'is_email'
                    ],
                ),
            ),
                )
        );
        
        register_rest_route($namespce, '/test_subscription', array(
            array(
                'methods' => array('GET', 'POST'),
                'callback' => array($this, 'test_subscription'),
            ),
                )
        );
        
        register_rest_route($namespce, '/test_payment', array(
            array(
                'methods' => array('GET', 'POST'),
                'callback' => array($this, 'test_payment'),
            ),
                )
        );
    }
    
    public function test(){
        header('Content-Type: text/html; charset=utf-8');
    
        echo '<html>
        <head>
                <link href="https://app.expeditionguate.com/wp-content/themes/expeditionapp/maps/css/nanoscroller.css" rel="stylesheet">
                <script src="https://app.expeditionguate.com/wp-content/themes/expeditionapp/maps/js/jquery.js"></script>
                <script src="https://app.expeditionguate.com/wp-content/themes/expeditionapp/maps/js/jquery.mousewheel.min.js"></script>
                <script src="https://app.expeditionguate.com/wp-content/themes/expeditionapp/maps/js/jquery.nanoscroller.min.js"></script>
                <script src="https://app.expeditionguate.com/wp-content/themes/expeditionapp/maps/js/mapsvg.min.js"></script>
        </head>
        <body>
                <div id="mapsvg" style="max-width:600px;"></div>

                <script type="text/javascript">
                jQuery(document).ready(function(){
                jQuery("#mapsvg").mapSvg({width: 563.42047,height: 594.59253,colors:
                {baseDefault: "#000000",selected: 0,directory: "#fafafa",status: {},background: "#ffffff",base: "#ffffff",stroke: "#999999"},
                regions: {"GT-PE": {id: "GT-PE","id_no_spaces": "GT-PE",title: "Petén",fill: "rgba(140,61,140,1)",data: {}}},
                viewBox: [0,0,563.42047,594.59253],zoom: {on: false,limit: [0,10],delta: 2,buttons: {on: true,location: "right"},
                mousewheel: true},scroll: {on: false,limit: false,background: false,spacebar: false},tooltips: {mode: "off",on: false,priority: "local",
                position: "bottom-right"},popovers: {mode: "off",on: false,priority: "local",position: "top",centerOn: true,width: 300,maxWidth: 50,maxHeight: 50,resetViewboxOnClose: true,mobileFullscreen: true},gauge: {on: false,labels: {low: "low",high: "high"},colors: {lowRGB: {r: 85,g: 0,b: 0,a: 1},highRGB: {r: 238,g: 0,b: 0,a: 1},low: "#550000",high: "#ee0000",diffRGB: {r: 153,g: 0,b: 0,a: 0}},min: 0,max: false},source: "/wp-content/themes/expeditionapp/mapsvg/maps/geo-calibrated/guatemala.svg",title: "Guatemala",responsive: true,onClick: null,mouseOver: null,mouseOut: null});
                });
                </script>
        </body>
        </html>';
        exit();
    }
    
    
    
    /**
     * Returns the terms used as tags for tours
     */
    public function get_tags($request){
        
        $tags = get_tags(array('hide_empty'=>false));
        foreach ($tags as $key => $tag) {
            unset($tag->taxonomy);
            unset($tag->filter);
            unset($tag->term_group);
            unset($tag->term_taxonomy_id);
            $tag->image = get_field('image', "post_tag_$tag->term_id");
        }
        
        return new WP_REST_Response(array('success'=>true, 'tags'=>$tags, 'message'=> 'Success'), 200 );
        
    }
    
    /**
     * Returns if enable_billing_fields
     */
    public function enable_billing_fields($request){
        
        $enabled = get_field('enable_billing_fields', "options");
        
        return new WP_REST_Response(array('success'=>true, 'enabled'=>$enabled), 200 );
        
    }
    
    /**
     * Testing Cybersource Transaction Response Page
     */
    public function test_payment($request){
        
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting (E_ALL);

        $subscriptionID = $request->get_param('subscriptionID');
        
        // the cybersource sdk
        get_template_part('includes/cybersource-sdk/lib/CybsSoapClient');
        //require_once '../cybersource-sdk/lib/CybsSoapClient.php';
        
        $referenceCode = time();
        
        $firstName = 'Humberto Estuardo';
        $lastName = 'Estrada Gudiel';
        $address = 'Via 4 1-00, Edificio TEC 2';
        $email = 'estuardoeg@gmail.com';
        $nit = '4513594-0';
        
        $amount = 140;
        
        $client = new CybsSoapClient();
        $request = $client->createRequest($referenceCode);
        
        $ccAuthService = new stdClass();
        $ccAuthService->run = 'true';
        $request->ccAuthService = $ccAuthService;
        $ccCaptureService = new stdClass();
        $ccCaptureService->run = 'true';
        $request->ccCaptureService = $ccCaptureService;

        $billTo = new stdClass();
        $billTo->firstName = $firstName;
        $billTo->lastName = $lastName;
        $billTo->street1 = $address;
        $billTo->city = 'GT';
        $billTo->country = 'GT';
        $billTo->state = 'GT';
        $billTo->postalCode = '010014';
        $billTo->email = $email;
        $billTo->ipAddress = $_SERVER['REMOTE_ADDR'];
        $billTo->company = $nit;
        $request->billTo = $billTo;

        $purchaseTotals = new stdClass();
        $purchaseTotals->currency = 'GTQ';
        $purchaseTotals->grandTotalAmount= strval(number_format((float)$amount, 2, '.', ''));
        $request->purchaseTotals = $purchaseTotals;
        
        $recurringSubscriptionInfo = new stdClass();
        $recurringSubscriptionInfo->subscriptionID = $subscriptionID;
        $request->recurringSubscriptionInfo = $recurringSubscriptionInfo;
        $request->deviceFingerprintID = $request->get_param('deviceFingerprintID');
                
        $reply = $client->runTransaction($request);
        
        echo 'RESPONSE <br/>';
        var_dump_pre($reply);
        echo 'REQUEST <br/>';
        var_dump_pre($request);
        
    }
    
    /**
     * Testing Cybersource Transaction Response Page
     */
    public function test_subscription(){
        
        // the cybersource sdk
        get_template_part('includes/cybersource-sdk/lib/CybsSoapClient');
        //require_once '../cybersource-sdk/lib/CybsSoapClient.php';
        
        $referenceCode = time();
        
        $cardNumber = '4242-4242-4242-4242';
        $cardCVV = '032';
        $expirationYear = '2021';
        $expirationMonth = '04';
        
        $firstName = 'Humberto Estuardo';
        $lastName = 'Estrada Gudiel';
        $address = 'Via 4 1-00, Edificio TEC 2';
        $email = 'estuardoeg@gmail.com';
        $nit = '4513594-0';
        
        $amount = 220;
        
        $client = new CybsSoapClient();
        $request = $client->createRequest($referenceCode);
        
        $ccAuthService = new stdClass();
        $ccAuthService->run = 'true';
        $request->ccAuthService = $ccAuthService;
        $ccCaptureService = new stdClass();
        $ccCaptureService->run = 'true';
        $request->ccCaptureService = $ccCaptureService;
        

        $billTo = new stdClass();
        $billTo->firstName = $firstName;
        $billTo->lastName = $lastName;
        $billTo->street1 = $address;
        $billTo->city = 'GT';
        $billTo->country = 'GT';
        $billTo->state = 'GT';
        $billTo->postalCode = '010014';
        $billTo->email = $email;
        $billTo->ipAddress = $_SERVER['REMOTE_ADDR'];
        $billTo->company = $nit;
        $request->billTo = $billTo;

        $card = new stdClass();
        $card->accountNumber = $cardNumber;
        $card->expirationMonth = $expirationMonth;
        $card->expirationYear = $expirationYear;
        $card->cvNumber = $cardCVV;
        if ($cardNumber[0] == '4') {
          $card->cardType='001';
        } else {
          $card->cardType='002';
        }
        $request->card = $card;

        $purchaseTotals = new stdClass();
        $purchaseTotals->currency = 'GTQ';
        $purchaseTotals->grandTotalAmount= strval(number_format((float)$amount, 2, '.', ''));
        $request->purchaseTotals = $purchaseTotals;
        
        $recurringSubscriptionInfo = new stdClass();
        $recurringSubscriptionInfo->frequency = 'on-demand';
        $request->recurringSubscriptionInfo = $recurringSubscriptionInfo;
        
        $paySubscriptionCreateService = new stdClass();
        $paySubscriptionCreateService->run = 'true';
        $request->paySubscriptionCreateService = $paySubscriptionCreateService;
        
        $request->deviceFingerprintID = $request->get_param('deviceFingerprintID');
        
        $reply = $client->runTransaction($request);
        
        echo 'RESPONSE <br/>';
        var_dump_pre($reply);
        echo 'REQUEST <br/>';
        var_dump_pre($request);
        
    }
    
    
    /**
     * Returns catalogs like countries, blood types, etc...
     */
    public function get_catalogs(){
        
        $catalogs = array();
        $catalogs['countries'] = self::add_sort_field(get_field('countries', 'options')) ;
        
        return $catalogs;
        
    }
    
    public function add_sort_field( $array ){
        $return = array();
        
        foreach ($array as $key => $item) {
            $item['sort'] = $key+1;
            $return[] = $item;
        }
        
        return $return;
    }
    
    /**
     * Redirects to scheme passed for GET vars
     */
    public function go_to_scheme(){
        
        if ( isset($_GET['scheme']) && strstr($_GET['scheme'], "expedition://")!==false ){
            header('Location: '.$_GET['scheme'], true, 302);
        }
        
    }
    
    /**
     * Returns the terms of service stored in backend
     */
    public function tos($request){
        
        $user_language = $request->get_param('user_language');
        $valid_languages = array('en', 'es');
        if (!in_array($user_language, $valid_languages) ){
            $user_language = $valid_languages[0];
        }
       
        $tos = get_field("terms_of_service", 'options');
        
        if ( $tos ){
            return new WP_REST_Response(array('success'=>true, 'message'=> 'Success', 'tos'=> $tos), 200 );
        }
        
        return new WP_REST_Response(array('success'=>false, 'message'=> 'Invalid Terms of service required'), 200 );
        
    }
    
    /**
     * Sends an email to recommend Expedition to his friends and patients
     */
    public function recommend($request){
        
        $data = self::get_token_data();
        $doctor_id = $data->ID;
        $fuser = FirebaseHelper::getSinglePathWithQuery("User", "objectId", $data->ID);
        $fullname = (string)@$fuser['fullname'];
        $thumbnail = (string)@$fuser['thumbnail'];
        
        
        $email = $request->get_param('email');
        $user_language = get_user_meta($doctor_id, 'user_language', true);
        
        $subject = get_field("recommend_email_subject_$user_language", 'options');
        $message = get_field("recommend_email_$user_language", 'options');
        
        $subject = str_replace(array('{doctor_name}', '{doctor_code}', '{doctor_thumbnail}'), array($fullname, $data->ID, $thumbnail), $subject);
        $message = str_replace(array('{doctor_name}', '{doctor_code}', '{doctor_thumbnail}'), array($fullname, $data->ID, $thumbnail), $message);
        
        
        
        $enable_field = "enable_recommend_notification";
        $enable = get_field($enable_field, 'options');
        if ( !$enable ){
            Expedition_Helper::logMessage( "Recommend email disabled, not sent to $email", 'emails_sent.txt');
        }else{
            $sent = Notifications::send($email, $subject, $message);
            if( !$sent ){
                Expedition_Helper::logMessage( "Recommend email failed to $email", 'emails_sent.txt');
                return new WP_REST_Response(array('success'=>false, 'message'=> 'The email delivery failed'), 200 );
            }else{
                Expedition_Helper::logMessage( "Recommend email sent to $email", 'emails_sent.txt');
            }
        }
        
        return new WP_REST_Response(array('success'=>true, 'message'=> 'Recommendation sent succesfully!'), 200 );
        
    }
    
    

}

add_action('rest_api_init', function () {
    $controller = new misc_API();
    $controller->register_routes();
});
