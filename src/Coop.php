<?php

namespace Amunyua\Coop;

use Symfony\Component\Dotenv\Dotenv;

class Coop
{
    public static function env($env_param){

        $dotenv = new Dotenv();

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
        $url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        $credentials = base64_encode($consumer_key.':'.$consumer_secret);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials)); //setting a custom header
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $curl_response = curl_exec($curl);

        return json_decode($curl_response)->access_token;


    }


    /**
     * use this function to generate a sandbox token
     * @return mixed
     */
    public static function generateSandBoxToken(){

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
        $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        $credentials = base64_encode($consumer_key.':'.$consumer_secret);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials)); //setting a custom header
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $curl_response = curl_exec($curl);

        return json_decode($curl_response)->access_token;
    }
}
