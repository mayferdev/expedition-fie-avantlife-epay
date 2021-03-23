<?php

include_once 'simple_html_dom.php';

/**
 * Class to handle calls to CyberSource, and helpers related with
 * 
 */
class ExpeditionCyberSource {

    function __construct() {
        
        //$this->create_token_endpoint = 'https://secureacceptance.cybersource.com/silent/token/create';
        $this->create_token_endpoint = 'https://testsecureacceptance.cybersource.com/silent/token/create';
        /*
         * Supports:
         * create_payment_token
         */


        //$this->update_token_endpoint = 'https://secureacceptance.cybersource.com/silent/token/update';
        $this->update_token_endpoint = 'https://testsecureacceptance.cybersource.com/silent/token/update';
        /*
         * Supports:
         * update_payment_token
         */
        
        // CHECK ENVIRONMENT BEFORE SET AUTH DATA
        if ( get_field('payments_environment', 'options') == 'development' ){
            // $this->process_transaction_endpoint = 'https://secureacceptance.cybersource.com/silent/pay';
            $this->process_transaction_endpoint = CYBERSOURCE_API_URL;
            /*
             * Supports:
             * authorization
             * authorization,create_payment_token
             * authorization,update_payment_token
             * sale
             * sale,create_payment_token
             * sale,update_payment_token
             */

            $this->secret_key = CYBERSOURCE_SECRET_KEY;
            $this->access_key = CYBERSOURCE_ACCESS_KEY;
            $this->profile_id = CYBERSOURCE_PROFILE_ID;
            $this->merchant_id = CYBERSOURCE_MERCHANT_ID;
        }elseif ( get_field('payments_environment', 'options') == 'production' ){
            $this->process_transaction_endpoint = CYBERSOURCE_API_URL_PRODUCTION;
            $this->secret_key = CYBERSOURCE_SECRET_KEY_PRODUCTION;
            $this->access_key = CYBERSOURCE_ACCESS_KEY_PRODUCTION;
            $this->profile_id = CYBERSOURCE_PROFILE_ID_PRODUCTION;
            $this->merchant_id = CYBERSOURCE_MERCHANT_ID_PRODUCTION;
        }
        
        
        
    }
    
