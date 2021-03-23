<?php

class payments_API extends base_API {

    public function register_routes() {
        
        $namespce = DREAMFOOTAPP_PRODUCTION_API_VERSION;
        
        register_rest_route($namespce, '/payments/pagalo', array(
            array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array($this, 'pagalo'),
                //'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                ),
            ),
                )
        );
        
        register_rest_route($namespce, '/payments/pagalo/hacercargo', array(
            array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array($this, 'pagaloHacerCargo'),
                //'permission_callback' => array( $this, 'check_token' ),
                'args' => array(
                ),
            ),
                )
        );
        
        
    }
    
    /**
     * Prints the form for pay subscriptions, songs and jams
     * 
     * @param type $request
     * @return \WP_REST_Response
     */
    public function pagalo($request) {
        global $_request;
        $_request = $request;
        
        $api_url = 'https://sandbox.pagalocard.com/api/v1';
        $token = 'P5oHlmBhgPeBkQqQvNPy';
        $public_key = '7Ru2LKj8mPcnbLecZKVIZzixStd54ftlqbGq6RKH';
        $secret_key = 'ipkAbKzU3QPTQrIQ9YJBEBPiZ2tb7VrqSIhHwpBO';
        $id_en_empresa = 'R237155663';
        
        $empresa = array(
            'key_secret'=> $secret_key,
            'key_public'=> $public_key,
            'idenEmpresa'=> $id_en_empresa
        );
        $cliente = array(
           'codigo'=> 'c001',
           'firstName'=> 'Juan',
           'lastName'=> 'Pérez',
           'phone'=> '55555555',
           'street1'=> '12 Avenida Zona 15',
           'country'=> 'Guatemala',
           'city'=> 'Guatemala',
           'state'=> 'Guatemala',
           'postalCode'=> '01015',
           'email'=> 'estuardoeg@gmail.com',
           'ipAddress'=> '181.174.73.189',
           'Total'=> 44.00,
           'currency'=> 'GTQ',
           'fecha_transaccion'=> '2018-12-07 06:25:26',
           'deviceFingerprintID'=> 'YoF3WTvwUb4BWXptmU'
        );
        $detalle[] = array(
           'id_producto'=> 'V01',
           'cantidad'=> "4",
           'tipo'=>'servicio',
           'nombre'=> 'Validar Tarjeta',
           'precio'=> "11",
           'Subtotal'=> "44"
        );
        $tarjetaPagalo = array(
           'nameCard'=> 'Juan Pérez',
           'accountNumber'=> '4111111111111111',
           'expirationMonth'=> '09',
           'expirationYear'=> '2020',
           'CVVCard'=> '010'
        );
        
        if ( isset($_REQUEST['cobro']) ){
            $tarjetaPagalo = array('tokenTarjeta'=>'BgKknVkTZCo2CQgY0BenCLcJSfDXpupaan7OzqIKIqLqKmHGr2w1cAOjKcZu');
            //$tarjetaPagalo = array('tokenTarjeta'=>'5441524439296620104012');
            $url = "{$api_url}/boveda/transaccionC/{$token}";
            $params = array(
                        'empresa'=> json_encode($empresa),
                        'cliente'=> json_encode($cliente),
                        'tarjetaPagalo'=> json_encode($tarjetaPagalo),
                        'detalle'=> json_encode($detalle),
                        );
            
            $response = wp_remote_post($url, array(
                    'method' => 'POST',
                    'body' => $params,
                    'timeout' => 45,
                )
            );
        }else{
            $url = "{$api_url}/boveda/nuevo/{$token}";
            $params = array(
                        'empresa'=> json_encode($empresa),
                        'cliente'=> json_encode($cliente),
                        'tarjetaPagalo'=> json_encode($tarjetaPagalo),
                        );
            $response = wp_remote_post($url, array(
                    'method' => 'POST',
                    'body' => $params,
                    'timeout' => 45,
                )
            );
        }
        
        
        var_dump_pre($response['body']);
        exit();
        
    }
    
    
    public function pagaloHacerCargo(){
        
        // this.profile_id = 'A5480292-02FF-4180-9C55-E39777C7301B';
        // this.access_key = '7f82af92c9f439f8b7a36700c4d93598';
        // this.secret_key = 'be9ebd6705354834a4ad4c186aafc10270d9cba7e07648dd911873702de34b1ad8089840210141b8bc1df6936cbf7fd04f5152cd114c472ab0aed9d1f2b452bc3f8360bf7509408897aedf685080c068b9325b1347b84dc7b43d13c3f313b18377002780fcd348f5aa85d4997bd2869f141122e3b46d473992cd397dd318a2e0';
        // this.merchant_id = 'visanetgt_canizenvia';
        
        $secret_key = 'be9ebd6705354834a4ad4c186aafc10270d9cba7e07648dd911873702de34b1ad8089840210141b8bc1df6936cbf7fd04f5152cd114c472ab0aed9d1f2b452bc3f8360bf7509408897aedf685080c068b9325b1347b84dc7b43d13c3f313b18377002780fcd348f5aa85d4997bd2869f141122e3b46d473992cd397dd318a2e0';
        $access_key = '7f82af92c9f439f8b7a36700c4d93598';
        $profile_id = 'A5480292-02FF-4180-9C55-E39777C7301B';
        $merchant_id = 'visanetgt_canizenvia';
        
        $fields = [];
        $fields['reference_number']= (string)12341234;
        $fields['transaction_type']= 'sale';
        $fields['currency']='GTQ';
        $fields['amount']=(string)150.30;
        $fields['locale']='en';
        $fields['access_key'] = $access_key;
        $fields['profile_id'] = $profile_id;
        $fields['transaction_uuid'] = uniqid();
        $fields['signed_date_time'] = gmdate("Y-m-d\TH:i:s\Z");
        $fields['signed_field_names'] ='';
        $fields['unsigned_field_names']='';
        $fields['payment_token'] = 'BgKknVkTZCo2CQgY0BenCLcJSfDXpupaan7OzqIKIqLqKmHGr2w1cAOjKcZu';
        $fields['payment_token'] = '5441524439296620104012';
        
        $fields['customer_ip_address'] = '181.174.73.189';
        // $fields['decisionManager_enabled']='false';
        $fields['merchant_id'] = $merchant_id;
        
        $fields['signed_field_names'] = $this->getSignedFieldNames($fields);
        $fields['signature'] = $this->sign($fields, $secret_key);       
        
        
        /**********************************************************************/
            echo json_encode($fields);
            exit();
            
        $response_fields = array();
        $response = wp_remote_post('https://testsecureacceptance.cybersource.com/silent/pay', array(
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
    
    
}
///phpinfo();

add_action('rest_api_init', function () {
    $controller = new payments_API();
    $controller->register_routes();
});
