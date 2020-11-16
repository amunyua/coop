<?php

namespace Amunyua\Coop;

use Ixudra\Curl\Facades\Curl;
use Symfony\Component\Dotenv\Dotenv;

class Coop
{
    public static function env($env_param){

        $dotenv = new Dotenv();

//        $dotenv->load('../.env');
        $dotenv->load('../.env');

        $env = getenv($env_param);

        return $env;
    }

    public static function generateLiveToken(){

        try {
            $consumer_key = env("C_CONSUMER_KEY");
            $consumer_secret = env("C_CONSUMER_SECRET");
        } catch (\Throwable $th) {
            $consumer_key = self::env("C_CONSUMER_KEY");
            $consumer_secret = self::env("C_CONSUMER_SECRET");
        }

        if(!isset($consumer_key)||!isset($consumer_secret)){
            die("please declare the consumer key and consumer secret as defined in the documentation");
        }
        $url = 'http://developer.co-opbank.co.ke:8280/token?grant_type=client_credentials';
        $credentials = base64_encode($consumer_key.':'.$consumer_secret);
        $header = array("Authorization: Basic ".$credentials);
        $content = "grant_type=client_credentials";
        $curl = curl_init();

        curl_setopt_array($curl, array(

            CURLOPT_URL => $url,

            CURLOPT_HTTPHEADER => $header,

            CURLOPT_SSL_VERIFYPEER => false,

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_POST => true,

            CURLOPT_POSTFIELDS => $content

        ));

        $response = curl_exec($curl);
        return json_decode($response)->access_token;
    }


    /**
     * use this function to generate a sandbox token
     * @return mixed
     */
    public static function generateSandBoxToken(){

        try {
            $consumer_key = env("C_CONSUMER_KEY");
            $consumer_secret = env("C_CONSUMER_SECRET");
//            $consumer_key = 'Ek4deBXb4TpGxXjmtSBBggm9Rwka';
//            $consumer_secret ="0QjSMnlHBfCgBoXrsw0uh1Fg3fUa";
        } catch (\Throwable $th) {
            $consumer_key = self::env("C_CONSUMER_KEY");
            $consumer_secret = self::env("C_CONSUMER_SECRET");
        }

        if(!isset($consumer_key)||!isset($consumer_secret)){
            die("please declare the consumer key and consumer secret as defined in the documentation");
        }
        $url = 'https://developer.co-opbank.co.ke:8243/token';
        $credentials = base64_encode($consumer_key.':'.$consumer_secret);
        $header = array("Authorization: Basic ".$credentials);
        $content = "grant_type=client_credentials";
        $curl = curl_init();

        curl_setopt_array($curl, array(

            CURLOPT_URL => $url,

            CURLOPT_HTTPHEADER => $header,

            CURLOPT_SSL_VERIFYPEER => false,

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_POST => true,

            CURLOPT_POSTFIELDS => $content

        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $token =json_decode($response)->access_token;
//        print_r($response);
        return $token;
    }

    public static function getAccountBalance($accountNumber = '12345678912345'){
//        print 'here';
        $requestPayload='{
                    "MessageReference": "40ca18c6765086089a1",
                  "AccountNumber": "12345678912345"
            } ';

        $url = 'https://developer.co-opbank.co.ke:8243/Enquiry/AccountBalance/1.0.0/Account';
        $token = self::generateSandBoxToken();
        $headers = array('Content-Type: application/json',"Authorization: Bearer {$token}");
//        print $token;

      /*  $process = curl_init();

        curl_setopt($process, CURLOPT_URL, $url);

        curl_setopt($process, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($process, CURLOPT_POSTFIELDS, $requestPayload);

        curl_setopt($process, CURLOPT_CUSTOMREQUEST, "POST");

        curl_setopt($process, CURLOPT_TIMEOUT, 30);

        curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);

        $response = curl_exec($process);

        print_r($response);die;
//        curl_close($process);

        return json_decode($response);*/

        $r = Curl::to($url)
            ->withBearer($token)
            ->asJson(true)
            ->withData([
                'AccountNumber' => $accountNumber
            ])
            ->returnResponseObject()
            ->post();
        return $r;
    }
}