    /**
     * Create a profile (an user susbscription is a profile) in Cybersource and 
     * returns the Cybersource response
     * 
     * @param FirebaseUser $fuser
     * @param string $card_number
     * @param string $card_expiry_date
     * @param string $card_cvn
     * @param string $card_holder_name
     * @return array
     */
    public function createToken($fuser, $card_number, $card_expiry_date, $card_cvn, $card_holder_name, $session_id, $return_fields = false) {
        
        $secret_key = $this->secret_key;
        $access_key = $this->access_key;
        $profile_id = $this->profile_id;
        $merchant_id = $this->merchant_id;
        
        
        /*$fields['transaction_uuid'] = uniqid(); //'02815b4f08e56882751a043839b7b481'; 
        // Unique merchant-generated identifier. Include with the access_key 
        // field for each transaction. This identifier must be unique for each transaction. 
        // This field is used to check for duplicate transaction attempts
        $fields['signed_date_time'] = gmdate("Y-m-d\TH:i:s\Z"); //'2013-07-11T15:16:54Z';
        $fields['access_key'] = $access_key; //the access key we configured in secure acceptance profile
        $fields['profile_id'] = $profile_id; // Identifies the profile to use with each transaction
        $fields['signed_field_names'] =''; //'access_key,profile_id,transaction_uuid,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,reference_number,amount,currency,bill_to_address_city,bill_to_address_country,bill_to_address_line1,bill_to_address_postal_code,bill_to_address_state,bill_to_company_name,bill_to_email,bill_to_forename,bill_to_phone,bill_to_surname,customer_ip_address,consumer_id,tax_amount,card_type,card_number,card_expiry_date,payment_method';
        $fields['signed_field_names'] = 'merchant_id,access_key,profile_id,transaction_uuid,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,reference_number,amount,currency,bill_city,bill_to_address_city,bill_to_address_country,bill_address1,bill_to_address_line1,bill_to_address_line2,bill_to_address_postal_code,bill_to_address_state,bill_to_company_name,bill_to_email,bill_to_forename,bill_to_phone,bill_to_surname,ship_to_address_city,ship_to_address_country,ship_to_address_line1,ship_to_address_line2,ship_to_address_postal_code,ship_to_address_state,ship_to_forename,ship_to_phone,ship_to_surname,customer_ip_address,consumer_id,tax_amount,card_type,card_number,card_expiry_date,card_cvn,payment_method,device_fingerprint_id';
        // Include all request fields in the signed_field_names field with 
        // the exception of the card_number field. 
        // The signed_field_names field is used to generate a signature 
        // that is used to verify the content of the transaction 
        // to prevent data tampering
        $fields['unsigned_field_names'] = '';
        $fields['locale'] = 'en-us';
        $fields['transaction_type'] = 'authorization,create_payment_token'; // The type of transaction, 
        $fields['reference_number'] = '1234567890';
        $fields['amount'] = '0'; //Total amount for the order, I think is optional creating subscription
        $fields['tax_amount'] = '0';
        $fields['currency'] = 'GTQ';//'usd'; // Currency used for the orderr
        
        
        $fields['bill_city'] = 'Mountain View';
        $fields['bill_to_address_city'] = 'Mountain View';
        $fields['bill_to_address_country'] = 'US';
        $fields['bill_address1'] = '1 My Apartment';
        $fields['bill_to_address_line1'] = '1 My Apartment';
        $fields['bill_to_address_line2'] = '';
        $fields['bill_to_address_postal_code'] = '94043';
        $fields['bill_to_address_state'] = 'CA';
        $fields['bill_to_company_name'] = '';
        $fields['bill_to_email'] = 'joesmith@example.com';
        $fields['bill_to_forename'] = 'Joe';
        $fields['bill_to_phone'] = '55802600';
        $fields['bill_to_surname'] = 'Smith';
        
        $fields['ship_to_address_city'] = '';
        $fields['ship_to_address_country'] = '';
        $fields['ship_to_address_line1'] = '';
        $fields['ship_to_address_line2'] = '';
        $fields['ship_to_address_postal_code'] = '';
        $fields['ship_to_address_state'] = '';
        $fields['ship_to_phone'] = '';
        $fields['ship_to_forename'] = '';
        $fields['ship_to_surname'] = '';
        
        
        $fields['customer_ip_address'] = '190.106.211.7';
        $fields['card_type'] = '001'; // credit card type, take a look to this->getCCType()
        $fields['card_number'] = '4111111111111111'; // Credit card number, without spaces or hyphens
        $fields['card_expiry_date'] = '12-2022'; // Card expiration date, format MM-YYYY
        $fields['card_cvn'] = '005'; // Card verification number
        $fields['payment_method'] = 'card';
        $fields['consumer_id'] = '1'; // Identifier for the customer's account. 
        // This field is defined when you create a subscription.
        $fields['merchant_id'] = $merchant_id;
        $fields['device_fingerprint_id'] = '';
        $fields['signed_field_names'] = $this->getSignedFieldNames($fields);
        $fields['signature'] = $this->sign($fields, $secret_key);   */    
        
        
        
        $fields = [];
        $fields['reference_number']= (string)time();
        $fields['transaction_type']='authorization,create_payment_token';
        $fields['currency']='GTQ';
        $fields['amount']='0.00';
        $fields['locale']='en';
        $fields['access_key'] = $access_key;
        $fields['profile_id'] = $profile_id;
        $fields['transaction_uuid'] = uniqid();
        $fields['signed_date_time'] = gmdate("Y-m-d\TH:i:s\Z");
        $fields['signed_field_names'] ='';
        $fields['unsigned_field_names']='';
        $fields['payment_method']='card';
        
        
        $fields['card_type']= $this->getCCType($card_number);// '001';
        $fields['card_number']= $card_number;//'4111111111111111';
        $fields['card_expiry_date']= $card_expiry_date; //'12-2022';
        $fields['card_cvn']= $card_cvn; //'005';
        $fields['bill_to_forename']= (string)$card_holder_name; //'Joe';
        $fields['bill_to_surname']= (string)$card_holder_name; //'Smith';
        
        
        $fields['bill_to_email']= (string)$fuser['email']; //'joesmith@example.com';
        $fields['bill_to_phone']= (string)$fuser['phone'];
        $fields['bill_to_address_line1']= (string)$fuser['address']; //'1 My Apartment';
        $fields['bill_to_address_city']= (string)$fuser['city']; //'Mountain View';
        $fields['bill_to_address_postal_code']= (string)$fuser['zip']; //'94043';
        $fields['bill_to_address_state']= ''; //;'CA';
        $country = @Expedition_Helper::getCountryById(@$fuser['country']);
        $country_code = @$country['iso_code'];
        $fields['bill_to_address_country']= $country_code ? $country_code : '';  //'US';
        // $fields['decisionManager_enabled']='false';
        $fields['customer_ip_address'] = Expedition_Helper::get_client_ip();
        $fields['device_fingerprint_id'] = (string)$session_id;
        
        $fields['merchant_id'] = $merchant_id;
        
        $fields['signed_field_names'] = $this->getSignedFieldNames($fields);
        $fields['signature'] = $this->sign($fields, $secret_key);       
        
        if ($return_fields){
            if (strlen($card_number)> 4 ){
                $fields['card_number'] = substr($card_number, 0, 4) . str_repeat('X', strlen($card_number) - 8) . substr($card_number, -4);
            }
            return $fields;
        }
        
        /**********************************************************************/
        
        
        $response_fields = array();
        $response = wp_remote_post($this->process_transaction_endpoint, array(
                'method' => 'POST',
                'body' => $fields,
                'timeout' => 45,
            )
        );
        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            echo "Something went wrong: $error_message";
        } else {
            //print_r($response['body']);
            //print_r($response['response']);

            $html = str_get_html( $response['body'] );
            $response_fields = array();

            foreach($html->find('input') as $input){
                if ( $input->type == 'hidden' ){
                    $response_fields[$input->name] = $input->value;
                    //echo "input name {$input->name} value {$input->value} \n\n";
                }
            }
            ksort($response_fields);
            // var_dump( $response_fields );
        }
        
