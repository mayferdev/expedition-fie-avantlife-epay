<?php

require 'vendor/autoload.php';
use Kreait\Firebase\Configuration;
use Kreait\Firebase\Firebase;
use Kreait\Firebase\Query;
use \Firebase\JWT\JWT;

global $firebase, $service_account_email, $private_key;

$config_file = __DIR__.'/expeditionapp-firebase-adminsdk-wzem4-2e1f3bd908.json';
$config = new Configuration();
$config->setAuthConfigFile($config_file);
$firebase = new Firebase('https://expeditionapp.firebaseio.com', $config);

//storageRef

// JSON WEB TOKENS
$config_json = json_decode(file_get_contents($config_file));
$service_account_email = $config_json->client_email;
$private_key = $config_json->private_key;



class FirebaseHelper{
    
    /**
     * Make a request to Firebase and get the object at passed $path
     * 
     * @global Firebase $firebase
     * @param type $path the path to looking for
     * @param type $queried_key the key to looking for
     * @param type $queried_value the value to match
     * @return false in error or an array with the list of matched objects,
     * to get just one object use getSinglePathWithQuery
     */
    public static function getPath($path){
        global $firebase;
        try{
            $query_result = $firebase->get($path);
            return $query_result;
        } catch (Exception $ex) {
            return false;
        }
    }
    
    /**
     * Make a request to Firebase and get the object at passed $path
     * 
     * @global Firebase $firebase
     * @param type $path the path to looking for
     * @param type $queried_key the key to looking for
     * @param type $queried_value the value to match
     * @return false in error or an array with the list of matched objects,
     * to get just one object use getSinglePathWithQuery
     */
    public static function getPathWithQuery($path, $queried_key, $queried_value){
        global $firebase;
        try{
            $query = new Query();
            $query->orderByChildKey($queried_key);
            $query->equalTo($queried_value);
            $query_result = $firebase->query($path, $query);
            return $query_result;
        } catch (Exception $ex) {
            return [];
        }
    }
    
    /**
     * Instance of getPathWithQuery, return just the first result
     * 
     * @param type $path the path to looking for
     * @param type $queried_key the key to looking for
     * @param type $queried_value the value to match
     * @return false in error or an array with the object
     */
    public static function getSinglePathWithQuery($path, $queried_key, $queried_value){
        try{
            $query_result = FirebaseHelper::getPathWithQuery($path, $queried_key, $queried_value);
            $query_result_single = is_array($query_result) ? array_values($query_result)[0] : false;
            return $query_result_single;
        } catch (Exception $ex) {
            return [];
        }
    }
    
    
    /**
     * Return list of consultations unanswered by doctor
     * 
     * @global Firebase $firebase
     * @return false in error or an array with the list of matched objects,
     * to get just one object use getSinglePathWithQuery
     */
    public static function getUnAnsweredConsultations(){
        global $firebase;
        try{
            
            $query = new Query();
            $query->orderByChildKey('doctorAnswered');
            $query->equalTo(false);
            
            $query_result = $firebase->query('Recent', $query);
            
            return $query_result;
        } catch (Exception $ex) {
            return [];
        }
    }
    
    /**
     * Return list of open consultations with status 0
     * 
     * @global Firebase $firebase
     * @return false in error or an array with the list of matched objects,
     * to get just one object use getSinglePathWithQuery
     */
    public static function getOpenConsultations(){
        global $firebase;
        try{
            
            $query = new Query();
            $query->orderByChildKey('status');
            $query->equalTo(0);
            
            $query_result = $firebase->query('Recent', $query);
            
            return $query_result;
        } catch (Exception $ex) {
            return [];
        }
    }
    
    
    /************** REMOVE NEXT ******************/
    /**
     * Make a request to Firebase and get the object at passed $path
     * 
     * @global Firebase $firebase
     * @param type $path the path to looking for
     * @param type $queried_key the key to looking for
     * @param type $queried_value the value to match
     * @return false in error or an array with the list of matched objects,
     * to get just one object use getSinglePathWithQuery
     */
    public static function getConsultationsWithDates($path, $queried_key, $queried_value){
        global $firebase;
        try{
            
            $query = new Query();
            $query->orderByChildKey('createdAt');
            $query->startAt(1510006315278);
            $query->endAt( 1510006362430 );
            //$query->equalTo('1234673');
            
            //$query->orderByChildKey($queried_key);
            //$query->equalTo($queried_value);
            
            $query_result = $firebase->query('Message/146', $query);
            
            return $query_result;
        } catch (Exception $ex) {
            return [];
        }
    }
    
    public static function decode_token($token){
        global $private_key;

        try {
            $return = JWT::decode($token, $private_key, array("HS256"));
        } catch (Exception $exc) {
            //var_dump($exc->getMessage());
            //echo $exc->getTraceAsString();
            return array( 'error'=>true, 'code'=>$exc->getCode(), 'message'=> $exc->getMessage());
        }
        return $return;
    }
    