        if ( count($response_fields) < 1 ){
            return $response['body'];
        }
        
        return $response_fields;
        
        
        
        header( 'Content-Type: text/html; charset=utf-8' );
        if ( $showForm ):
            echo '<form id="cb_payment_confirmation" action="'.$this->process_transaction_endpoint.'" method="POST"/>';
            foreach ($fields as $key => $value) {
                echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
            }
            echo '<input type="submit" class="button-alt" id="submit_cb_payment_form" value="Create token" /> ';
            echo '</form>';
        endif;
        
        
        //$fields['date_of_birth']='19840628'; // Optional, Date of birth of the customer. Use the format: YYYYMMDD
        //$fields['consumer_id']='US'; //Identifier for the customer's account. This field is defined when you create a subscription
        //ACCEPT - Successful transaction. Reason codes 100 and 110
        //DECLINE - Transaction was declined. See reason codes 102, 200, 202, 203, 204, 205, 207, 208, 210, 211, 221, 222, 230, 231, 232, 233, 234, 236, 240, 475, 476, and 481.
        //REVIEW - Authorization was declined; however, the capture may still be possible. Review payment details. See reason codes 200, 201, 230, and 520
        //ERROR - Access denied, page not found, or internal server error. See reason codes 102, 104, and 150.
        //CANCEL - The customer did not accept the service fee conditions., The customer cancelled the transaction.  
    }
    
    
    /**
     * Make a change to the credit card stored in the token subscription profile,
     * with the passed $amount
     * 
     * @param string $token
     * @param string/number $amount
     * @param string/int $transaction_id
     * @return array
     */
    public function makePaymentByToken($token, $amount, $transaction_id, $patient_id ) {
        
        $secret_key = $this->secret_key;
        $access_key = $this->access_key;
        $profile_id = $this->profile_id;
        $merchant_id = $this->merchant_id;
        
        $fields = [];
        $fields['reference_number']= (string)$transaction_id;
        $fields['transaction_type']='sale';
        $fields['currency']='GTQ';
        $fields['amount']=(string)$amount;
        $fields['locale']='en';
        $fields['access_key'] = $access_key;
        $fields['profile_id'] = $profile_id;
        $fields['transaction_uuid'] = uniqid();
        $fields['signed_date_time'] = gmdate("Y-m-d\TH:i:s\Z");
        $fields['signed_field_names'] ='';
        $fields['unsigned_field_names']='';
        $fields['payment_token'] = $token;
        
        $fuser = FirebaseHelper::getSinglePathWithQuery("User", "objectId", $patient_id);
        $fields['customer_ip_address'] = isset($fuser['ipAddress']) && $fuser['ipAddress']  ? $fuser['ipAddress'] : '';
        
        // $fields['decisionManager_enabled']='false';
        $fields['merchant_id'] = $merchant_id;
        
        $fields['signed_field_names'] = $this->getSignedFieldNames($fields);
        $fields['signature'] = $this->sign($fields, $secret_key);       
        
        
        /**********************************************************************/
        
        $response_fields = array();
        $response = wp_remote_post($this->process_transaction_endpoint, array(
                'method' => 'POST',
                'body' => $fields,
                'timeout' => 45,
            )
        );
        
        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            echo "Something went wrong: $error_message";
        } else {
            
            $html = str_get_html( $response['body'] );
            $response_fields = array();

            foreach($html->find('input') as $input){
                if ( $input->type == 'hidden' ){
                    $response_fields[$input->name] = $input->value;
                    //echo "input name {$input->name} value {$input->value} \n\n";
                }
            }
            ksort($response_fields);
        }
        
        if ( count($response_fields) < 1 ){
            return $response['body'];
        }
        
        return $response_fields;
    }
    
    
    
    /**
     * Return the value to send in signed_field_names for a CyberSource request
     * 
     * @param type $fields
     * @return type
     */
    public function getSignedFieldNames($fields){
        $signed_field_names = array();
        foreach ($fields as $key => $field) {
            $signed_field_names[] = $key;
        }
        return join(',', $signed_field_names);
    }

    
    /**
     * Returns the proper code depending the passed $cardnum
     * 
     * @param string $cardnum the credit card number
     * @return string the code to send to CyberSource as credit card type
     */
    public function getCCType($cardnum) {

        /* Visa */
        if (preg_match("/^4(\d{12}|\d{15})$/", $cardnum)) {
            $type = '001';

            /* MasterCard */
        } else if (preg_match("/^5[1-5]\d{14}$/", $cardnum)) {
            $type = '002';

            /* American Express */
        } else if (preg_match("/^3[47]\d{13}$/", $cardnum)) {
            $type = '003';

            /* Discover */
        } else if (preg_match("/^6011\d{12}$/", $cardnum)) {
            $type = '004'; // Discover

            /* Diners Club */
        } else if (preg_match("/^[300-305]\d{11}$/", $cardnum) ||
                preg_match("/^3[68]\d{12}$/", $cardnum)) {
            $type = '005';

            /* EnRoute */
        } else if (preg_match("/^2(014|149)\d{11}$/", $cardnum)) {
            $type = '014';

            /* JCB */
        } else if (preg_match("/^3\d{15}$/", $cardnum) ||
                preg_match("/^(2131|1800)\d{11}$/", $cardnum)) {
            $type = '007';

            /* Maestro */
        } else if (preg_match("/^(?:5020|6\\d{3})\\d{12}$/", $cardnum)) {
            $type = '024';

            /* Visa Electron */
        } else if (preg_match("/^4(17500|917[0-9][0-9]|913[0-9][0-9]|508[0-9][0-9]|844[0-9][0-9])\d{10}$/", $cardnum)) {
            $type = '033';

            /* Laser */
        } else if (preg_match("/^(6304|670[69]|6771)[0-9]{12,15}$/", $cardnum)) {
            $type = '035';

            /* Carte Blanche */
        } else if (preg_match("/^389[0-9]{11}$/", $cardnum)) {
            $type = '006';

            /* Dankort */
        } else if (preg_match("/^5019\d{12}$/", $cardnum)) {
            $type = '034';
        } else {
            $type = '';
        }


        return $type;
    }
    
    /**
     * Returns the signature of fields to send to CyberSource
     * 
     * @param array $params the array of params/fields to send
     * @param type $secret_key
     * @return type
     */
    public function sign($params, $secret_key) {
        return $this->signData($this->buildDataToSign($params), $secret_key);
    }

    public function signData($data, $secretKey) {
        return base64_encode(hash_hmac('sha256', $data, $secretKey, true));
    }

    public function buildDataToSign($params) {
        $signedFieldNames = explode(",", $params["signed_field_names"]);
        foreach ($signedFieldNames as $field) {
            $dataToSign[] = $field . "=" . $params[$field];
        }
        return $this->commaSeparate($dataToSign);
    }
    
    public function commaSeparate($dataToSign) {
        return implode(",", $dataToSign);
    }

}