    public static function create_token($user, $longlive = false) {
        global $service_account_email, $private_key;

        $longliveExtraTimeFactor = $longlive ? (24*30*6) : 1;
        $algorithm = $longlive ? "HS256": "RS256";
        
        $now_seconds = time();
        $payload = array(
            "iss" => $service_account_email,
            "sub" => $service_account_email,
            "aud" => "https://identitytoolkit.googleapis.com/google.identity.identitytoolkit.v1.IdentityToolkit",
            "iat" => $now_seconds,
            "exp" => $now_seconds + (60 * 60 * $longliveExtraTimeFactor), // Maximum expiration time is one hour
            "uid" => $user['ID'],
            "data" => $user
        );
        
        // HS256 doen't work with firebase
        //return JWT::encode($payload, $private_key, "HS256");
        return JWT::encode($payload, $private_key, $algorithm);
    }

    
    function create_hs256_auth_token($user) {
        global $service_account_email, $private_key;

        $now_seconds = time();
        $payload = array(
            "iss" => $service_account_email,
            "sub" => $service_account_email,
            "aud" => "https://identitytoolkit.googleapis.com/google.identity.identitytoolkit.v1.IdentityToolkit",
            "iat" => $now_seconds,
            "exp" => $now_seconds + (60 * 60 * 24 * 30), // Maximum expiration time is one hour
            "uid" => $user['ID'],
            "data" => $user
        );

        //$token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJmaXJlYmFzZS1hZG1pbnNkay1tdG1qekBkb2N0b3JmeS1kYjRhMC5pYW0uZ3NlcnZpY2VhY2NvdW50LmNvbSIsInN1YiI6ImZpcmViYXNlLWFkbWluc2RrLW10bWp6QGRvY3RvcmZ5LWRiNGEwLmlhbS5nc2VydmljZWFjY291bnQuY29tIiwiYXVkIjoiaHR0cHM6XC9cL2lkZW50aXR5dG9vbGtpdC5nb29nbGVhcGlzLmNvbVwvZ29vZ2xlLmlkZW50aXR5LmlkZW50aXR5dG9vbGtpdC52MS5JZGVudGl0eVRvb2xraXQiLCJpYXQiOjE0OTI1MTE1OTAsImV4cCI6MTQ5MjUxNTE5MCwidWlkIjozLCJkYXRhIjp7InJvbGUiOiJzdWJzY3JpYmVyIiwiZW1haWwiOiJlc3R1YXJkb2VnQGdtYWlsLmNvbSIsIklEIjozfX0.aQ1kSMX3okksjKEdV9D71u_EjgLslpXMimwFXmwpFK2G0DIMpS0JAqeo4ttXuCyiPP2CFdoQ2urRhVuaBhjEZa_b7gLha3_as0w1m4ClO2sGBzfyNaIqPkkWjB1jaOw3FAr27Cq4Im_VbmrZ3AVL-EhaDAPfmOeGhBQna4AutYuLPuk_kKqF8_0u8svjAiXEb4gALoUELl9AJ6c28FZVDFslr64H08nlvtMx1Bx0QMfdmGStZO8EjwgUaFhxkMz2GOtjEcgFTC5zPqhnoOzqeMFR0uGC9HFRE4ktKRDrdofVeyIjV2D4xfacwG0_t8eK4rw_3BmeHBKZ7sqKj4W1jA";

        // HS256 doen't work with firebase
        //return JWT::encode($payload, $private_key, "HS256");
        return JWT::encode($payload, $private_key, "HS256");
    }

    
    
}


/************************************************/

// GET DATA LIST COLLECTION
//$users_list = $firebase->get('firebase_v4_local/users')->query();
//wp_send_json( $users_list );

/************************************************/

// QUERY BY SPECIFIC KEY
//$query = new Query();
//$query->orderByChildKey("meta/email");
//$query->equalTo("codystuard@gmail.com");
//$users_queried = $firebase->query('firebase_v4_local/users', $query);
//wp_send_json( $users_queried );

/************************************************/

// QUERY BY KEY
//$query = new Query();
//$query->orderByKey();
//$query->equalTo("gt9hmvyuoaSKrQObPFyiKDQw4pr2");
//$users = $firebase->query('firebase_v4_local/users', $query);
//wp_send_json($users);

/************************************************/

/*
$query = new Query();
$query->orderByChildKey("meta/email");
$query->equalTo("estuardoeg@gmail.com");
$users_queried = $firebase->query('firebase_v4_local/users', $query);

$user = array_values($users_queried)[0];
$user["meta"]["phone"] = "55802600";
$firebase->set($user, 'firebase_v4_local/users/'.$user["authentication-id"]);
var_dump( $user );
//wp_send_json( $users_queried );
exit();
*/

        

//$firebase->set(['key' => 'value'], 'my/data');
//$firebase->set('new value', 'my/data/key');
//$firebase->delete('my/data');



//wp_send_json($db);










//$return = createAuthToken("e@nirki.com", false);
//wp_send_json($config_json->client_email);
//wp_send_json($return);
//exit();
